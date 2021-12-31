<?php

namespace App\Http\Controllers;

use App\Models\ApiKeys;
use App\Models\BlockIP;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class BlockIPController extends Controller
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
        $pageConfigs = ['pageHeader' => false];
        $users = $this->user->all();
        $roles = $this->role->all();
//        $categories = CategoryManage::all();
        return view('content.blockip.index', [
            'pageConfigs' => $pageConfigs,
            'users'=>$users,
            'roles'=>$roles,
//            'categories' => $categories
        ]);

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
        $totalRecords = BlockIP::select('count(*) as allcount')->count();
        $totalRecordswithFilter = BlockIP::select('count(*) as allcount')
            ->where('ip_address', 'like', '%' . $searchValue . '%')
            ->count();


        // Get records, also we have included search filter as well
        $records = BlockIP::orderBy($columnName, $columnSortOrder)
            ->where('ip_address', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();
//        dd($records->wallpaper_count);
        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
                "id" => $record->id,
                "ip_address" => $record->ip_address,
                "created_at" => $record->created_at,
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
    public function create(Request $request)
    {
//        dd($request->all());
        $rules = [
            'ip_address' => 'unique:block_i_p_s,ip_address',
        ];
        $message = [
            'ip_address.unique'=>'Đã tồn tại',
        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = new BlockIP();
        $data['ip_address'] = $request->ip_address;
        $data->save();
        $allBlockIps = BlockIP::latest()->get();
        return response()->json([
            'success'=>'Thêm mới thành công',
            'allBlockIps' => $allBlockIps
        ]);
    }
    public function update(Request $request){

        $id = $request->id;
        $rules = [
            'ip_address' =>'required|unique:block_i_p_s,ip_address,'.$id.',id',

        ];
        $message = [
            'ip_address.unique'=>'Đã tồn tại',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = BlockIP::find($id);
        $data->ip_address = $request->ip_address;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function edit($id)
    {
        $data = BlockIP::find($id);
        return response()->json($data);
    }
    public function delete($id)
    {
        $block_ip = BlockIP::find($id);
        $block_ip->sites()->detach();
        $block_ip->delete();
        return response()->json(['success'=>'Xóa thành công.']);

    }
}
