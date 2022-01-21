<?php

namespace App\Http\Controllers;

use App\Models\CategoryManage;
use App\Models\User;

use App\Models\Wallpapers;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;




class WallpapersController extends Controller
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
        $categories = CategoryManage::where('id', '<>', 1)->get();
        return view('content.wallpaper.index', [
            'pageConfigs' => $pageConfigs,
            'users'=>$users,
            'roles'=>$roles,
            'categories' => $categories
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



        $columnIndex = $columnIndex_arr ?  $columnIndex_arr[0]['column'] : '2'; // Column index
        $columnName = $columnName_arr ?  $columnName_arr[$columnIndex]['data'] : 'name'; // Column name
        $columnSortOrder = $order_arr?  $order_arr[0]['dir'] :'asc'; // asc or desc
        $searchValue = $search_arr ? $search_arr['value'] :''; // Search value
        if(isset($request->category)){
            $searchValue = $request->category;
        }
        $totalRecords = Wallpapers::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Wallpapers::with('category')->select('count(*) as allcount')
            ->leftJoin('tbl_category_has_wallpaper', 'tbl_category_has_wallpaper.wallpaper_id', '=', 'wallpapers.id')
            ->leftJoin('tbl_category_manages', 'tbl_category_manages.id', '=', 'tbl_category_has_wallpaper.category_id')
            ->where('name', 'like', '%' . $searchValue . '%')
            ->orWhere('tbl_category_manages.category_name', 'like', '%' . $searchValue . '%')
            ->count();


        // Get records, also we have included search filter as well
        $records = Wallpapers::with('category')->orderBy($columnName, $columnSortOrder)
            ->leftJoin('tbl_category_has_wallpaper', 'tbl_category_has_wallpaper.wallpaper_id', '=', 'wallpapers.id')
            ->leftJoin('tbl_category_manages', 'tbl_category_manages.id', '=', 'tbl_category_has_wallpaper.category_id')
            ->where('name', 'like', '%' . $searchValue . '%')
            ->orWhere('tbl_category_manages.category_name', 'like', '%' . $searchValue . '%')
            ->select('wallpapers.*','tbl_category_manages.category_name')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $key => $record) {
            $cate_name = [];
            foreach ($record->category as $category){
                $cate_name =$category->category_name;
            }
            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "thumbnail_image" => $record->thumbnail_image,
                "view_count" => $record->view_count,
                "like_count" => $record->like_count,
                "tbl_category_manages.category_name" => $cate_name,
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
        Log::error($request->all());
        if($request->file){
            $rules = [
                'file' => 'max:20000|mimes:jpeg,jpg,png,gif',
                'select_category' => 'required'
            ];
            $message = [
                'file.mimes'=>'Định dạng File',
                'file.max'=>'Dung lượng File',
                'select_category.required'=>'Chọn Category',
            ];
            $error = Validator::make($request->all(),$rules, $message );
            if($error->fails()){
                return response()->json(['errors'=> $error->errors()->all()]);
            }
            $file = $request->file;
            $wallpaper= new Wallpapers();
            $filenameWithExt=$file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $nameImage =  preg_replace('/[^A-Za-z0-9\-\']/', '_', $filename);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $nameImage.'_'.time().'.'.$extension;
            $now = new \DateTime('now'); //Datetime
            $monthNum = $now->format('m');
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F'); // Month
            $year = $now->format('Y'); // Year
            $monthYear = $monthName.$year;

            $path_origin    =  storage_path('app/public/wallpapers/download/'.$monthYear.'/');
            $path_detail    =  storage_path('app/public/wallpapers/detail/'.$monthYear.'/');
            $path_thumbnail =  storage_path('app/public/wallpapers/thumbnail/'.$monthYear.'/');

            if (!file_exists($path_detail)) {
                mkdir($path_detail, 0777, true);
            }
            if (!file_exists($path_thumbnail)) {
                mkdir($path_thumbnail, 0777, true);
            }

            if (!file_exists($path_origin)) {
                mkdir($path_origin, 0777, true);
            }

            $img = Image::make($file);
            $origin_image = $img->save($path_origin.$fileNameToStore);

            $detail_image = $img->resize(720, 1280,function ($constraint) {
                $constraint->aspectRatio();
            })->save($path_detail.$fileNameToStore);

            $thumbnail_image = $img->resize(360, 640,function ($constraint) {
                $constraint->aspectRatio();
            })->save($path_thumbnail.$fileNameToStore);

            $path_origin =  $monthYear.'/'.$fileNameToStore;
            $path_detail =  $monthYear.'/'.$fileNameToStore;
            $path_thumbnail =  $monthYear.'/'.$fileNameToStore;
            $wallpaper->name = $filename;
            $wallpaper->thumbnail_image = $path_thumbnail;
            $wallpaper->image = $path_detail;
            $wallpaper->origin_image = $path_origin;

            $wallpaper->view_count = rand(500,1000);
            $wallpaper->like_count = rand(500,1000);
            $wallpaper->feature = 0;
            $wallpaper->save();
            $wallpaper->category()->attach($request->select_category);
            return response()->json(['success'=>'Thành công']);

        }
    }
    public function update(Request $request){
//        dd($request->all());
        $id = $request->id;
        $rules = [
            'wallpaper_name' =>'required|unique:wallpapers,name,'.$id.',id',
            'image_thumbnail' => 'mimes:jpg',
            'image_detail' => 'mimes:jpg',
            'image_download' => 'mimes:jpg'
        ];
        $message = [
            'wallpaper_name.unique'=>'Tên đã tồn tại',
            'wallpaper_name.required'=>'Tên Category không để trống',
            'image_thumbnail.mimes'=>'Định dạng Thumbnail: JPG',
            'image_detail.mimes'=>'Định dạng Detail: JPG',
            'image_download.mimes'=>'Định dạng Download: JPG',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = Wallpapers::find($id);
        $data->name = $request->wallpaper_name;
        $data->view_count = $request->wallpaper_viewCount;
        $data->like_count = $request->wallpaper_likeCount;
        if($request->feature){
            $data->feature  = 1;
        }else{
            $data->feature  = 0;
        }
        if($request->image_thumbnail){
            $path_thumbnailRemove =   storage_path('app/public/wallpapers/thumbnail/').$data->thumbnail_image;
            if(file_exists($path_thumbnailRemove)){
                unlink($path_thumbnailRemove);
            }
            $image_thumbnail= $request->image_thumbnail;
            $filenameWithExt=$image_thumbnail->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $nameImage =  preg_replace('/[^A-Za-z0-9\-\']/', '_', $filename);
            $extension = $image_thumbnail->getClientOriginalExtension();
            $fileNameToStore = $nameImage.'_'.time().'.'.$extension;
            $now = new \DateTime('now'); //Datetime
            $monthNum = $now->format('m');
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F'); // Month
            $year = $now->format('Y'); // Year
            $monthYear = $monthName.$year;
            $path_thumbnail =  storage_path('app/public/wallpapers/thumbnail/'.$monthYear.'/');
            if (!file_exists($path_thumbnail)) {
                mkdir($path_thumbnail, 0777, true);
            }
            $img = Image::make($image_thumbnail);
            $thumbnail_image = $img->resize(360, 640,function ($constraint) {
                $constraint->aspectRatio();
            })->save($path_thumbnail.$fileNameToStore);
            $path_thumbnail =  $monthYear.'/'.$fileNameToStore;
            $data->thumbnail_image = $path_thumbnail;


        }
        if($request->image_detail){
            $path_detailRemove =   storage_path('app/public/wallpapers/thumbnail/').$data->image;
            if(file_exists($path_detailRemove)){
                unlink($path_detailRemove);
            }
            $image_detail= $request->image_detail;
            $filenameWithExt=$image_detail->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $nameImage =  preg_replace('/[^A-Za-z0-9\-\']/', '_', $filename);
            $extension = $image_detail->getClientOriginalExtension();
            $fileNameToStore = $nameImage.'_'.time().'.'.$extension;
            $now = new \DateTime('now'); //Datetime
            $monthNum = $now->format('m');
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F'); // Month
            $year = $now->format('Y'); // Year
            $monthYear = $monthName.$year;
            $path_detail    =  storage_path('app/public/wallpapers/detail/'.$monthYear.'/');
            if (!file_exists($path_detail)) {
                mkdir($path_detail, 0777, true);
            }
            $img = Image::make($image_detail);
            $detail_image = $img->resize(720, 1280,function ($constraint) {
                $constraint->aspectRatio();
            })->save($path_detail.$fileNameToStore);

            $path_detail =  $monthYear.'/'.$fileNameToStore;
            $data->image = $path_detail;

        }
        if($request->image_download){
            $path_originRemove =   storage_path('app/public/wallpapers/thumbnail/').$data->origin_image;
            if(file_exists($path_originRemove)){
                unlink($path_originRemove);
            }
            $image_download= $request->image_download;
            $filenameWithExt=$image_download->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $nameImage =  preg_replace('/[^A-Za-z0-9\-\']/', '_', $filename);
            $extension = $image_download->getClientOriginalExtension();
            $fileNameToStore = $nameImage.'_'.time().'.'.$extension;
            $now = new \DateTime('now'); //Datetime
            $monthNum = $now->format('m');
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F'); // Month
            $year = $now->format('Y'); // Year
            $monthYear = $monthName.$year;
            $path_origin    =  storage_path('app/public/wallpapers/download/'.$monthYear.'/');
            if (!file_exists($path_origin)) {
                mkdir($path_origin, 0777, true);
            }
            $img = Image::make($image_download);
            $origin_image = $img->save($path_origin.$fileNameToStore);
            $path_origin =  $monthYear.'/'.$fileNameToStore;
            $data->origin_image = $path_origin;
        }
        $data->category()->sync($request->select_category);
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function edit($id)
    {
        $data = Wallpapers::with('category')->where('id',$id)->first();
        return response()->json($data);
    }
    public function delete($id)
    {
        $wallpaper = Wallpapers::find($id);
        $path_thumbnail =   storage_path('app/public/wallpapers/thumbnail/').$wallpaper->thumbnail_image;
        $path_detail    =   storage_path('app/public/wallpapers/detail/').$wallpaper->image;
        $path_origin    =   storage_path('app/public/wallpapers/download/').$wallpaper->origin_image;
        try {
            if(file_exists($path_thumbnail)){
                unlink($path_thumbnail);
            }
            if(file_exists($path_detail)){
                unlink($path_detail);
            }
            if(file_exists($path_origin)){
                unlink($path_origin);
            }
        }catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
        $wallpaper->category()->detach();
        $wallpaper->delete();

        return response()->json(['success'=>'Xóa thành công.']);
    }

    public function deleteSelect(Request $request)
    {
        $id = $request->id;
        $wallpapers = Wallpapers::whereIn('id',$id)->get();
        foreach ( $wallpapers as $wallpaper){
            $path_thumbnail =   storage_path('app/public/wallpapers/thumbnail/').$wallpaper->thumbnail_image;
            $path_detail    =   storage_path('app/public/wallpapers/detail/').$wallpaper->image;
            $path_origin    =   storage_path('app/public/wallpapers/download/').$wallpaper->origin_image;
//            dd($path_thumbnail);
            try {
                if(file_exists($path_thumbnail)){
                    unlink($path_thumbnail);
                }
                if(file_exists($path_detail)){
                    unlink($path_detail);
                }
                if(file_exists($path_origin)){
                    unlink($path_origin);
                }
            }catch (Exception $ex) {
                Log::error($ex->getMessage());
            }
            $wallpaper->category()->detach();
            $wallpaper->delete();
        }
        return response()->json(['success'=>'Xóa thành công.']);
    }

}
