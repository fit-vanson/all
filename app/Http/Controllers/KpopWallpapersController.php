<?php

namespace App\Http\Controllers;

use App\Models\KpopWallpapers;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class KpopWallpapersController extends Controller
{
    private $user;
    public $role;
    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
    }
    public function index(Request $request)
    {

        $user = Auth::user();
        $pageConfigs = ['pageHeader' => false];
        if($user->hasRole('Admin')){
            $users = $this->user->all();
            $roles = $this->role->all();
            return view('content.user.index', ['pageConfigs' => $pageConfigs,'users'=>$users,'roles'=>$roles]);
        }else{
            $user = Auth::user();
            $role = Auth::user()->getRoleNames()[0];
            return view('content.user.info', ['pageConfigs' => $pageConfigs,'user'=>$user,'role'=>$role]);
        }
    }


    public function category(){
        $pageConfigs = ['pageHeader' => false];
        $users = $this->user->all();
        $roles = $this->role->all();
        return view('content.kpopwallpapers.index', ['pageConfigs' => $pageConfigs,'users'=>$users,'roles'=>$roles]);

    }
    public function getIndex(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');


        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value


        // Total records
        $totalRecords = KpopWallpapers::select('count(*) as allcount')->count();
        $totalRecordswithFilter = KpopWallpapers::select('count(*) as allcount')
            ->where('name', 'like', '%' . $searchValue . '%')
            ->count();


        // Get records, also we have included search filter as well
        $records = KpopWallpapers::orderBy($columnName, $columnSortOrder)
            ->where('name', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
//            $btn = '<a href="javascript:void(0)" onclick="editUser('.$value->id.')" class="btn btn-warning">Edit</i></a>';
//            $btn = "<a href='user/get_add_user/$record->id' class='btn btn-warning'>Edit</i></a>";
            $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Edit" class="btn btn-warning editUser">Edit</i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-danger deleteUser">Del</i></a>';


            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "image" => $record->image,
                "view_count" => $record->view_count,
                "checked_ip" => $record->checked_ip,
            );
        }


        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        echo json_encode($response);
    }
}
