<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|digits_between:7,15',
            'email' => 'required|string|email|max:255|unique:users',
            'age' => 'required|integer|min:0|max:120', 
            'Country' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        // dd($request);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'age' => $request->age,
            'Country' => $request->Country,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['user' => $user], 201);
    }
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user=User::where('email',$request->email)->first();
        // if(!User::where($request->password,$user->password)){
        if(!$user ||!Hash::check($request->password,$user->password)){
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
                }
                $token = $user->createToken('API Token')->plainTextToken;
                return response()->json(['token' => $token], 200);
        }
        public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }
    public function sendResetLinkEmail(Request $request)
{
    $validated = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
    ]);

    if ($validated->fails()) {
        return response()->json(['message' => 'Invalid email address'], 400);
    }

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status == Password::RESET_LINK_SENT
        ? response()->json(['message' => 'Reset link sent to your email.'])
        : response()->json(['message' => 'Failed to send reset link'], 400);
}

}