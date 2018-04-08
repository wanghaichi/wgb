<?php
/**
 * Created by PhpStorm.
 * User: liebes
 * Date: 2018/4/3
 * Time: 3:14 PM
 */
namespace App\Http\Controllers;
use App\Models\Play;
use App\Models\SubTitle;

class IndexController extends Controller{
    public function index(){
        $detail = auth()->user();
        $id = $detail->spec_id;
        $play = Play::where('spec_id', $id)->first();
        $contentsC = SubTitle::where(['play_id'=> $play->id, 'language' => 'Chinese'])
            ->orderBy('content_id', 'DESC')->get()->all();
        $contentsE = SubTitle::where(['play_id'=> $play->id, 'language' => 'English'])
            ->orderBy('content_id', 'DESC')->get()->all();
        $res['status'] = 1;
        $contents = [
            'Chinese'   => $contentsC,
            'English'   => $contentsE
        ];
        $play['startTime'] = strtotime($play['startTime']);
        $play['endTime'] = strtotime($play['endTime']);
        return response()->json([
            'status'    => 1,
            'data'      => [
                'contents' => $contents,
                'play'     => $play
            ]
        ]);
    }
}