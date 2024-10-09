<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;

use App\Models\Event;
use App\Models\EventPayable;
use App\Models\EventRecurit;
use App\Models\EventSurvey;
use App\Models\EventFAQ;
use App\Models\EventBooth;
use App\Models\EventReject;

use App\Models\Category;
use App\Models\Tag;

use App\Http\Resources\EventEditBasicResource;
use App\Http\Resources\EventEditDetailResource;
use App\Http\Resources\EventEditRecuritResource;

use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Mail;

class EventController extends BaseController
{

    public function create(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user->company == null && $user->company->accept != 2)
            return $this->sendError('Authentication Error.'); 

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')
            ],
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->only(['title', 'category_id']);
        $input['user_id'] = $user->id;
        $event = Event::create($input);
        $eventReject = EventReject::create(['event_id' => $event->id]);

        $success = [
            'event_id' => $event->id
        ];
        return $this->sendResponse($success, 'Event create successfully.');
    }

    public function retriveBasic(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $event = Event::find($id);

        if (!$user->is_admin) {
            if ($user->company == null && $user->company->accept != 2 && $user->id != $event->user_id)
                return $this->sendError('Authentication Error.');
        }
        
        $success = new EventEditBasicResource($event);
        return $this->sendResponse($success, 'Event edit basic data');
    }

    public function updateBasic(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $event = Event::find($id);
        if ($user->company == null && $user->company->accept != 2 && $user->id != $event->user_id)
            return $this->sendError('Authentication Error.'); 

        $validator = Validator::make($request->all(), [
            'category_id' => Rule::exists('categories', 'id'),
            'img1' => File::types(['jpg', 'jpeg', 'png'])->max('10mb'),
            'img2' => File::types(['jpg', 'jpeg', 'png'])->max('10mb'),
            'event_start_date' => 'date',
            'event_start_time' => 'date_format:H:i',
            'event_end_date' => 'date',
            'event_end_time' => 'date_format:H:i',
            'progress_type' => 'digits_between:0,2',
            'payable_type' => 'digits_between:0,5',
            'payable_start_date' => 'date',
            'payable_end_date' => 'date',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->only(['title', 'category_id', 'img1', 'img2', 'event_start_date', 'event_start_time', 'event_end_date', 'event_end_time', 
                                'payable_type', 'payable_start_date', 'payable_end_date', 'payable_type', 'payable_price', 'payable_price_url', 'progress_url', 'position1', 'position2']);

        $uploadFolder = 'event_img';
        if (array_key_exists('img1', $input)) {
            if ($event->img1 != null)
                Storage::disk('public')->delete($event->img1);

            $image = $request->file('img1');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $image_url = Storage::disk('public')->url($image_uploaded_path);

            $input['img1'] = $image_uploaded_path;
        }

        if (array_key_exists('img2', $input)) {
            if ($event->img2 != null)
                Storage::disk('public')->delete($event->img2);

            $image = $request->file('img2');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $image_url = Storage::disk('public')->url($image_uploaded_path);

            $input['img2'] = $image_uploaded_path;
        }

        $event->update($input);

        $success = [$input];
        return $this->sendResponse($success, 'Event default data update successfully.');
    }

    public function retriveDetail(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $event = Event::find($id);

        if (!$user->is_admin) {
            if ($user->company == null && $user->company->accept != 2 && $user->id != $event->user_id)
                return $this->sendError('Authentication Error.');
        }
        
        $success = new EventEditDetailResource($event);
        return $this->sendResponse($success, 'Event edit detail data');
    }

    public function updateDetail(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $event = Event::find($id);
        if ($user->company == null && $user->company->accept != 2 && $user->id != $event->user_id)
            return $this->sendError('Authentication Error.');

        $input = $request->only(['content', 'tags']);
        if (array_key_exists('tags', $input)) {
            $tagIds = [];
            foreach($input['tags'] as $name) {
                $tag = Tag::where('name', $name)->first();
                if ($tag == null)
                    $tag = Tag::create(["name" => $name]);

                $tagIds[] = $tag->id;
            }
            $event->tags()->attach($tagIds);
        }
        $event->update($input);

        $success = [$input];
        return $this->sendResponse($success, 'Event default data update successfully.');
    }

    public function retriveRecurit(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $event = Event::find($id);

        if (!$user->is_admin) {
            if ($user->company == null && $user->company->accept != 2 && $user->id != $event->user_id)
                return $this->sendError('Authentication Error.');
        }
        
        $success = new EventEditRecuritResource($event);
        return $this->sendResponse($success, 'Event edit detail data');
    }

    public function updateRecurit(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $event = Event::find($id);
        if ($user->company == null && $user->company->accept != 2 && $user->id != $event->user_id)
            return $this->sendError('Authentication Error.');

        $validator = Validator::make($request->all(), [
            'recurit_type' => 'digits_between:0,2',
            'recurit_start_date' => 'date',
            'recurit_start_time' => 'date_format:H:i',
            'recurit_end_date' => 'date',
            'recurit_end_time' => 'date_format:H:i',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->only(['recurit_type', 'recurit_start_date', 'recurit_start_time', 'recurit_end_date', 'recurit_end_time', 'informations']);

        if (array_key_exists('informations', $input)) {
            foreach($input['informations'] as $key => $val)
                $event->information()->sync($key, ['required' => $val]);
        }
        $event->update($input);


        $success = [$input];
        return $this->sendResponse($success, 'Event default data update successfully.');
    }





}
