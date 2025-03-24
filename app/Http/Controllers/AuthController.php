<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;

class AuthController extends Controller
{

    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function register(Request $request)
    {
        
        try {
            $data = $request->validate([
                "name"=>'required|string',
                "email"=>"required|email|unique:users,email",
                "password"=>"required|string|confirmed|min:6"
    
            ]);

            // $existingUser = User::where("email", $request->email)->first();
            // if ($existingUser) {
            //     return response()->json(['success' => false, 'message' => 'User already exists.'], 409);
            // }
            //     $user = User::create([
            //     'name' => $request->input('name'),
            //     'email' => $request->input('email'),
            //     'password' => bcrypt($request->input('password')),
            // ]);

                $user = $this->userRepo->register($data);

                return response()->json(['success' => true]);        
            } 
        catch (\Exception $e) 
        {
            return response()->json(['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()], 500); 
        }
    }

    public function login(Request $request){
        

        try{
            $data = $request->validate([
                "email"=>"required|email",
                "password"=>"required|string",
            ]);
            
            // $existingUser = User::where("email", $request->email)->first();
        
            // if(Auth::attempt(['email'=>$request->email, 'password'=> $request->password])){
            //     $user = Auth::user();
                
            //     $token = $user->createToken('Task')->plainTextToken;
            //     return response()->json([
            //         'success' => true,
            //         'message' => 'Login successful.',
            //         'user' => [
            //             'id' => $user->id,
            //             'name' => $user->name,
            //             'email' => $user->email,
            //         ],
            //          'token' => $token,
            //     ]);

            if (!Auth::attempt($data)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $user = $this->userRepo->findByEmail($request->email);

            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                        'success' => true,
                        'message' => 'Login successful.',
                         'token' => $token,
                         
                    ]);
                    
        }

    catch (\Exception $e){
        return response()->json(['success'=> false,'message'=> ''. $e->getMessage()],);
    }
 }
}
