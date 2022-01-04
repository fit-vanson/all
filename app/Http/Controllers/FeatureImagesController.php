<?php

namespace App\Http\Controllers;

use App\Models\FeatureImage;
use App\Models\SiteManage;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;

class FeatureImagesController extends Controller
{
    private $user;
    public $role;
    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
    }
    public function index($id)
    {
        $site = SiteManage::with('feature_images')->where('site_name',$id)->first();
        $pageConfigs = [
            'pageHeader' => false,
        ];
        $users = $this->user->all();
        $roles = $this->role->all();
        $sites = SiteManage::all();
        return view('content.site.site-view-feature-images', [
            'pageConfigs' => $pageConfigs,
            'users'=>$users,
            'roles'=>$roles,
            'site' =>$site,
            'sites' =>$sites,
        ]);

    }
    public function getIndex(Request $request,$id)
    {
        $site = SiteManage::with('feature_images')->where("site_name",$id)->first();

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
        $totalRecords = count($site->feature_images);

        $totalRecordswithFilter = FeatureImage::where('site_id',$site->id)
            ->where('name', 'like', '%' . $searchValue . '%')
            ->count();
        $records = FeatureImage::where('site_id',$site->id)
            ->where('name', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
                "id" => $record->id,
                "image" => $record->image,
                "name" => $record->name,
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
        $data = new FeatureImage();
        $image = $request->file;
        $filenameWithExt=$image->getClientOriginalName();
        $filename = Str::slug(pathinfo($filenameWithExt, PATHINFO_FILENAME));
        $nameSite = Str::slug($request->id_site);
        $extension = $image->getClientOriginalExtension();
        $fileNameToStore = $nameSite.'_'.$filename.'.'.$extension;
        $now = new \DateTime('now'); //Datetime
        $monthNum = $now->format('m');
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // Month
        $year = $now->format('Y'); // Year
        $monthYear = $monthName.$year;
        $path_image    =  storage_path('app/public/feature-images/'.$monthYear.'/');
        if (!file_exists($path_image)) {
            mkdir($path_image, 0777, true);
        }
        $img = Image::make($image);
        $image = $img->save($path_image.$fileNameToStore);
        $path_image =  $monthYear.'/'.$fileNameToStore;
        $data['image'] = $path_image;
        $data['name'] = $filenameWithExt;
        $data['site_id'] = $request->id_site;

        $data->save();
        return response()->json([
            'success'=>'Thêm mới thành công'
        ]);
    }
    public function update(Request $request){
        $id = $request->id;
        $data = FeatureImage::find($id);

        if( $request->image){
            $path_Remove =   storage_path('app/public/feature-images/').$data->image;
            if(file_exists($path_Remove)){
                unlink($path_Remove);
            }
            $image = $request->image;
            $filenameWithExt=$image->getClientOriginalName();
            $filename = Str::slug(pathinfo($filenameWithExt, PATHINFO_FILENAME));
            $nameSite = Str::slug($request->site_id);
            $extension = $image->getClientOriginalExtension();
            $fileNameToStore = $nameSite.'_'.$filename.'.'.$extension;
            $now = new \DateTime('now'); //Datetime
            $monthNum = $now->format('m');
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F'); // Month
            $year = $now->format('Y'); // Year
            $monthYear = $monthName.$year;
            $path_image    =  storage_path('app/public/feature-images/'.$monthYear.'/');
            if (!file_exists($path_image)) {
                mkdir($path_image, 0777, true);
            }
            $img = Image::make($image);
            $image = $img->save($path_image.$fileNameToStore);
            $path_image =  $monthYear.'/'.$fileNameToStore;
            $data['image'] = $path_image;

        }
        $data['name'] = $request->feature_image_name;
        $data['site_id'] = $request->site_id;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function edit($id,$id_image)
    {
        $data = FeatureImage::find($id_image);
        return response()->json($data);
    }
    public function delete($id,$id1)
    {
        $featureImage = FeatureImage::find($id1);
        $path    =   storage_path('app/public/feature-images/').$featureImage->image;
        if(file_exists($path)){
            unlink($path);
        }
        $featureImage->delete();
        return response()->json(['success'=>'Xóa thành công.']);

    }
}
