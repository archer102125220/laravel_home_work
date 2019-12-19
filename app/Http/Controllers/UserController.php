<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    public function register(Request $request){
        $postData = $request->all();
        $objValidator = Validator::make(
            $postData,
            [
                'account' => [
                    'request',
                    'between:6,20',
                    'regex:/^(([a-z]+[0-9]+)|([0-9]+[a-z]+))[a-z0-9]*$/i',
                    'unique:users'
                ],
                'password' => [
                    'required',
                    'between:6,20'
                ],
                'name' => [
                    'required',
                    'max:20'
                ],
            ],
            [
                'account.required' => '請輸入帳號',
                'account.between' => '帳號需介於6-20英文字',
                'account.regex' => '帳號須包含英文數字',
                'account.unique' => '帳號已存在',
                'password.required' => '請輸入密碼',
                'password.between' => '密碼需介於6-20個英文字',
                'name.required' => '請輸入姓名',
                'name.max' => '姓名不可超過20個英文字(1個中文字等於2個英文字)'
            ]
        );
        if($objValidator->fails()){
            return response()->json($objValidator->errors()->all(), 400);
        }
        $postData['password']=bcrypt($postData['password']);
        User::create($postData);
        return response()->json('註冊成功', 200);
    }
}
