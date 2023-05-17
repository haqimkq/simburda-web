<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function register(Request $request){
        try {
            $request['role'] = "USER";
            User::validateCreateUser($request);
            $request['password'] = Hash::make($request->password);
            $user = User::createUser($request);
            return ResponseFormatter::success('user', $user, 'User Registered');
        } catch (Exception $error) {
            return ResponseFormatter::error("Authentication Failed: ". $error->getMessage());
        }
    }
    public function login(Request $request){
        try{
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error('Authentication Failed');
            }

            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('authToken')->plainTextToken;
            $user['token'] = $token;

            return ResponseFormatter::success('user', $user, 'Login Successfully');

        }catch (Exception $error) {
            return ResponseFormatter::error("Authentication Failed:". $error->getMessage());
        }
    }
    public function logout(Request $request){
        try{
            $request->user()->currentAccessToken()->delete();
            return ResponseFormatter::success(null, null, 'Logout Successfully');
        }catch (Exception $error){
            return ResponseFormatter::error("Logout Failed:". $error->getMessage());
        }
    }

    public function currentAccessToken(Request $request){
        try{
            $user = $request->user()->currentAccessToken()->tokenable;
            return ResponseFormatter::success('user', $user, 'Get Current User Access Token');
        }catch (Exception $error){
            return ResponseFormatter::error("Get Current User Access Token Failed:". $error->getMessage());
        }
    }
    public function updateProfile(Request $request){
        try{
            User::validateChangeProfile($request);
            $data = $request->all();
            $user = $request->user();
            $user->update($data);
            return ResponseFormatter::success('user', $user, 'Profile Updated');
        }catch (Exception $error){
            return ResponseFormatter::error("Update Profile Failed:". $error->getMessage());
        }
    }
    public function updatePhoto(Request $request){
        try{
            User::validateChangePhoto($request);
            if ($request->file('foto')) {
                $user = $request->user();
                $file = $request->file('foto');
                $extension = $file->extension();
                $filename ="$user->id.$extension";
                $request->foto->storeAs('assets/users', $filename,'public');
                $output_file = "assets/users/$filename";

                //store your file into database
                $user->foto = $output_file;
                $user->update();

                return ResponseFormatter::success('user', $user,'File successfully uploaded');
            }
        }catch (Exception $error){
            return ResponseFormatter::error("Update Profile Failed:". $error->getMessage());
        }
        
    }
}
