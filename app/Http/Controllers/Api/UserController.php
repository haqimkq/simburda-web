<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Utils;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Mail\ForgetPasswordMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

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
            if($request->device_token){
                $user->update(['device_token' => $request->device_token]);
            }
            // $token = $user->createToken('authToken', ["$user->role"])->plainTextToken;
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
            $request->user()->update([
                'device_token' => null,
            ]);
            return ResponseFormatter::success(null, null, 'Logout Successfully');
        }catch (Exception $error){
            return ResponseFormatter::error("Logout Failed:". $error->getMessage());
        }
    }

    public function showResetPasswordForm($token, $email) { 
        return view('resetpassword.resetPasswordForm', ['token' => $token,'email' => $email]);
    }

    public function submitResetPasswordForm(Request $request){
          $request->validate([
              'password' => 'required|string|min:8|confirmed',
              'password_confirmation' => 'required|same:password'
          ]);
  
          $updatePassword = DB::table('password_resets')
                              ->where([
                                'email' => $request->email, 
                                'token' => $request->token
                              ])
                              ->first();
  
          if(!$updatePassword){
              return back()->withInput()->with('error', 'Invalid token!');
          }
  
          $user = User::where('email', $request->email)
                      ->update(['password' => bcrypt($request->password)]);
 
          DB::table('password_resets')->where(['email'=> $request->email])->delete();
  
          return view('resetPassword.successChangePassword');
      }

    public function forgetPassword(Request $request){
        try{
            $request->validate([
                'email' => 'required|email'
            ]);
            if(collect(User::where('email',$request->email)->first())->isEmpty()){
                return ResponseFormatter::error("Unregistered Email");
            }
            $token = Str::random(64);
            DB::table('password_resets')->insert([
                'email' => $request->email, 
                'token' => $token, 
                'created_at' => Carbon::now()
            ]);
            Mail::to($request->email)->send(new ForgetPasswordMail($token, $request->email));

            return ResponseFormatter::success(null,null,"Email Successfully sent");
            
        } catch (Exception $error){
            return ResponseFormatter::error("Authentication Failed:". $error->getMessage());
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
    public function setDeviceToken(Request $request){
        try{
            $user = $request->user();
            $request->validate([
                'device_token' => 'required',
            ]);
            $user->device_token = $request->device_token;
            $user->update();
            return ResponseFormatter::success(null, null, 'Set User Device Token Success');
        }catch (Exception $error){
            return ResponseFormatter::error("Set User Device Token Failed:". $error->getMessage());
        }
    }
    public function getTtd(Request $request){
        try{
            $user = $request->user();
            return ResponseFormatter::success('ttd', $user->ttd, 'Get User TTD Success');
        }catch (Exception $error){
            return ResponseFormatter::error("Get User TTD Failed:". $error->getMessage());
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
    public function uploadPhoto(Request $request){
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

                return ResponseFormatter::success(null, null,'Photo successfully uploaded');
            }
        }catch (Exception $error){
            return ResponseFormatter::error("Upload Photo Failed:". $error->getMessage());
        }
    }

    public function getDetailUser(Request $request){
        try{
            return ResponseFormatter::success('user', $request->user, 'Data Berhasil Didapat');
        }catch (Exception $error) {
            return ResponseFormatter::error("Authentication Failed:". $error->getMessage());
        }
    }

    public function uploadTTD(Request $request){
        try{
            User::validateChangePhoto($request);
            if ($request->file('ttd')) {
                $user = $request->user();
                $file = $request->file('ttd');
                $extension = $file->extension();
                $filename ="$user->id.$extension";
                $request->ttd->storeAs('assets/users/ttd', $filename,'public');
                $output_file = "assets/users/ttd/$filename";

                //store your file into database
                $user->foto = $output_file;
                $user->update();

                return ResponseFormatter::success(null, null,'TTD successfully uploaded');
            }
        }catch (Exception $error){
            return ResponseFormatter::error("Upload TTD Failed:". $error->getMessage());
        }
    }
}