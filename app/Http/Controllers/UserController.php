<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;

use App\Models\User;
use App\Models\UserTermsOfUse;
use App\Models\UserCompany;

use App\Http\Resources\UserBasicResource;
use App\Http\Resources\UserDetailResource;

use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\File;
use App\Rules\TermsOfUsesCheck;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Mail;

class UserController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => [
                'required',
                Password::min(8)->numbers()->letters()
            ],
            'c_password' => 'required|same:password',
            'birth' => 'required|date',
            'sex' => 'required|boolean',

            'interests.*' => Rule::exists('categories', 'id'),
            'terms_of_uses' => [
                'required',
                new TermsOfUsesCheck(),
            ],

        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
     
        $token = Str::random(64);
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['email_verity_token'] = $token;
        $user = User::create($input);

        if (array_key_exists('interests', $input))
            $user->interests()->attach($input['interests']);

        foreach($input['terms_of_uses'] as $key => $val)
            $user->termsOfUses()->attach($key, ['agree' => $val]);

        Mail::send('emailVerificationEmail', ['token' => $token, 'name' => $input['name'], 'email' => $input['email'], 'time' => now()->toDateTimeString()], function($message) use($request) {
            $message->to($request->email);
            $message->subject('[마이스 메이트] 회원가입 안내');
        });

        $success = [];
        return $this->sendResponse($success, 'User register successfully.');
    }

    public function registerCompany(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        if ($user->id != $id)
            return $this->sendError('Authentication Error.'); 

        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'company_id' => 'required',
            'company_id_file' => [
                'required',
                File::types(['pdf', 'png'])->max('10mb'),
            ],
            'name' => 'required',
            'department' => 'required',
            'position' => 'required',
            'contact' => 'required',
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $uploadFolder = 'company_id';
        $image = $request->file('company_id_file');

        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $image_url = Storage::disk('public')->url($image_uploaded_path);
     
        $input = $request->only(['company_name', 'company_id', 'name', 'department', 'position', 'contact']);
        $input['user_id'] = $user->id;
        $input['company_id_file'] = $image_uploaded_path;
        $input['company_id_file_name'] = $image->getClientOriginalName();

        $company = $user->company;
        if ($company == null) {
            UserCompany::create($input);
            $str = 'User company register successfully.';
        } else {
            Storage::disk('public')->delete($company->company_id_file);
            $input["accept"] = 0;
            $company->update($input);
            $str = 'User company update successfully.';
        }

        $success = [];
        return $this->sendResponse($success, $str);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        if ($user->id != $id && !$user->is_admin)
            return $this->sendError('Authentication Error.'); 

        $validator = Validator::make($request->all(), [
            'password' => [
                Password::min(8)->numbers()->letters()
            ],
            'birth' => 'date',
            'sex' => 'boolean',
            'interests.*' => Rule::exists('categories', 'id'),
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        if (!$user->is_admin)
            $input = $request->only(['contact', 'password', 'sex', 'birth', 'interests']);
        else {
            $input = $request->only(['contact', 'interests']);
            $user = User::find($id);
        }

        if (array_key_exists('password', $input))
            $input['password'] = bcrypt($input['password']);
        if (array_key_exists('interests', $input))
            $user->interests()->sync($input['interests']);

        $user->update($input);

        $success = [];
        return $this->sendResponse($success, 'User update successfully.');
    }


    public function retrive(Request $request, string $id): JsonResponse
    {
        $authUser = $request->user();
        if ($authUser->id != $id)
            return $this->sendError('Authentication Error.');

        return new UserBasicResource($authUser);
    }

    public function retriveMe(Request $request): JsonResponse
    {
        $success = new UserBasicResource($request->user());
        return $this->sendResponse($success, 'User retrived data');
    }
    
    public function retriveBasic(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        if ($user->id != $id)
            return $this->sendError('Authentication Error.');

        $success = new UserBasicResource($request->user());
        return $this->sendResponse($success, 'User retrived data');
    }

    public function retriveDetail(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        if (!$user->is_admin)
            return $this->sendError('Authentication Error.');

        $success = new UserDetailResource(User::find($id));
        return $this->sendResponse($success, 'User retrived data');
    }


    public function setStateDeleted(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        if ($user->id != $id)
            return $this->sendError('Authentication Error.'); 

        $user->update(["state" => 2, "deleted_at" => now()]);

        $success = [];
        return $this->sendResponse($success, 'User delete successfully.');
    }

    public function setCompanyAccept(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        if (!$user->is_admin)
            return $this->sendError('Authentication Error.');
        
        $user = User::find($id);
        if ($user->company == null || $user->company->accept != 0)
            return $this->sendError('Authentication Error.');

        $validator = Validator::make($request->all(), [
            'accept' => 'required|boolean'
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        if ($request->accept) {
            $user->company()->update(["accept" => 2, "accepted_at" => now()]);
            Mail::send('grantCompanyEmail', ['name' => $user->name, 'company_name' => $user->company->company_name, 'time' => now()->toDateTimeString()], function($message) use($user) {
                $message->to($user->email);
                $message->subject('[마이스 메이트] 호스트 회원 승인 안내');
            });

            return $this->sendResponse([], 'Company granted');
        } else {
            $user->company()->update(["accept" => 1]);
            Mail::send('notGrantCompanyEmail', ['name' => $user->name], function($message) use($user) {
                $message->to($user->email);
                $message->subject('[마이스 메이트] 호스트 회원 미승인 안내');
            });
            
            return $this->sendResponse([], 'Company is not granted');
        }
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                Rule::exists('users', 'email')
            ]
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $user = User::where(['email' => $input['email']])->first();
        $password = $user->resetPassword();

        Mail::send('passwordResetEmail', ['password' => $password, 'name' => $user->name], function($message) use($user) {
            $message->to($user->email);
            $message->subject('[마이스 메이트] 임시 비밀번호 발급 안내');
        });

        $success = [];
        return $this->sendResponse($success, 'User password reset successfully.');
    }






     


    public function testDeleteUser(Request $request, string $id) : JsonResponse
    {
        User::destroy($id);
        $success = [];
        return $this->sendResponse($success, 'User delete successfully.');
    }
}
