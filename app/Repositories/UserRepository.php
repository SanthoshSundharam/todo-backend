<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository{
    public function register(array $data){
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function findByEmail($email){
        return User::where('email', $email)->first();
    }
}
