<?php

namespace App\Http\Controllers;

use App\Models\CategoryHasWallpaper;
use App\Models\CategoryManage;
use App\Models\User;


use App\Models\Wallpapers;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;
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
//        $a = $this->convert();
//        dd($a);
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
//            ->leftJoin('tbl_category_has_wallpaper', 'tbl_category_has_wallpaper.wallpaper_id', '=', 'wallpapers.id')
            ->leftJoin('tbl_category_manages', 'tbl_category_manages.id', '=', 'wallpapers.cate_id')
            ->where('name', 'like', '%' . $searchValue . '%')
            ->orWhere('tbl_category_manages.category_name',  $searchValue )
            ->count();



        // Get records, also we have included search filter as well
        $records = Wallpapers::with('category')
            ->orderBy($columnName, $columnSortOrder)
//            ->leftJoin('tbl_category_has_wallpaper', 'tbl_category_has_wallpaper.wallpaper_id', '=', 'wallpapers.id')
            ->leftJoin('tbl_category_manages', 'tbl_category_manages.id', '=', 'wallpapers.cate_id')
            ->where('name', 'like', '%' . $searchValue . '%')
            ->orWhere('tbl_category_manages.category_name',  $searchValue )
            ->select('wallpapers.*','tbl_category_manages.category_name')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach ($records as $key => $record) {
//            $cate_name = [];
//            foreach ($record->category as $category){
//                $cate_name =$category->category_name;
//            }
            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "thumbnail_image" => $record->thumbnail_image,
                "view_count" => $record->view_count,
                "like_count" => $record->like_count,
                "image_extension" => $record->image_extension,
//                "tbl_category_manages.category_name" => $cate_name,
                "category_name" => $record->category_name,
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

        if($request->file){
            $rules = [
                'file.*' => 'max:20000|mimes:jpeg,jpg,png,gif',
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
            if($img->mime() == "image/gif"){
                copy($file->getRealPath(), $path_origin.$fileNameToStore);
                copy($file->getRealPath(), $path_detail.$fileNameToStore);
                copy($file->getRealPath(), $path_thumbnail.$fileNameToStore);
            }else{
                $origin_image = $img->save($path_origin.$fileNameToStore);
                $detail_image = $img->resize(720, 1280,function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path_detail.$fileNameToStore);

                $thumbnail_image = $img->resize(360, 640,function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path_thumbnail.$fileNameToStore);
            }
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
            $wallpaper->image_extension = $_FILES['file']['type'];
            $wallpaper->cate_id = $request->select_category;

            $wallpaper->save();
//            $wallpaper->category()->attach($request->select_category);
            return response()->json(['success'=>'Thành công']);

        }
    }
    public function update(Request $request){
        $id = $request->id;
        $rules = [
//            'wallpaper_name' =>'required|unique:wallpapers,name,'.$id.',id',
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
        $data->image_extension = $request->wallpaper_image_extension;
//        $data->cate_id = $request->select_category;
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
//        $wallpaper->category()->detach();
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
//            $wallpaper->category()->detach();
            $wallpaper->delete();
        }
        return response()->json(['success'=>'Xóa thành công.']);
    }


    public function convert(){
        $cateHasWalls = CategoryHasWallpaper::all();
        foreach ($cateHasWalls as $cateHasWall){
            Wallpapers::updateOrCreate(
                [
                    'id' =>$cateHasWall->wallpaper_id
                ],
                [
                    'cate_id' =>$cateHasWall->category_id
                ]);
        }


    }

//
//    public function compare(){
//
//        ini_set('memory_limit', '2000M');
//        ini_set('max_execution_time', 300);
////        $wallpapersHash_null = Wallpapers::where('hash_file',null)->get();
//        $wallpapers = Wallpapers::all()->toArray();
//        $hashImg = [];
//        $duplicate = [];
//        $compares = [];
////        $hasher = new ImageHash(new DifferenceHash());
////        if(!$wallpapersHash_null->isEmpty()){
////            $this->insertHash_file($wallpapersHash_null,$hasher);
////        }
//
//
//        foreach ($wallpapers as $wallpaper) {
//            $path = storage_path('app/public/wallpapers/thumbnail/'.$wallpaper['thumbnail_image']);
////            $hashImg[$wallpaper['id']] = hash_file('md5', $path);
//            $hashImg[$wallpaper['id']] = $wallpaper['hash_file'];
////            $hashImg[$wallpaper->id] = $hasher->hash($path)->toBits();
////            Wallpapers::updateorCreate(
////                ['id' => $wallpaper->id],
////                ['hash_file' =>$hasher->hash($path)->toBits()  ]
////            );
////            $hashImg[] = [
////                'id' => $wallpaper->id,
////                'id' => $wallpaper->id,
////                'hash_file' => $hasher->hash($path)->toBits()
////            ];
////            $hashImg['hash_file'][] = $hasher->hash($path)->toBits();
//        }
//
////        dd($hashImg);
////        Wallpapers::insert($hashImg);
//
//
////        $distance = $hasher->distance($hash1, $hash2);
//
////        foreach ($wallpapers as $path1 => $item) {
//////            echo 'Compare ' . $path1 . PHP_EOL;
////            foreach ($wallpapers as $path2 => $match) {
////                if ($path1 == $path2) {
////                    continue;
////                }
////
////                $distance = count(array_diff_assoc(str_split($item['hash_file']), str_split($match['hash_file'])));
////
//////                if ($item == $match) {
////                if ($distance <= 0 ) {
//////                    dd($item,$match,$path1);
////                    if (!isset($duplicate[$item['id']])) {
////                        $duplicate[$item['id']] = [];
////                    }
////
////
////                    if (!in_array($path1, $duplicate[$item['id']])) {
////                        $duplicate[$item['id']][] = $path1;
////                    }
////
////                    if (!in_array($path2, $duplicate[$item['id']])) {
////                        $duplicate[$item['id']][] = $path2;
////                    }
////                }
////            }
////        }
//
////        dd($duplicate);
//
//
//
//        foreach ($hashImg as $path1 => $item) {
////        foreach ($wallpapers as $path1 => $item) {
//
////            dd($wallpapers);
//
//
////            echo 'Compare ' . $path1 . PHP_EOL;
//            foreach ($hashImg as $path2 => $match) {
////            foreach ($wallpapers as $path2 => $match) {
//                if ($path1 == $path2) {
////                if ($item['id'] == $match['id']) {
//                    continue;
//                }
////                dd($item, $match);
//                $distance = count(array_diff_assoc(str_split($item), str_split($match)));
////                $distance = 1;
////                dd($distance);
//
//
////                if ($item == $match) {
////                if ($item['hash_file'] == $match['hash_file']) {
//                if ($distance <= 1 ) {
//
//                    if (!isset($duplicate[$item])) {
//                        $duplicate[$item] = [];
//                    }
//
//                    if (!in_array($path1, $duplicate[$item])) {
////                    if (!in_array($item['id'], $duplicate[$item['hash_file']])) {
//                        $duplicate[$item][] = $path1;
////                        $duplicate[$item['hash_file']][] = $item['id'];
//                    }
//
//                    if (!in_array($path2, $duplicate[$item])) {
////                    if (!in_array($match['id'], $duplicate[$item['hash_file']])) {
//                        $duplicate[$item][] = $path2;
////                        $duplicate[$item['hash_file']][] = $match['id'];
//                    }
//                }
//            }
//        }
//
////        dd($duplicate);
//        if (!empty($duplicate)) {
//            foreach ($duplicate as $key =>$item) {
//                foreach ($item as $path) {
//                    $compares[] = Wallpapers::with('category')->where('id',$path)->first()->toArray();
//                }
//            }
//        }
//
////        dd($compares,$duplicate);
//
//
//        $pageConfigs = [
//            'pageClass' => 'ecommerce-application',
//        ];
//
//
//        return view('content.wallpaper.compare', [
//            'pageConfigs' => $pageConfigs,
//            'compares' => $compares
//        ]);
//    }
//
//    public function insertData(){
//        $wallpapers = Wallpapers::all();
//        foreach ($wallpapers as $item){
//            $path = storage_path('app/public/wallpapers/download/'.$item->thumbnail_image);
//            dd($path);
//            Wallpapers::updateorCreate(
//                ['id' => $item->id],
//                ['hash_file' =>$hasher->hash($path)->toBits()  ]
//            );
//        }
//        return 1;
//    }

}
