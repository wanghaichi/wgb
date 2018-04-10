<?php
/**
 * Created by PhpStorm.
 * User: liebes
 * Date: 2018/4/8
 * Time: 4:23 PM
 */

namespace App\Http\Controllers;
use App\Models\STUser;
use Illuminate\Http\Request;

class STController extends Controller{
    public function loginPage(){
        return view('login');
    }

    public function login(Request $request){
        $data = $request->only(['username', 'password']);
        if(count($data) != 2 || mb_strlen($data['username'] < 1)){
            return back()->withInput()->with('error', '用户名或密码错误，请重试');
        }
        $username = $data['username'];
        $user = STUser::where('username', $username)->first();
        if(!$user || $user->password != $data['password']){
            return back()->withInput()->with('error', '用户名或密码错误，请重试');
        }
        return view('info', ['data' => $user]);
    }


}