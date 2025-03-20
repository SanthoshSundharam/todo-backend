<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        
        try {
            $request->validate([
                "name"=>'required|string',
                "email"=>"required|email|unique:users,email",
                "password"=>"required|string|confirmed|min:6"
    
            ]);

            $existingUser = User::where("email", $request->email)->first();
            if ($existingUser) {
                return response()->json(['success' => false, 'message' => 'User already exists.'], 409);
            }
                $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
            ]);
                


                return response()->json(['success' => true]);        
            } 
        catch (\Exception $e) 
        {
            return response()->json(['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()], 500); 
        }
    }

    public function login(Request $request){
        

        try{
            $request->validate([
                "email"=>"required|email",
                "password"=>"required|string",
            ]);
            // $existingUser = User::where("email", $request->email)->first();
            if(Auth::attempt(['email'=>$request->email, 'password'=> $request->password])){
                $user = Auth::user();
                //$token = $user->createToken('App')->plainTextToken;

                return response()->json([
                    'success' => true,
                    'message' => 'Login successful.',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    // 'token' => $token,
                ]);
                
        }

    }
    catch (\Exception $e){
        return response()->json(['success'=> false,'message'=> ''. $e->getMessage()],);
    }
 }
}
