<?php

namespace App\Http\Controllers;

use App\Models\FeatureImage;
use App\Models\Home;

use App\Models\SiteManage;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use ImageOrientationFix\ImageOrientationFixer;
use Illuminate\Support\Facades\Validator;
use Imagick;
use Intervention\Image\Facades\Image;
use Monolog\Logger;
use PHPExiftool\Reader;
use PHPExiftool\Driver\Value\ValueInterface;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    private $user;
    public $role;
    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
    }
    public function show(){
        $domain=$_SERVER['SERVER_NAME'];
        $site = SiteManage::where('site_name',$domain)->first();
        if($site){
            $home=Home::where('site_id',$site->id)->first();

            $images=FeatureImage::all();
            return view('content.index')->with(compact('images','home'));
        }
        else{
            return 'Site không tồn tại';
        }
    }


    public function home()
    {
        return view('content.home');
    }

    public function index(Request $request)
    {
        $pageConfigs = ['pageHeader' => false];
        $users = $this->user->all();
        $roles = $this->role->all();
        $sites = SiteManage::all();
        return view('content.home.index', [
            'pageConfigs' => $pageConfigs,
            'users'=>$users,
            'roles'=>$roles,
            'sites' => $sites
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
        $totalRecords = Home::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Home::with('site')->select('count(*) as allcount')
//            ->where('site_name', 'like', '%' . $searchValue . '%')
            ->count();
        // Get records, also we have included search filter as well
        $records = Home::with('site')
            ->whereHas('site', function ($q) use ($searchValue) {
                $q->where('site_name','like', '%' . $searchValue . '%');
            })

            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();

//        dd($records->wallpaper_count);
        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
                "id" => $record->id,
                "logo" => $record->header_image,
                "header_title" => $record->header_title,
                "site_name" => $record->site['site_name'],
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
            'header_image' => 'required',
        ];
        $message = [
            'header_image.required'=>'Header Image không để trống',
        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }

        $data = new Home();
        $image = $request->header_image;
        $filenameWithExt=$image->getClientOriginalName();
        $filename = $request->select_site;
        $extension = $image->getClientOriginalExtension();
        $fileNameToStore = $filename.'_'.time().'.'.$extension;
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
        $data['header_image'] = $path_image;
        $data['header_title'] = $request->header_title;
        $data['header_content'] = $request->header_content;
        $data['body_title'] = $request->body_title;
        $data['body_content'] = $request->body_content;
        $data['footer_title'] = $request->footer_title;
        $data['footer_content'] = $request->footer_content;
        $data['site_id'] = $request->id;
        $data->save();

        return response()->json([
            'success'=>'Thêm mới thành công'
        ]);
    }
    public function update(Request $request){

        $id = $request->id;

        $data = Home::find($id);

        if( $request->header_image){
            $path_Remove =   storage_path('app/public/homes/').$data->header_image;
            if(file_exists($path_Remove)){
                unlink($path_Remove);
            }

            $image = $request->header_image;
            $filenameWithExt=$image->getClientOriginalName();
            $filename = $request->select_site;
            $extension = $image->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
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
            $data['header_image'] = $path_image;

        }
        $data['header_title'] = $request->header_title;
        $data['header_content'] = $request->header_content;
        $data['body_title'] = $request->body_title;
        $data['body_content'] = $request->body_content;
        $data['footer_title'] = $request->footer_title;
        $data['footer_content'] = $request->footer_content;
        $data['site_id'] = $request->select_site;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function edit($id)
    {
        $data = Home::find($id);
        return response()->json($data);
    }
    public function delete($id)
    {
        $category = Home::find($id);
        $category->delete();
        return response()->json(['success'=>'Xóa thành công.']);

    }

}
