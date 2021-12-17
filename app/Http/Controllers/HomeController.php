<?php

namespace App\Http\Controllers;

use App\Models\FileManage;
use App\Models\Tags;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use ImageOrientationFix\ImageOrientationFixer;
use Imagick;
use Monolog\Logger;
use PHPExiftool\Reader;
use PHPExiftool\Driver\Value\ValueInterface;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function home()
    {
        $data_users = $this->topUpload();
        $data_tags = $this->tagsCount();
        $file = FileManage::all();

        return view('content.home', [
            'data_users' => $data_users,
            'data_tags'=>$data_tags,
            'file' =>$file
        ]);
    }

    public function topUpload($limit=3)
    {
        if(request()->time){
            $time =request()->time;
        }else{
            $time = 'inMonth';
        }
        $records = User::get();
        $data_arr = array();
        foreach ($records as $record){
            if ($record->avatar) {
                $output = '<img src="data:image/png;base64,' . $record->avatar . '" alt="Avatar" height="40" width="40">';
            } else {
                // For Avatar badge
                $stateNum = rand(0,6);
                $states = ['danger', 'secondary', 'warning', 'info', 'dark', 'primary', 'success'];
                $state = $states[$stateNum];
                $name = $record->name;
                $initials = strtoupper(implode('', array_map(function($v) { return $v[0]; },array_filter(array_map('trim',explode(' ', $name))))));
                $output = '<span class="avatar-content" style="width: 40px; height: 40px">' . $initials . '</span>';
            }
            $colorClass = $record->avatar === '' ? ' bg-light-' . $state .' ' : '';
            $row_output ='<div class="d-flex justify-content-left align-items-center">' .
                            '<div class="avatar-wrapper">' .
                            '<div class="avatar ' .
                            $colorClass .
                            ' me-1">' .
                            $output .
                            '</div>' .
                            '</div>' .
                            '</div>';
            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "email" => $record->email,
                "avatar" => $row_output,
                "countUpload" => $this->uploadOfUsers($record->id,$time),
                "diff" => $this->uploadOfUsers($record->id,$time) - $this->uploadOfUsers($record->id,'lastMonth'),
            );
        }
        $columns = array_column($data_arr, 'countUpload');
        array_multisort($columns, SORT_DESC, $data_arr);
        $data_arr = array_slice($data_arr, 0, $limit);
        return $data_arr;
    }

    public function uploadOfUsers(int $id, $time)
    {
        if($time == 'inMonth'){
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now();
        }elseif ($time == 'lastMonth'){
            $start = new Carbon('first day of last month');
            $end = new Carbon('last day of last month');
        }
        $countUploads = FileManage::where('user_id', $id)
            ->whereBetween('created_at',[$start,$end])
            ->get()
            ->count();
        return $countUploads;
    }

    public function tagsCount($limit=5){
        $records = Tags::all();
        $data_arr = array();
        foreach ($records as $record){
            $countRecord = FileManage::select('count(*) as allcount')
                ->where('tags', 'like', '%' . $record->tags_name . '%')
                ->count();

            $data_arr[] = array(
                "id" => $record->id,
                "tags_name" => $record->tags_name,
                "tags_count" => $countRecord,
            );
            Tags::updateOrCreate(
                [
                    'tags_name'=>$record->tags_name,
                ],
                [
                    'tags_count'=>$countRecord,
                ]
            );
        }
        $columns = array_column($data_arr, 'tags_count');
        array_multisort($columns, SORT_DESC, $data_arr);
        $data_arr = array_slice($data_arr, 0, $limit);
        return $data_arr;
    }



}
