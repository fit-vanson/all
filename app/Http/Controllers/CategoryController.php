<?php

namespace App\Http\Controllers;


use App\Models\CategoryManage;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;

class CategoryController extends Controller
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
        $categoriesReal = CategoryManage::where('checked_ip',0)->count();
        $categoriesPhace = CategoryManage::where('checked_ip',1)->count();
        return view('content.category.index', [
            'pageConfigs' => $pageConfigs,
            'users'=>$users,
            'roles'=>$roles,
            'categoriesReal' => $categoriesReal,
            'categoriesPhace' => $categoriesPhace,

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
        $totalRecords = CategoryManage::select('count(*) as allcount')->count();
        $totalRecordswithFilter = CategoryManage::select('count(*) as allcount')
            ->where('id', '<>', 1)
            ->where('category_name', 'like', '%' . $searchValue . '%')
            ->count();


        // Get records, also we have included search filter as well
        $records = CategoryManage::with('wallpaper','site')
            ->withCount('wallpaper')
            ->orderBy($columnName, $columnSortOrder)
            ->where('category_name', 'like', '%' . $searchValue . '%')
            ->where('id', '<>', 1)
            ->skip($start)
            ->take($rowperpage)
            ->get();
//        dd($records);


        $data_arr = array();
        foreach ($records as $key => $record) {
            $image_count = '<a href="/admin/wallpaper?category='.$record->category_name.'"> <span>'.$record->wallpaper_count.'</span></a>';
            $data_arr[] = array(
                "id" => $record->id,
                "category_name" => $record->category_name,
                "image" => $record->image,
                "view_count" => $record->view_count,
                "wallpaper_count" => $image_count,
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
    public function create(Request $request)
    {
        $rules = [
            'category_name' => 'required|unique:tbl_category_manages,category_name',
            'image' => 'required',
        ];
        $message = [
            'category_name.unique'=>'T??n Category ???? t???n t???i',
            'category_name.required'=>'T??n Category kh??ng ????? tr???ng',
            'image.required'=>'???nh kh??ng ????? tr???ng',
        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = new CategoryManage();
        $data['category_name'] = $request->category_name;
        $data['order'] = $request->category_order;
        $data['view_count'] = $request->view_count;
        if($request->view_count){
            $data['view_count'] = $request->view_count;
        }else{
            $data['view_count'] = rand(500,2000);
        }
        if($request->checked_ip){
            $data['checked_ip'] = 0;
        }
        if($request->image){
            $file = $request->image;
            $filenameWithExt=$file->getClientOriginalName();
            $filename = Str::slug($request->category_name);

            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $now = new \DateTime('now'); //Datetime
            $monthNum = $now->format('m');
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F'); // Month
            $year = $now->format('Y'); // Year
            $monthYear = $monthName.$year;
            $path_image    =  storage_path('app/public/categories/'.$monthYear.'/');
            if (!file_exists($path_image)) {
                mkdir($path_image, 0777, true);
            }
            $img = Image::make($file);
            $image = $img->save($path_image.$fileNameToStore);
            $path_image =  $monthYear.'/'.$fileNameToStore;
            $data['image'] = $path_image;
        }
        $data->save();
        $allCategory = CategoryManage::where('id', '<>', 1)->latest()->get();
        return response()->json([
            'success'=>'Th??m m???i th??nh c??ng',
            'all_category' => $allCategory
            ]);
    }
    public function update(Request $request){
        $id = $request->id;
        $rules = [
            'category_name' =>'required|unique:tbl_category_manages,category_name,'.$id.',id'

        ];
        $message = [
            'category_name.unique'=>'T??n ???? t???n t???i',
            'category_name.required'=>'T??n Category kh??ng ????? tr???ng',
            'image.required'=>'???nh kh??ng ????? tr???ng',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = CategoryManage::find($id);
        $data->category_name = $request->category_name;
        $data->order = $request->category_order;
        $data->view_count = $request->view_count;
        if($request->checked_ip){
            $data->checked_ip  = 0;
        }else{
            $data->checked_ip  = 1;
        }

        if($request->image){

            $path_Remove =   storage_path('app/public/categories/').$data->image;
            if(file_exists($path_Remove)){
                unlink($path_Remove);
            }
            $file = $request->image;
            $filenameWithExt=$file->getClientOriginalName();
            $filename = Str::slug($request->category_name);

            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $now = new \DateTime('now'); //Datetime
            $monthNum = $now->format('m');
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F'); // Month
            $year = $now->format('Y'); // Year
            $monthYear = $monthName.$year;
            $path_image    =  storage_path('app/public/categories/'.$monthYear.'/');
            if (!file_exists($path_image)) {
                mkdir($path_image, 0777, true);
            }
            $img = Image::make($file);
            $image = $img->save($path_image.$fileNameToStore);
            $path_image =  $monthYear.'/'.$fileNameToStore;
            $data->image = $path_image;
        }
        $data->save();
        return response()->json(['success'=>'C???p nh???t th??nh c??ng']);
    }
    public function edit($id)
    {
        $user = Auth::user();
        if($id == 1){
            if($user->hasRole('Admin')){
                $data = CategoryManage::find($id);
                return response()->json([
                    'success'=>'Th??m m???i th??nh c??ng',
                    'data' => $data
                ]);
            }else{
                return response()->json([
                    'error'=>'User kh??ng c?? quy???n',
                ]);
            }
        }else{
            $data = CategoryManage::find($id);
            return response()->json([
                'success'=>'Th??m m???i th??nh c??ng',
                'data' => $data
            ]);
        }

    }
    public function delete($id)
    {
        if($id == 1){
            return response()->json(['error'=>'Kh??ng th??? xo??.']);
        }else{
            $category = CategoryManage::find($id);
            $category->delete();
            return response()->json(['success'=>'X??a th??nh c??ng.']);
        }
    }


}
