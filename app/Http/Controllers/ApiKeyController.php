<?php

namespace App\Http\Controllers;


use App\Models\ApiKeys;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class ApiKeyController extends Controller
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
        return view('content.apikey.index', [
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
        $totalRecords = ApiKeys::select('count(*) as allcount')->count();
        $totalRecordswithFilter = ApiKeys::select('count(*) as allcount')
            ->where('name', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = ApiKeys::orderBy($columnName, $columnSortOrder)
            ->where('name', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $key => $record) {
//            dd($record);
//            $image_count = '<a href="/wallpaper?category='.$record->category_name.'"> <span>'.$record->wallpaper->count().'</span></a>';
//            $image_count = '<a data-id="'.$record->category_name.'" class="category_name_search"> <span>'.$record->wallpaper->count().'</span></a>';

            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "key" => $record->key,
                "active" => $record->active,
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
            'apikey_name' => 'required|unique:api_keys,name',
        ];
        $message = [
            'apikey_name.unique'=>'Tên Api Key đã tồn tại',
            'apikey_name.required'=>'Tên Api Key không để trống',
        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        if($request->apikey){
            $data = new ApiKeys();
            $data->name  = Str::slug($request->apikey_name);
            $data->key = $request->apikey;
            $data->save();
        }else{
            $data =  Artisan::call('apikey:generate '.Str::slug($request->apikey_name));
        }
        return response()->json([
            'success'=>'Thêm mới thành công'
        ]);
    }
    public function update(Request $request){

        $id = $request->id;
        $rules = [
            'apikey_name' =>'required|unique:api_keys,name,'.$id.',id',
        ];
        $message = [
            'apikey_name.unique'=>'Tên Api Key đã tồn tại',
            'apikey_name.required'=>'Tên Api Key không để trống',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = ApiKeys::find($id);
        $data->name  = Str::slug($request->apikey_name);
        $data->key = $request->apikey;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function edit($id)
    {
        $data = ApiKeys::find($id);
        return response()->json($data);
    }
    public function delete($id)
    {
        $category = ApiKeys::find($id);
        $category->delete();
        return response()->json(['success'=>'Xóa thành công.']);

    }
}
