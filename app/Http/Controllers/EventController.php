<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;

use App\Models\Event;
use App\Models\EventSurvey;
use App\Models\EventFAQ;
use App\Models\EventBooth;
use App\Models\EventReject;

use App\Models\Category;
use App\Models\Tag;

use App\Http\Resources\EventEditBasicResource;
use App\Http\Resources\EventEditDetailResource;
use App\Http\Resources\EventEditRecuritResource;
use App\Http\Resources\EventEditSurveyResource;
use App\Http\Resources\EventEditFAQResource;

use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
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

    public function checkState(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $event = Event::find($id);

        if (!$user->is_admin) {
            if ($user->company == null && $user->company->accept != 2 && $user->id != $event->user_id)
                return $this->sendError('Authentication Error.');
        }

        $success = [
            "basic" => $event->checkBasic(),
            "detail" => $event->checkDetail(),
            "recurit" => $event->checkRecurit(),
            "survey" => $event->checkSurvey(),
            "faq" => $event->checkFAQ(),
        ];
        return $this->sendResponse($success, 'Event edit state data');
    }

    public function submit(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $event = Event::find($id);

        if (!$user->is_admin) {
            if ($user->company == null && $user->company->accept != 2 && $user->id != $event->user_id)
                return $this->sendError('Authentication Error.');
        }

        $check = true;
        if (!$event->checkBasic())
            $check = false;
        if (!$event->checkDetail())
            $check = false;
        if (!$event->checkRecurit())
            $check = false;
        if (!$event->checkSurvey())
            $check = false;
        if (!$event->checkFAQ())
            $check = false;

        if (!$check)
            return $this->sendError('Not finished');

        $event->update(["state" => 1]);

        $success = [];
        return $this->sendResponse($success, 'Event submit finished');
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
            'progress_type' => 'numeric|between:0,2',
            'payable_type' => 'numeric|between:0,5',
            'payable_start_date' => 'date',
            'payable_end_date' => 'date',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->only(['title', 'category_id', 'img1', 'img2', 'event_start_date', 'event_start_time', 'event_end_date', 'event_end_time', 
                                'payable_type', 'payable_start_date', 'payable_end_date', 'payable_price1', 'payable_price2', 'payable_price_url', 'progress_type', 'progress_url', 'position1', 'position2']);

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

        $progress_type = array_key_exists('progress_type', $input) ? $input['progress_type'] : $event->progress_type;
        if ($progress_type == 0)
            $input['progress_url'] = null;

        $payable_type = array_key_exists('payable_type', $input) ? $input['payable_type'] : $event->payable_type;
        if ($payable_type == 0 || $payable_type == 1) {
            $input['payable_start_date'] = null;
            $input['payable_end_date'] = null;
            $input['payable_price_url'] = null;
            $input['payable_price1'] = null;
            $input['payable_price2'] = null;
        } else if ($payable_type == 2) {
            $input['payable_start_date'] = null;
            $input['payable_end_date'] = null;
            $input['payable_price_url'] = null;
            $input['payable_price1'] = null;
        } else if ($payable_type == 5) {
            $input['payable_start_date'] = null;
            $input['payable_end_date'] = null;
            $input['payable_price1'] = null;
            $input['payable_price2'] = null;
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
        return $this->sendResponse($success, 'Event edit recurit data');
    }

    public function updateRecurit(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $event = Event::find($id);
        if ($user->company == null && $user->company->accept != 2 && $user->id != $event->user_id)
            return $this->sendError('Authentication Error.');

        $validator = Validator::make($request->all(), [
            'recurit_type' => 'numeric|between:0,2',
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
            $informations = collect($input['informations'])->map(function ($val, $key) {
                return ['required' => $val];
            });
            $event->information()->sync($informations);
        }
        $event->update($input);

        $success = [$input];
        return $this->sendResponse($success, 'Event recurit data update successfully.');
    }

    public function retriveSurvey(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $event = Event::find($id);

        if (!$user->is_admin) {
            if ($user->company == null && $user->company->accept != 2 && $user->id != $event->user_id)
                return $this->sendError('Authentication Error.');
        }
        
        $success = new EventEditSurveyResource($event);
        return $this->sendResponse($success, 'Event edit survey data');
    }

    public function updateSurvey(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $event = Event::find($id);
        if ($user->company == null && $user->company->accept != 2 && $user->id != $event->user_id)
            return $this->sendError('Authentication Error.');

        $validator = Validator::make($request->all(), [
            'is_survey' => 'boolean',
            'surveys.*.type' => 'sometimes|required|numeric|between:0,2',
            'surveys.*.options' => 'sometimes|required|array',
            'surveys.*.options.*' => 'sometimes|required|string',
            'surveys.*.required' => 'sometimes|required|boolean',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->only(['is_survey', 'surveys']);

        if (array_key_exists('surveys', $input)) {
            $surveys = $event->surveys();
            $surveys->delete();

            foreach($input['surveys'] as $value) {
                $options = $value['options'];
                if ($value['type'] == 2)
                    $options = [$options[0]];

                $value['options'] = json_encode($options);
                $surveys->create($value);
            }
        }
        $event->update($input);

        $success = [$input];
        return $this->sendResponse($success, 'Event survey data update successfully.');
    }

    public function retriveFAQ(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $event = Event::find($id);

        if (!$user->is_admin) {
            if ($user->company == null && $user->company->accept != 2 && $user->id != $event->user_id)
                return $this->sendError('Authentication Error.');
        }
        
        $success = new EventEditFAQResource($event);
        return $this->sendResponse($success, 'Event edit FAQ data');
    }

    public function updateFAQ(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $event = Event::find($id);
        if ($user->company == null && $user->company->accept != 2 && $user->id != $event->user_id)
            return $this->sendError('Authentication Error.');

        $validator = Validator::make($request->all(), [
            'is_FAQ' => 'boolean',
            'faqs.*.question' => 'sometimes|required|string',
            'faqs.*.answer' => 'sometimes|required|string',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->only(['is_FAQ', 'faqs', 'contact_name', 'contact_email', 'contact_number']);

        if (array_key_exists('faqs', $input)) {
            $faqs = $event->faqs();
            $faqs->delete();

            foreach($input['faqs'] as $value) {
                $faqs->create($value);
            }
        }
        $event->update($input);

        $success = [$input];
        return $this->sendResponse($success, 'Event FAQ data update successfully.');
    }

    public function setEventAccept(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        if (!$user->is_admin)
            return $this->sendError('Authentication Error.');

        $user = $request->user();
        $event = Event::find($id);
        if ($event->event == null && $event->state != 1)
            return $this->sendError('Authentication Error.');

        $validator = Validator::make($request->all(), [
            'accept' => 'required|boolean',
            'reject' => 'sometimes|array',
            'reject.title' => 'required_with:reject.*|boolean',
            'reject.category' => 'required_with:reject.*|boolean',
            'reject.img' => 'required_with:reject.*|boolean',
            'reject.date' => 'required_with:reject.*|boolean',
            'reject.time' => 'required_with:reject.*|boolean',
            'reject.payable' => 'required_with:reject.*|boolean',
            'reject.progress' => 'required_with:reject.*|boolean',
            'reject.position' => 'required_with:reject.*|boolean',
            'reject.content' => 'required_with:reject.*|boolean',
            'reject.recurit_date' => 'required_with:reject.*|boolean',
            'reject.recurit_type' => 'required_with:reject.*|boolean',
            'reject.recurit_information' => 'required_with:reject.*|boolean',
            'reject.tag' => 'required_with:reject.*|boolean',
            'reject.survey' => 'required_with:reject.*|boolean',
            'reject.surveys' => 'required_with:reject.*|array',
            'reject.faq' => 'required_with:reject.*|boolean',
            'reject.faqs' => 'required_with:reject.*|array',
            'reject.contact' => 'required_with:reject.*|boolean',
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if ($request->accept) {
            $event->update(['state' => 3]);

            Mail::send('grantEventEmail', ['name' => $event->user->name, 'event_name' => $event->name, 
                'recurit_start_date' => $event->recurit_start_date->toDateTimeString() . ' ' . $event->recurit_start_time->toDateTimeString(), 'recurit_end_date' => $event->recurit_end_date->toDateTimeString() . ' ' . $event->recurit_end_time->toDateTimeString()], function($message) use($user) {
                $message->to($event->user->email);
                $message->subject('[마이스 메이트] ' . $event->name . ' 승인 안내');
            });

            return $this->sendResponse([], 'Event granted');
        } else {
            $reject = $input->reject;

            $event->surveys()->update(['is_reject' => $reject['surveys']]);
            $event->faqs()->update(['is_reject' => $reject['faqs']]);
            $event->reject()->update($reject);
            $event->update(['state' => 2]);

            Mail::send('notGrantEventEmail', ['name' => $event->user->name, 'event_name' => $event->name], function($message) use($user) {
                $message->to($event->user->email);
                $message->subject('[마이스 메이트] ' . $event->name . ' 반려 안내');
            });
            
            return $this->sendResponse([], 'Event is not granted');
        }

    }




}
