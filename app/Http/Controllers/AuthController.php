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
                "name" => 'required|string',
                "email" => "required|email|unique:users,email",
                "password" => "required|string|confirmed|min:6"

            ]);

            $user = $this->userRepo->register($data);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {


        try {
            $data = $request->validate([
                "email" => "required|email",
                "password" => "required|string",
            ]);

            if (!Auth::attempt($data)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $user = $this->userRepo->findByEmail($request->email);

            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful.',
                'token' => $token,
                'user'=> $user['id']

            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => '' . $e->getMessage()], );
        }
    }
}
