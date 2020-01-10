<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request) {
        $user = $request->all();
        $objValidator =Validator::make(
            $user,
            [
                'account' => [
                    'required',
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
        $user['password']=bcrypt($user['password']);
        User::create($user);
        return response()->json('註冊成功', 200);
    }

    public function login(Request $request)
    {
        $data=$request->all();
        $objValidator = Validator::make(
            $data,
            [
                'account' => [
                    'required',
                ],
                'password' => [
                    'required',
                ],
            ],
            [
                'account.required' => '請輸入帳號',
                'password.required' => '請輸入密碼',
            ]
        );
        if($objValidator->fails()){
            return response()->json($objValidator->errors()->all(), 400);
        }
        $user = User::where('account', $request['account'])->first();
        if($user){
            if(Hash::check($data['password'], $user->password)){
                $token = JWTAuth::fromUser($user);
                return response()->json($token, 200);
            }
            return response()->json(['密碼錯誤'], 400);
        }
        return response()->json(['無此用戶'], 400);
    }

    public function getThisUserData(Request $request)
    {
        $userData = $request->input('user');
        return response()->json($userData, 200);
    }

    
    public function userAll()
    {
        $user = User::all();
        if($user){
            return response()->json($user, 200);
        }
        return response()->json(['查無貼文'], 400);
    }

    public function editUser($userId, Request $request)
    {
        $userUpdata['password'] = $request->input('password');
        $userUpdata['name'] = $request->input('name');
        $objValidator = Validator::make(
            $userUpdata,
            [
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
                'password.required' => '請輸入密碼',
                'password.between' => '密碼需介於6-20個英文字',
                'name.required' => '請輸入姓名',
                'name.max' => '姓名不可超過20個英文字(1個中文字等於2個英文字)'
            ]
        );
        if($objValidator->fails()){
            return response()->json($objValidator->errors()->all(), 400);
        }
        
        $user = User::where('account', $userId)->first();
        $userUpdata['password']=bcrypt($user['password']);
        if($user){
            $user->password = $userUpdata['password'];
            $user->name = $userUpdata['name'];
            $user->save();
            return response()->json(['修改成功'], 200);
        }
        return response()->json(['無此用戶'], 400);
    }

    public function deleteUser(Request $request)
    {
        $userUpdata = $request->all();
        $objValidator = Validator::make(
            $userUpdata,
            [
                'account' => [
                    'required',
                ],
            ],
            [
                'account.required' => '請輸入帳號',
            ]
        );
        if($objValidator->fails()){
            return response()->json($objValidator->errors()->all(), 400);
        }
        $user = User::where('account', $userUpdata['account'])->first();
        if ($user) {
            $user->delete();
            return response()->json(['刪除成功'], 200);
        }else{
            return response()->json(['無此用戶'], 400);
        }
    }

}
