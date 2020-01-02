<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Hash;

class UserService {
    public function register($data) {
        $data['password'] = bcrypt($data['password']);
        //加密密碼
        User::create($data);
    }

    public function login($data)
    {
        $user = User::where('account', $data['account'])->first();
        //select('*')可省略
        if ($user) {
            //有此用戶
            if (Hash::check($data['password'], $user->password))
                return $user;
            else
                return "密碼錯誤";
        }
        return '無此用戶';
    }
}