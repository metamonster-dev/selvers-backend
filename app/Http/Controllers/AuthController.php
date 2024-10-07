<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

use Validator;
use Illuminate\Validation\Rules\Password;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    //

    public function createToken(Request $request) : JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => [
                'required',
                Password::min(8)->numbers()->letters()
            ],
            'remember_me' => 'required|boolean',
        ]);

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'state' => 1])) { 
            $user = Auth::user();
            if ($request->remember_me)
                $success['token'] =  $user->createToken(env('APP_NAME', 'laravel'))->plainTextToken; // 만료 X
            else
                $success['token'] =  $user->createToken(env('APP_NAME', 'laravel'), ['*'], now()->addHours(3))->plainTextToken; // 3시간 뒤 만료
            $success['name'] =  $user->name;
            $success['user_id'] = $user->id;
   
            return $this->sendResponse($success, 'User token create successfully.');
        } else { 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }

    public function deleteToken(Request $request) : JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse([], 'User token delete successfully.');
    }


    public function verityEmail($token): RedirectResponse
    {
        $user = User::where('email_verity_token', $token)->first();
        $message = 'Sorry your email cannot be identified.';

        if(!is_null($user) ){
            if(!!is_null($user->email_verified_at)) {
                $user->email_verified_at = now();
                $user->state = 1;
                $user->save();
                $message = "Your e-mail is verified. You can now login.";
            } else {
                $message = "Your e-mail is already verified. You can now login.";
            }
        }

        $frontUrl = env('FRONT_END_URL', 'https://www-test.micemate.io'); //For Dev
        return redirect()->away($frontUrl);
    }
}
