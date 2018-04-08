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
        $data = $request->only(['username']);
        if(count($data) != 1 || mb_strlen($data['username'] < 1)){
            return back()->withInput()->with('error', '请输入用户名');
        }
        $username = $data['username'];
        $user = STUser::where('username', $username)->first();
        if(!$user){
            return back()->withInput()->with('error', '用户名不存在，请重试');
        }
        return view('info', ['data' => $user]);
    }


}