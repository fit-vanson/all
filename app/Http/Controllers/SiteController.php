<?php

namespace App\Http\Controllers;

use App\Models\ApiKeys;
use App\Models\BlockIP;
use App\Models\BlockIpsHasSite;
use App\Models\CategoryHasSite;
use App\Models\CategoryHasWallpaper;
use App\Models\CategoryManage;
use App\Models\FeatureImage;
use App\Models\SiteManage;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;

class SiteController extends Controller
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
        $categories = CategoryManage::where('id', '<>', 1)->get();;
        $apiKeys = ApiKeys::where('active',1)->get();
        return view('content.site.site-list', [
            'pageConfigs' => $pageConfigs,
            'users'=>$users,'roles'=>$roles,
            'categories' => $categories,
            'apiKeys' => $apiKeys,
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
        $totalRecords = SiteManage::select('count(*) as allcount')->count();
        $totalRecordswithFilter = SiteManage::select('count(*) as allcount')
            ->where('site_name', 'like', '%' . $searchValue . '%')
            ->count();


        // Get records, also we have included search filter as well
        $records = SiteManage::with('category', 'api_key')->orderBy($columnName, $columnSortOrder)
            ->where('site_name', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
//            dd($record);
            $cate_name = [];
            foreach ($record->category as $category){
                $cate_name[] =$category->category_name;
            }
            $data_arr[] = array(
                "id" => $record->id,
                "logo" => $record->logo,
                "site_name" => $record->site_name,
                "ad_switch" => $record->ad_switch,
                "category" => $cate_name,
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
            'site_name' => 'unique:tbl_site_manages,site_name',
            'image_logo' => 'required',
        ];
        $message = [
            'site_name.unique'=>'Tên Site đã tồn tại',
            'image_logo.required'=>'Vui lòng chọn Logo',

        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data =new SiteManage();
        $data['site_name'] = $request->site_name;
        $data['name_site'] = $request->name_site;

        $image = $request->image_logo;
        $filenameWithExt=$image->getClientOriginalName();
        $filename = Str::slug($request->site_name);
        $extension = $image->getClientOriginalExtension();
        $fileNameToStore = $filename.'_'.time().'.'.$extension;
        $now = new \DateTime('now'); //Datetime
        $monthNum = $now->format('m');
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // Month
        $year = $now->format('Y'); // Year
        $monthYear = $monthName.$year;
        $path_image    =  storage_path('app/public/sites/'.$monthYear.'/');
        if (!file_exists($path_image)) {
            mkdir($path_image, 0777, true);
        }
        $img = Image::make($image);
        $image = $img->save($path_image.$fileNameToStore);
        $path_image =  $monthYear.'/'.$fileNameToStore;
        $data['logo'] = $path_image;
        $data->save();
        $data->category()->attach($request->select_category);
        return response()->json(['success'=>'Thêm mới thành công']);
    }
    public function update(Request $request){
        $id = $request->id;
        $rules = [
            'site_name' =>'unique:tbl_site_manages,site_name,'.$id.',id',
        ];
        $message = [
            'site_name.unique'=>'Tên đã tồn tại',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = SiteManage::find($id);
        $data->site_name = $request->site_name;
        $data->name_site = $request->name_site;
        if( $request->image_logo){
            $path_Remove =   storage_path('app/public/sites/').$data->logo;
            if(file_exists($path_Remove)){
                unlink($path_Remove);
            }
            $image = $request->image_logo;
            $filenameWithExt=$image->getClientOriginalName();
            $filename = Str::slug($request->site_name);
            $extension = $image->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $now = new \DateTime('now'); //Datetime
            $monthNum = $now->format('m');
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F'); // Month
            $year = $now->format('Y'); // Year
            $monthYear = $monthName.$year;
            $path_image    =  storage_path('app/public/sites/'.$monthYear.'/');
            if (!file_exists($path_image)) {
                mkdir($path_image, 0777, true);
            }
            $img = Image::make($image);
            $image = $img->save($path_image.$fileNameToStore);
            $path_image =  $monthYear.'/'.$fileNameToStore;
            $data->logo = $path_image;
        }
        $data->category()->sync($request->select_category);
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function edit($id)
    {
//        $data = SiteManage::find($id);
        $data = SiteManage::with('category')->where('id',$id)->first();
        return response()->json($data);
    }
    public function delete($id)
    {
        $site = SiteManage::find($id);
        $site->category()->detach();
        $site->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }
    public function changeAds($id)
    {
        $data = SiteManage::find($id);
        if($data->ad_switch == 1){
            $data->ad_switch = 0;
            $data->save();
            return response()->json(['success'=>'Tắt Ads.']);
        }elseif ($data->ad_switch == 0){
            $data->ad_switch = 1;
            $data->save();
            return response()->json(['success'=>'Kích hoạt ADs.']);
        }


    }


    //===================================================
    public function site_index($id){
        $site = SiteManage::with('category')->where('site_name',$id)->first();
        $pageConfigs = [
            'pageHeader' => false,
        ];
        $users = $this->user->all();
        $roles = $this->role->all();
        $categories = CategoryManage::all();
        $blockIps = BlockIP::all();

        return view('content.site.site-view-categories', [
            'pageConfigs' => $pageConfigs,
            'users'=>$users,
            'roles'=>$roles,
            'categories' => $categories,
            'blockIps' => $blockIps,
            'site' =>$site
            ]);

    }
    public function site_getCategory(Request $request,$id){
        $site = SiteManage::where("site_name",$id)->first();
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



        $totalRecords = CategoryHasSite::where('site_id',$site->id)->select('count(*) as allcount')->count();

        $totalRecordswithFilter = CategoryHasSite::select('count(*) as allcount')
            ->where('site_id',$site->id)
//            ->where('site_name', 'like', '%' . $searchValue . '%')
            ->count();


        // Get records, also we have included search filter as well
        $records = CategoryHasSite::orderBy($columnName, $columnSortOrder)
            ->join('tbl_category_manages','tbl_category_has_site.category_id','=','tbl_category_manages.id')
            ->where('site_id',$site->id)
            ->where('tbl_category_manages.category_name', 'like', '%' . $searchValue . '%')
            ->select('tbl_category_has_site.*',
                'tbl_category_manages.id as id_cate',
                'tbl_category_manages.category_name',
                'tbl_category_manages.view_count',
                'tbl_category_manages.checked_ip',
                'tbl_category_manages.image as category_image')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            if($record->image){
                $image = $record->image;
            }else{
                $image = $record->category_image;
            }
            $data_arr[] = array(
                "id" => $record->id,
                "id_cate" => $record->id_cate,
                "category_name" => $record->category_name,
                "image" => $image,
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
    public function site_addCategory(Request $request,$id){
        $site = SiteManage::where('site_name',$id)->first();
        $site->category()->sync($request->select_category);
        $site->save();
        return response()->json(['success'=>'Thêm mới thành công']);
    }
    public function site_editAddCategory( $id){
        $site = SiteManage::with('category')->where('site_name',$id)->first();
        return response()->json($site);
    }
    public function site_updateCategory(Request $request){
        $id = $request->id;
        $data = CategoryHasSite::find($id);
        if($request->image){
            $path_Remove =   storage_path('app/public/categories/').$data->image;
            if(file_exists($path_Remove)){
                unlink($path_Remove);
            }
            $file = $request->image;
            $filenameWithExt=$file->getClientOriginalName();
            $filename = $data->site_id.'_'.$data->category_id;
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
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function site_editCategory(Request $request,$id,$id1){
        $site = CategoryHasSite::find($id1);
        $category = CategoryManage::find($site->category_id);
        return response()->json([$site,$category]);
    }

    //=======================================================

    public function site_getWallpaper(Request $request,$id){
//        dd($request->all(), $id);
        $site = SiteManage::with('category')->where("site_name",$id)->first();


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

        $totalRecords =$totalRecordswithFilter = 0;
//        dd($site->category);
        $id_cate = [];

        foreach ($site->category as $key => $record) {
            $id_cate []  = $record->id;
//            $totalRecords += CategoryHasWallpaper::where('category_id',$record->id)->select('count(*) as allcount')->count();
//            $totalRecordswithFilter += CategoryHasWallpaper::select('count(*) as allcount')
//                ->where('category_id',$record->id)
////            ->where('site_name', 'like', '%' . $searchValue . '%')
//                ->count();
//
            $records = CategoryHasWallpaper::whereIn('category_id',$id_cate)
                ->join('wallpapers','tbl_category_has_wallpaper.wallpaper_id','=','wallpapers.id')
                ->join('tbl_category_manages','tbl_category_has_wallpaper.category_id','=','tbl_category_manages.id')
//                ->orderBy($columnName, $columnSortOrder)
//                ->where('tbl_category_manages.category_name', 'like', '%' . $searchValue . '%')
                ->select('tbl_category_has_wallpaper.*',
                    'tbl_category_manages.id as id_cate',
                    'tbl_category_manages.category_name as category_name',
                    'tbl_category_manages.image as category_image',
                    'wallpapers.*'
//                    'tbl_category_manages.image as ca_image'
                )
                ->skip($start)
                ->take($rowperpage)
                ->get();


        }
//        dd($records);








//        $totalRecordswithFilter = CategoryHasWallpaper::select('count(*) as allcount')
//            ->where('site_id',$site->id)
////            ->where('site_name', 'like', '%' . $searchValue . '%')
//            ->count();


        // Get records, also we have included search filter as well
//        $records = CategoryHasWallpaper::where('site_id',$site->id)
//            ->join('tbl_category_manages','tbl_category_has_site.category_id','=','tbl_category_manages.id')
////            ->orderBy($columnName, $columnSortOrder)
//            ->where('tbl_category_manages.category_name', 'like', '%' . $searchValue . '%')
//            ->select('tbl_category_has_site.*',
//                'tbl_category_manages.id as id_cate',
//                'tbl_category_manages.category_name',
//                'tbl_category_manages.view_count',
//                'tbl_category_manages.checked_ip',
//                'tbl_category_manages.image as category_image')
//            ->skip($start)
//            ->take($rowperpage)
//            ->get();
//        dd($records);

        $data_arr = array();
        foreach ($records as $key => $record) {
//            if($record->image){
//                $image = $record->image;
//            }else{
//                $image = $record->category_image;
//            }
//            dd($record);
//            $cate_name = [];
//
//            foreach ($record->category as $category){
//                $cate_name[] =$category->category_name;
//            }
            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "category" => $record->category_name,
                "category_name" => $record->category_name,
                "image" => $record->thumbnail_image,
                "view_count" => $record->view_count,
                "like_count" => $record->like_count,
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

    //===========================================================

    public function site_BlockIps($id){
        $site = SiteManage::with('blockIps')->where('site_name',$id)->first();
        $pageConfigs = [
            'pageHeader' => false,
        ];
        $users = $this->user->all();
        $roles = $this->role->all();
        $blockIps = BlockIP::all();

        return view('content.site.site-view-block-ips', [
            'pageConfigs' => $pageConfigs,
            'users'=>$users,
            'roles'=>$roles,
            'blockIps' => $blockIps,
            'site' =>$site
        ]);

    }
    public function site_getBlockIps(Request $request,$id){
        $site = SiteManage::with('blockIps')->where("site_name",$id)->first();

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
        $totalRecords = count($site->blockIps);


        $totalRecordswithFilter = SiteManage::with('blockIps')->select('count(*) as allcount')
            ->leftJoin('tbl_site_has_block_ip', 'tbl_site_has_block_ip.sites_id', '=', 'tbl_site_manages.id')
            ->leftJoin('block_i_p_s', 'block_i_p_s.id', '=', 'tbl_site_has_block_ip.blockIps_id')
            ->where('ip_address', 'like', '%' . $searchValue . '%')
            ->where('tbl_site_has_block_ip.site_id',$site->id)
            ->count();


        // Get records, also we have included search filter as well
        $records = SiteManage::with('blockIps')->select('count(*) as allcount')
            ->leftJoin('tbl_site_has_block_ip', 'tbl_site_has_block_ip.sites_id', '=', 'tbl_site_manages.id')
            ->leftJoin('block_i_p_s', 'block_i_p_s.id', '=', 'tbl_site_has_block_ip.blockIps_id')
            ->where('ip_address', 'like', '%' . $searchValue . '%')
            ->where('tbl_site_has_block_ip.site_id',$site->id)
            ->select('block_i_p_s.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();

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
    public function site_deleteBlockIp($id,$id1){
        $site = SiteManage::where('site_name',$id)->first();
        $site_id = $site->id;
        BlockIpsHasSite::where('sites_id',$site_id)->where('blockIps_id',$id1)->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }
    public function site_editBlockIp( $id){
        $site = SiteManage::with('blockIps')->where('site_name',$id)->first();
        return response()->json($site);
    }
    public function site_updateBlockIp(Request $request){
        $id = $request->id_site;
        $site = SiteManage::find($id);
        $site->blockIps()->sync($request->block_ips_site);
        $site->save();
        return response()->json(['success'=>'Thêm mới thành công']);
    }

    //==========================================================

    public function site_Home($id){
        $site = SiteManage::where('site_name',$id)->first();
        if($site){
            $pageConfigs = [
                'pageHeader' => false,
            ];
            $users = $this->user->all();
            $roles = $this->role->all();
            return view('content.site.site-view-home', [
                'pageConfigs' => $pageConfigs,
                'users'=>$users,
                'roles'=>$roles,
                'site' =>$site,

            ]);
        }else{
            return 'Site không tồn tại';
        }
    }
    public function site_updateHome(Request $request){
        $id = $request->id;
        $site = SiteManage::find($id);
        if($request->header_image){
            if($site->header_image){
                $path_Remove =   storage_path('app/public/homes/').$site->header_image;
                if(file_exists($path_Remove)){
                    unlink($path_Remove);
                }
            }
            $image = $request->header_image;
            $filenameWithExt=$image->getClientOriginalName();
            $extension = $image->getClientOriginalExtension();
            $fileNameToStore = time().'.'.$extension;
            $now = new \DateTime('now'); //Datetime
            $monthNum = $now->format('m');
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F'); // Month
            $year = $now->format('Y'); // Year
            $monthYear = $monthName.$year;
            $path_image    =  storage_path('app/public/homes/'.$monthYear.'/');
            if (!file_exists($path_image)) {
                mkdir($path_image, 0777, true);
            }
            $img = Image::make($image);
            $image = $img->save($path_image.$fileNameToStore);
            $path_image =  $monthYear.'/'.$fileNameToStore;
            $header_image = $path_image;
            $site->header_image = $header_image;
        }
        $site->header_title = $request->header_title;
        $site->header_content = $request->header_content;
        $site->body_title = $request->body_title;
        $site->body_content = $request->body_content;
        $site->footer_title = $request->footer_title;
        $site->footer_content = $request->footer_content;
        $site->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }

    //===========================================================

    public function site_Policy($id){
        $site = SiteManage::where('site_name',$id)->first();
        if($site){
            $pageConfigs = [
                'pageHeader' => false,
            ];
            $users = $this->user->all();
            $roles = $this->role->all();
            return view('content.site.site-view-policy', [
                'pageConfigs' => $pageConfigs,
                'users'=>$users,
                'roles'=>$roles,
                'site' =>$site,
            ]);
        }else{
            return 'Site không tồn tại';
        }
    }
    public function site_updatePolicy(Request $request){
        $id = $request->id;
        $site = SiteManage::find($id);
        $site->policy = $request->policy;
        $site->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }

    //==========================================================

    public function site_LoadFeature($id){
        $site = SiteManage::with('category')->where('site_name',$id)->first();
        $pageConfigs = [
            'pageHeader' => false,
        ];
        $users = $this->user->all();
        $roles = $this->role->all();
        $categories = CategoryManage::all();
        $blockIps = BlockIP::all();

        return view('content.site.site-view-load-feature', [
            'pageConfigs' => $pageConfigs,
            'users'=>$users,
            'roles'=>$roles,
            'categories' => $categories,
            'blockIps' => $blockIps,
            'site' =>$site
        ]);

    }

    public function site_updateLoadFeature(Request $request,$id){
        $site = SiteManage::where('site_name',$id)->update(['load_view_by'=>$request->load_feature]);
        return response()->json(['success'=>'Cập nhật thành công']);
    }

}
