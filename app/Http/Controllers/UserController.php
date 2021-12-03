<?php

namespace App\Http\Controllers;



//use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;


class UserController extends Controller
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
        return view('content.user.index', ['pageConfigs' => $pageConfigs,'users'=>$users,'roles'=>$roles]);
    }
//    public function callAction($method, $parameters)
//    {
//        return parent::callAction($method, array_values($parameters));
//    }
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
        $totalRecords = User::select('count(*) as allcount')->count();
        $totalRecordswithFilter = User::select('count(*) as allcount')
            ->where('name', 'like', '%' . $searchValue . '%')
            ->orwhere('email', 'like', '%' . $searchValue . '%')
            ->count();


        // Get records, also we have included search filter as well
        $records = User::orderBy($columnName, $columnSortOrder)
            ->where('name', 'like', '%' . $searchValue . '%')
            ->orwhere('email', 'like', '%' . $searchValue . '%')
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
                "email" => $record->email,
                "roles" => (count($record->getRoleNames())> 0) ? $record->getRoleNames()[0] : 'Guest' ,
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


        $rules = [
            'user_name' => 'required|unique:users,name',
            'user_email' => 'required|unique:users,email',

        ];
        $message = [
            'user_email.required' => 'Email không được để trống',
            'user_name.required' => 'Tên không được để trống',
            'user_name.unique'=>'Tên người dùng đã tồn tại',
            'user_email.unique'=>'Email đã tồn tại',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data =new User();
        $data['name'] = $request->user_name;
        $data['email'] = $request->user_email;
        if($request->user_password){
            $data['password'] =  bcrypt($request->user_password);
        }else{
            $data['password'] =  bcrypt(123456789);
        }
        $data->assignRole($request->user_role);
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function update(Request $request){
        $id = $request->id;
        $rules = [
            'user_name' =>'unique:users,name,'.$id.',id',
            'user_email' =>'email|unique:users,email,'.$id.',id',

        ];
        $message = [
            'user_name.unique'=>'Tên đã tồn tại',
            'user_email.unique'=>'Email đã đã được đăng ký',
            'user_email.email'=>'Phải là Email',

        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }


        $data = User::find($id);
        $data->name = $request->user_name;
        $data->email = $request->user_email;
        if($request->user_password){
            $data->password =  bcrypt($request->user_password);
        }
        $data->syncRoles($request->user_role);
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function edit($id)
    {
        $user = $this->user->find($id);
        $role = (count($user->getRoleNames())> 0 ) ? $user->getRoleNames()[0] : 'User';
        return response()->json([$user,$role]);
    }
    public function delete($id)
    {
        User::where('id',$id)->delete();
        return response()->json(['success'=>'Xóa người dùng.']);
    }
}
