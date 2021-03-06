<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AdsResource_V1;
use App\Http\Resources\CategoryResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource_V1;
use App\Http\Resources\FeatureWallpaperResource;
use App\Http\Resources\WallpaperResource;
use App\Http\Resources\WallpaperResource_V1;
use App\Models\CategoryManage;
use App\Models\ListIp;
use App\Models\SiteManage;
use App\Models\Wallpapers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class ApiController extends Controller
{

    public $_content_type = "application/json";
    private $_code = 200;
    public $_request = array();
    public function index(){
        dd(1);
        $api_key = 'UMvz0pkHexZ3ApdN4fpmVSJSiBXEEqLd8mZhgywEXVQvJ4LPTOWcYYSt0j4QO8Zm';
        if(isset($_GET['keyapi']) && $_GET['keyapi'] == $api_key ){
            if (isset($_GET['action']) && $_GET['action'] == "get_category") {
                $this->getCategory();

            } else if (isset($_GET['action']) && $_GET['action'] == "get_category_detail") {
                $id = $_GET['id'];
                $offset = $_GET['offset'];
                $this->getCategoryDetail($id, $offset);

            } else if (isset($_GET['action']) && $_GET['action'] == "get_recent") {

                $offset = $_GET['offset'];

                $this->getRecent($offset);

            } else if (isset($_GET['action']) && $_GET['action'] == "get_popular") {

                $offset = $_GET['offset'];
                $this->getPopular($offset);

            } else if (isset($_GET['action']) && $_GET['action'] == "get_random") {

                $offset = $_GET['offset'];
                $this->getRandom($offset);

            } else if (isset($_GET['action']) && $_GET['action'] == "get_featured") {

                $offset = $_GET['offset'];
                $this->getFeatured($offset);

            } else if (isset($_GET['action']) && $_GET['action'] == "get_search") {

                $search = $_GET['search'];
                $offset = $_GET['offset'];
                $this->getSearch($search, $offset);

            } else if (isset($_GET['action']) && $_GET['action'] == "view_count") {

                $id = $_GET['id'];
                $this->viewCount($id);

            } else if (isset($_GET['action']) && $_GET['action'] == "download_count") {

                $id = $_GET['id'];
                $this->downloadCount($id);

            } else if (isset($_GET['action']) && $_GET['action'] == "get_privacy_policy") {

                $this->getPrivacyPolicy();

            } else if (isset($_GET['get_wallpapers'])) {
                $this->get_wallpapers();
            } else if (isset($_GET['get_one_wallpaper'])) {
                $this->get_one_wallpaper();
            } else if (isset($_GET['get_categories'])) {
                $this->get_categories();
            } else if (isset($_GET['get_category_details'])) {
                $this->get_category_details();
            } else if (isset($_GET['get_search'])) {
                $this->get_search();
            } else if (isset($_GET['get_search_category'])) {
                $this->get_search_category();
            } else if (isset($_GET['update_view'])) {
                $this->update_view();
            } else if (isset($_GET['update_download'])) {
                $this->update_download();
            } else if (isset($_GET['get_ads'])) {
                $this->get_ads();
            } else if (isset($_GET['get_settings'])) {
                $this->get_settings();
            } else {
                $this->processApi();
            }
        }else {
            $this->processApi();
        }

    }


    function getCategory() {
        $domain=$_SERVER['SERVER_NAME'];
        if(checkBlockIp()){
            $data = CategoryManage::
            leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                ->has('wallpaper','>',0)
                ->where('site_name',$domain)
                ->where('tbl_category_manages.checked_ip',1)
                ->select('tbl_category_manages.*','tbl_category_has_site.image as site_image')
                ->withCount('wallpaper')
                ->get();
        } else{
            $data = CategoryManage::
            leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                ->has('wallpaper','>',0)
                ->where('site_name',$domain)
                ->where('tbl_category_manages.checked_ip',0)
                ->select('tbl_category_manages.*','tbl_category_has_site.image as site_image')
                ->withCount('wallpaper')
                ->get();
        }
        dd($data);







        $setting_qry = "SELECT * FROM tbl_settings where id = '1'";
        $result = mysqli_query($connect, $setting_qry);
        $row    = mysqli_fetch_assoc($result);
        $sort   = $row['category_sort'];
        $order  = $row['category_order'];

        $json_object = array();

        $query = "SELECT cid, category_name, category_image FROM tbl_category ORDER BY $sort $order";
        $sql = mysqli_query($connect, $query);

        while ($data = mysqli_fetch_assoc($sql)) {

            $query = "SELECT COUNT(*) as num FROM tbl_gallery WHERE cat_id = '".$data['cid']."'";
            $total = mysqli_fetch_array(mysqli_query($connect, $query));
            $total = $total['num'];

            $object['category_id'] = $data['cid'];
            $object['category_name'] = $data['category_name'];
            $object['category_image'] = $data['category_image'];
            $object['total_wallpaper'] = $total;

            array_push($json_object, $object);

        }

        $set = $json_object;

        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

    }

    function getCategoryDetail($id, $offset) {



        dd(1);

        include_once "../includes/config.php";

        $qry = "SELECT * FROM tbl_settings where id = '1'";
        $result = mysqli_query($connect, $qry);
        $settings_row = mysqli_fetch_assoc($result);
        $load_more = $settings_row['limit_recent_wallpaper'];

        $id = $_GET['id'];
        $offset = isset($_GET['offset']) && $_GET['offset'] != '' ? $_GET['offset'] : 0;


        $all = mysqli_query($connect, "SELECT * FROM tbl_gallery ORDER BY id DESC");
        $count_all = mysqli_num_rows($all);
        $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id AND c.cid = $id ORDER BY w.id DESC LIMIT $offset, $load_more");
        $count = mysqli_num_rows($query);
        $json_empty = 0;
        if ($count < $load_more) {
            if ($count == 0) {
                $json_empty = 1;
            } else {
                $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id AND c.cid = $id ORDER BY w.id DESC LIMIT $offset, $count");
                $count = mysqli_num_rows($query);
                if (empty($count)) {
                    $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id AND c.cid = $id ORDER BY w.id DESC LIMIT 0, $load_more");
                    $num = 0;
                } else {
                    $num = $offset;
                }
            }
        } else {
            $num = $offset;
        }
        $json = '[';
        while ($row = mysqli_fetch_array($query)) {
            $num++;
            $char ='"';
            $json .= '{
				"no": '.$num.',
				"image_id": "'.$row['id'].'",
				"image_upload": "'.$row['image'].'",
				"image_url": "'.$row['image_url'].'",
				"type": "'.$row['type'].'",
				"view_count": "'.$row['view_count'].'",
				"download_count": "'.$row['download_count'].'",
				"featured": "'.$row['featured'].'",
				"tags": "'.$row['tags'].'",
				"category_id": "'.$row['category_id'].'",
				"category_name": "'.$row['category_name'].'"
			},';
        }

        $json = substr($json,0, strlen($json)-1);

        if ($json_empty == 1) {
            $json = '[]';
        } else {
            $json .= ']';
        }

        header('Content-Type: application/json; charset=utf-8');
        echo $json;

        mysqli_close($connect);

    }

    function getRecent($offset) {

        include_once "../includes/config.php";

        $qry = "SELECT * FROM tbl_settings where id = '1'";
        $result = mysqli_query($connect, $qry);
        $settings_row = mysqli_fetch_assoc($result);
        $load_more = $settings_row['limit_recent_wallpaper'];

        $offset = isset($_GET['offset']) && $_GET['offset'] != '' ? $_GET['offset'] : 0;
        $all = mysqli_query($connect, "SELECT * FROM tbl_gallery ORDER BY id DESC");
        $count_all = mysqli_num_rows($all);
        $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id ORDER BY w.id DESC LIMIT $offset, $load_more");
        $count = mysqli_num_rows($query);
        $json_empty = 0;
        if ($count < $load_more) {
            if ($count == 0) {
                $json_empty = 1;
            } else {
                $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id ORDER BY w.id DESC LIMIT $offset, $count");
                $count = mysqli_num_rows($query);
                if (empty($count)) {
                    $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id ORDER BY w.id DESC LIMIT 0, $load_more");
                    $num = 0;
                } else {
                    $num = $offset;
                }
            }
        } else {
            $num = $offset;
        }
        $json = '[';
        while ($row = mysqli_fetch_array($query)) {
            $num++;
            $char ='"';
            $json .= '{
				"no": '.$num.',
				"image_id": "'.$row['id'].'",
				"image_upload": "'.$row['image'].'",
				"image_url": "'.$row['image_url'].'",
				"type": "'.$row['type'].'",
				"view_count": "'.$row['view_count'].'",
				"download_count": "'.$row['download_count'].'",
				"featured": "'.$row['featured'].'",
				"tags": "'.$row['tags'].'",
				"category_id": "'.$row['category_id'].'",
				"category_name": "'.$row['category_name'].'"
			},';
        }

        $json = substr($json,0, strlen($json)-1);

        if ($json_empty == 1) {
            $json = '[]';
        } else {
            $json .= ']';
        }

        header('Content-Type: application/json; charset=utf-8');
        echo $json;

        mysqli_close($connect);

    }


    function getPopular($offset) {

        include_once "../includes/config.php";

        $qry = "SELECT * FROM tbl_settings where id = '1'";
        $result = mysqli_query($connect, $qry);
        $settings_row = mysqli_fetch_assoc($result);
        $load_more = $settings_row['limit_recent_wallpaper'];

        $offset = isset($_GET['offset']) && $_GET['offset'] != '' ? $_GET['offset'] : 0;
        $all = mysqli_query($connect, "SELECT * FROM tbl_gallery ORDER BY id DESC");
        $count_all = mysqli_num_rows($all);
        $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id ORDER BY w.view_count DESC LIMIT $offset, $load_more");
        $count = mysqli_num_rows($query);
        $json_empty = 0;
        if ($count < $load_more) {
            if ($count == 0) {
                $json_empty = 1;
            } else {
                $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id ORDER BY w.view_count DESC LIMIT $offset, $count");
                $count = mysqli_num_rows($query);
                if (empty($count)) {
                    $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id ORDER BY w.view_count DESC LIMIT 0, $load_more");
                    $num = 0;
                } else {
                    $num = $offset;
                }
            }
        } else {
            $num = $offset;
        }
        $json = '[';
        while ($row = mysqli_fetch_array($query)) {
            $num++;
            $char ='"';
            $json .= '{
				"no": '.$num.',
				"image_id": "'.$row['id'].'",
				"image_upload": "'.$row['image'].'",
				"image_url": "'.$row['image_url'].'",
				"type": "'.$row['type'].'",
				"view_count": "'.$row['view_count'].'",
				"download_count": "'.$row['download_count'].'",
				"featured": "'.$row['featured'].'",
				"tags": "'.$row['tags'].'",
				"category_id": "'.$row['category_id'].'",
				"category_name": "'.$row['category_name'].'"
			},';
        }

        $json = substr($json,0, strlen($json)-1);

        if ($json_empty == 1) {
            $json = '[]';
        } else {
            $json .= ']';
        }

        header('Content-Type: application/json; charset=utf-8');
        echo $json;

        mysqli_close($connect);

    }

    function getRandom($offset) {

        include_once "../includes/config.php";

        $qry = "SELECT * FROM tbl_settings where id = '1'";
        $result = mysqli_query($connect, $qry);
        $settings_row = mysqli_fetch_assoc($result);
        $load_more = $settings_row['limit_recent_wallpaper'];

        $offset = isset($_GET['offset']) && $_GET['offset'] != '' ? $_GET['offset'] : 0;
        $all = mysqli_query($connect, "SELECT * FROM tbl_gallery ORDER BY id DESC");
        $count_all = mysqli_num_rows($all);
        $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id ORDER BY RAND() DESC LIMIT $offset, $load_more");
        $count = mysqli_num_rows($query);
        $json_empty = 0;
        if ($count < $load_more) {
            if ($count == 0) {
                $json_empty = 1;
            } else {
                $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id ORDER BY RAND() DESC LIMIT $offset, $count");
                $count = mysqli_num_rows($query);
                if (empty($count)) {
                    $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id ORDER BY RAND() w.view_count DESC LIMIT 0, $load_more");
                    $num = 0;
                } else {
                    $num = $offset;
                }
            }
        } else {
            $num = $offset;
        }
        $json = '[';
        while ($row = mysqli_fetch_array($query)) {
            $num++;
            $char ='"';
            $json .= '{
				"no": '.$num.',
				"image_id": "'.$row['id'].'",
				"image_upload": "'.$row['image'].'",
				"image_url": "'.$row['image_url'].'",
				"type": "'.$row['type'].'",
				"view_count": "'.$row['view_count'].'",
				"download_count": "'.$row['download_count'].'",
				"featured": "'.$row['featured'].'",
				"tags": "'.$row['tags'].'",
				"category_id": "'.$row['category_id'].'",
				"category_name": "'.$row['category_name'].'"
			},';
        }

        $json = substr($json,0, strlen($json)-1);

        if ($json_empty == 1) {
            $json = '[]';
        } else {
            $json .= ']';
        }

        header('Content-Type: application/json; charset=utf-8');
        echo $json;

        mysqli_close($connect);

    }

    function getFeatured($offset) {

        include_once "../includes/config.php";

        $qry = "SELECT * FROM tbl_settings where id = '1'";
        $result = mysqli_query($connect, $qry);
        $settings_row = mysqli_fetch_assoc($result);
        $load_more = $settings_row['limit_recent_wallpaper'];

        $offset = isset($_GET['offset']) && $_GET['offset'] != '' ? $_GET['offset'] : 0;
        $all = mysqli_query($connect, "SELECT * FROM tbl_gallery ORDER BY id DESC");
        $count_all = mysqli_num_rows($all);
        $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id AND w.featured = 'yes' ORDER BY w.id DESC LIMIT $offset, $load_more");
        $count = mysqli_num_rows($query);
        $json_empty = 0;
        if ($count < $load_more) {
            if ($count == 0) {
                $json_empty = 1;
            } else {
                $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id AND w.featured = 'yes' ORDER BY w.id LIMIT $offset, $count");
                $count = mysqli_num_rows($query);
                if (empty($count)) {
                    $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id AND w.featured = 'yes' ORDER BY w.id LIMIT 0, $load_more");
                    $num = 0;
                } else {
                    $num = $offset;
                }
            }
        } else {
            $num = $offset;
        }
        $json = '[';
        while ($row = mysqli_fetch_array($query)) {
            $num++;
            $char ='"';
            $json .= '{
				"no": '.$num.',
				"image_id": "'.$row['id'].'",
				"image_upload": "'.$row['image'].'",
				"image_url": "'.$row['image_url'].'",
				"type": "'.$row['type'].'",
				"view_count": "'.$row['view_count'].'",
				"download_count": "'.$row['download_count'].'",
				"featured": "'.$row['featured'].'",
				"tags": "'.$row['tags'].'",
				"category_id": "'.$row['category_id'].'",
				"category_name": "'.$row['category_name'].'"
			},';
        }

        $json = substr($json,0, strlen($json)-1);

        if ($json_empty == 1) {
            $json = '[]';
        } else {
            $json .= ']';
        }

        header('Content-Type: application/json; charset=utf-8');
        echo $json;

        mysqli_close($connect);

    }

    function getSearch($search, $offset) {

        include_once "../includes/config.php";

        $qry = "SELECT * FROM tbl_settings where id = '1'";
        $result = mysqli_query($connect, $qry);
        $settings_row = mysqli_fetch_assoc($result);
        $load_more = $settings_row['limit_recent_wallpaper'];

        $search = $_GET['search'];
        $offset = isset($_GET['offset']) && $_GET['offset'] != '' ? $_GET['offset'] : 0;


        $all = mysqli_query($connect, "SELECT * FROM tbl_gallery ORDER BY id DESC");
        $count_all = mysqli_num_rows($all);
        $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id AND (c.category_name LIKE '%$search%' OR w.tags LIKE '%$search%') ORDER BY w.id DESC LIMIT $offset, $load_more");
        $count = mysqli_num_rows($query);
        $json_empty = 0;
        if ($count < $load_more) {
            if ($count == 0) {
                $json_empty = 1;
            } else {
                $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id AND (c.category_name LIKE '%$search%' OR w.tags LIKE '%$search%') ORDER BY w.id DESC LIMIT $offset, $count");
                $count = mysqli_num_rows($query);
                if (empty($count)) {
                    $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id AND (c.category_name LIKE '%$search%' OR w.tags LIKE '%$search%') ORDER BY w.id DESC LIMIT 0, $load_more");
                    $num = 0;
                } else {
                    $num = $offset;
                }
            }
        } else {
            $num = $offset;
        }
        $json = '[';
        while ($row = mysqli_fetch_array($query)) {
            $num++;
            $char ='"';
            $json .= '{
				"no": '.$num.',
				"image_id": "'.$row['id'].'",
				"image_upload": "'.$row['image'].'",
				"image_url": "'.$row['image_url'].'",
				"type": "'.$row['type'].'",
				"view_count": "'.$row['view_count'].'",
				"download_count": "'.$row['download_count'].'",
				"featured": "'.$row['featured'].'",
				"tags": "'.$row['tags'].'",
				"category_id": "'.$row['category_id'].'",
				"category_name": "'.$row['category_name'].'"
			},';
        }

        $json = substr($json,0, strlen($json)-1);

        if ($json_empty == 1) {
            $json = '[]';
        } else {
            $json .= ']';
        }

        header('Content-Type: application/json; charset=utf-8');
        echo $json;

        mysqli_close($connect);

    }

    function viewCount($id) {

        $id = $_GET['id'];

        include_once "../includes/config.php";

        $jsonObj = array();

        $query = "SELECT * FROM tbl_gallery WHERE id = $id";
        $sql = mysqli_query($connect, $query) or die(mysqli_error());

        while ($data = mysqli_fetch_assoc($sql)) {

            $row['id'] = $data['id'];
            $row['view_count'] = $data['view_count'];

            array_push($jsonObj, $row);

        }

        $view_qry = mysqli_query($connect, " UPDATE tbl_gallery SET view_count = view_count + 1 WHERE id = $id ");

        $set['result'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

    }

    function downloadCount($id) {

        $id = $_GET['id'];

        include_once "../includes/config.php";

        $jsonObj = array();

        $query = "SELECT * FROM tbl_gallery WHERE id = $id";
        $sql = mysqli_query($connect, $query) or die(mysqli_error());

        while ($data = mysqli_fetch_assoc($sql)) {

            $row['id'] = $data['id'];
            $row['download_count'] = $data['download_count'];

            array_push($jsonObj, $row);

        }

        $view_qry = mysqli_query($connect, " UPDATE tbl_gallery SET download_count = download_count + 1 WHERE id = $id ");

        $set['result'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

    }

    function getPrivacyPolicy() {

        include_once "../includes/config.php";

        $query = "SELECT privacy_policy FROM tbl_settings LIMIT 1";
        $resouter = mysqli_query($connect, $query);

        $set = array();
        $total_records = mysqli_num_rows($resouter);
        if($total_records >= 1) {
            while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){
                $set = $link;
            }
        }

        header('Content-Type: application/json; charset=utf-8');
        echo $val = str_replace('\\/', '/', json_encode($set));

    }





    public function processApi() {
        if(isset($_REQUEST['x']) && $_REQUEST['x']!=""){
            $func = strtolower(trim(str_replace("/","", $_REQUEST['x'])));
            if((int)method_exists($this,$func) > 0) {
                $this->$func();
            } else {
                header( 'Content-Type: application/json; charset=utf-8' );
                echo 'processApi - method not exist';
                exit;
            }
        } else {
            header( 'Content-Type: application/json; charset=utf-8' );
            echo 'processApi - method not exist';
            exit;
        }
    }

    /* Api Checker */
    public function check_connection() {
        if (mysqli_ping($this->mysqli)) {
            //echo "Responses : Congratulations, database successfully connected.";
            $respon = array(
                'status' => 'ok', 'database' => 'connected'
            );
            $this->response($this->json($respon), 200);
        } else {
            $respon = array(
                'status' => 'failed', 'database' => 'not connected'
            );
            $this->response($this->json($respon), 404);
        }
    }

    public function get_wallpapers() {

        $domain=$_SERVER['SERVER_NAME'];
        if($this->get_request_method() != "GET") $this->response('',406);
        $limit = isset($_GET['count']) ? ((int)$_GET['count']) : 10;
        $page = isset($_GET['page']) ? ((int)$_GET['page']) : 1;
        $order = isset($_GET['order']) ? ((int)$_GET['order']) : 1;


        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
            $ipaddress= $_SERVER["HTTP_CF_CONNECTING_IP"];
        else
            $ipaddress = 'UNKNOWN';

        $site=SiteManage::where('site_name',$domain)->first();
        $listIp=ListIp::where('ip_address',$ipaddress)->where('id_site',$site->id)->whereDate('created_at', Carbon::today())->first();
        if(!$listIp){
            ListIp::create([
                'ip_address'=>$ipaddress,
                'id_site' => $site->id
            ]);
        }else{
            $listIp=ListIp::where('ip_address',get_ip())->where('id_site',$site->id)->whereDate('created_at', Carbon::today())->first();
            if(!$listIp){
                ListIp::create([
                    'ip_address'=>get_ip(),
                    'id_site' => $site->id
                ]);
            }
        }

        if (checkBlockIp()) {
            if($order == 1){
                $data = Wallpapers::where('image_extension','<>','image/gif')->whereHas('category', function ($q) use ($domain) {
                    $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                        ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                        ->where('site_name',$domain)
                        ->where('tbl_category_manages.checked_ip',1)
                        ->select('tbl_category_manages.*');
                    })
                    ->orderBy('id', 'desc')
                    ->paginate($limit);
            }elseif ($order ==2){
                $data = Wallpapers::where('image_extension','<>','image/gif')->where('feature', 1)->whereHas('category', function ($q) use ($domain) {
                    $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                        ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                        ->where('site_name',$domain)
                        ->where('tbl_category_manages.checked_ip',1)
                        ->select('tbl_category_manages.*');
                    })
                    ->orderBy('updated_at', 'desc')
                    ->paginate($limit);
            }elseif ($order ==3){
                $data = Wallpapers::where('image_extension','<>','image/gif')->whereHas('category', function ($q) use ($domain) {
                    $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                        ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                        ->where('site_name',$domain)
                        ->where('tbl_category_manages.checked_ip',1)
                        ->select('tbl_category_manages.*');
                    })
                    ->orderBy('view_count', 'desc')
                    ->paginate($limit);
            }elseif ($order ==4){
                $data = Wallpapers::where('image_extension','<>','image/gif')->whereHas('category', function ($q) use ($domain) {
                    $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                        ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                        ->where('site_name',$domain)
                        ->where('tbl_category_manages.checked_ip',1)
                        ->select('tbl_category_manages.*');
                    })
                    ->inRandomOrder()
                    ->paginate($limit);
            }else{
                $data = Wallpapers::where('image_extension','image/gif')->whereHas('category', function ($q) use ($domain) {
                    $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                        ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                        ->where('site_name',$domain)
                        ->where('tbl_category_manages.checked_ip',1)
                        ->select('tbl_category_manages.*');
                    })
                    ->orderBy('id', 'desc')
                    ->paginate($limit);
            }
        } else {
            if($order == 1){
                $data = Wallpapers::where('image_extension','<>','image/gif')->whereHas('category', function ($q) use ($domain) {
                    $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                        ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                        ->where('site_name',$domain)
                        ->where('tbl_category_manages.checked_ip',0)
                        ->select('tbl_category_manages.*');
                    })
                    ->orderBy('id', 'desc')
                    ->paginate($limit);
            }elseif ($order ==2){
                $data = Wallpapers::where('feature', 1)->where('image_extension','<>','image/gif')->whereHas('category', function ($q) use ($domain) {
                    $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                        ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                        ->where('site_name',$domain)
                        ->where('tbl_category_manages.checked_ip',0)
                        ->select('tbl_category_manages.*');
                    })
                    ->orderBy('updated_at', 'desc')
                    ->paginate($limit);
            }elseif ($order ==3){
                $data = Wallpapers::where('image_extension','<>','image/gif')->whereHas('category', function ($q) use ($domain) {
                    $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                        ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                        ->where('site_name',$domain)
                        ->where('tbl_category_manages.checked_ip',0)
                        ->select('tbl_category_manages.*');
                    })
                    ->orderBy('view_count', 'desc')
                    ->paginate($limit);
            }elseif ($order ==4){
                $data = Wallpapers::where('image_extension','<>','image/gif')->whereHas('category', function ($q) use ($domain) {
                    $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                        ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                        ->where('site_name',$domain)
                        ->where('tbl_category_manages.checked_ip',0)
                        ->select('tbl_category_manages.*');
                    })
                    ->inRandomOrder()
                    ->paginate($limit);
            }else{
                $data = Wallpapers::where('image_extension','image/gif')->whereHas('category', function ($q) use ($domain) {
                    $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                        ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                        ->where('site_name',$domain)
                        ->where('tbl_category_manages.checked_ip',0)
                        ->select('tbl_category_manages.*');
                    })
                    ->orderBy('id', 'desc')
                    ->paginate($limit);
            }
        }


        $getResource= WallpaperResource_V1::collection($data);
        $count_total = $data->total();
        $count = count($data->items());
        $respon = array(
            'status' => 'ok', 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $getResource
        );
        $this->response($this->json($respon), 200);

    }

    public function get_one_wallpaper() {

        if($this->get_request_method() != "GET") $this->response('',406);
        $id = $_GET['id'];
        $query = "SELECT g.id AS 'image_id', g.image_name, g.image AS 'image_upload', g.image_url, g.type, g.image_resolution AS 'resolution', g.image_size AS 'size', g.image_extension AS 'mime', g.view_count AS 'views', g.download_count AS 'downloads', g.featured, g.tags, c.cid AS 'category_id', c.category_name, g.last_update FROM tbl_category c, tbl_gallery g WHERE c.cid = g.cat_id AND g.id = $id";

        $wallpaper = $this->get_one_result($query);

        $respon = array(
            'status' => 'ok', 'wallpaper' => $wallpaper
        );
        $this->response($this->json($respon), 200);

    }

    public function get_categories() {
        if($this->get_request_method() != "GET") $this->response('',406);
        $domain=$_SERVER['SERVER_NAME'];
        if(checkBlockIp()){
            $data = CategoryManage::
            leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                ->has('wallpaper','>',0)
                ->where('site_name',$domain)
                ->where('tbl_category_manages.checked_ip',1)
                ->select('tbl_category_manages.*','tbl_category_has_site.image as site_image')
                ->withCount('wallpaper')
                ->get();
        } else{
            $data = CategoryManage
                ::leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                ->has('wallpaper','>',0)
                ->where('site_name',$domain)
                ->where('tbl_category_manages.checked_ip',0)
//                ->select('tbl_category_manages.*','tbl_category_has_site.image as site_image')
                ->withCount('wallpaper')
                ->get();
        }
        $categories =  CategoryResource_V1::collection($data);
        $count = count($categories);
        $respon = array(
            'status' => 'ok', 'count' => $count, 'categories' => $categories
        );
        $this->response($this->json($respon), 200);

    }

    public function get_category_details() {

        if($this->get_request_method() != "GET") $this->response('',406);
//        dd($_GET['page']);
        $limit = isset($_GET['count']) ? ((int)$_GET['count']) : 10;
        $page = isset($_GET['page']) ? ((int)$_GET['page']) : 1;

        $order = $_GET['order'];
        $offset = ($page * $limit) - $limit;

        $id = $_GET['id'];
        $domain=$_SERVER['SERVER_NAME'];
            $wallpapers = CategoryManage::findOrFail($id)
                ->wallpaper()
                ->orderBy('like_count', 'desc')
                ->skip($offset)
                ->take($limit)
                ->get();
//                ->paginate($limit);
            CategoryManage::findOrFail($id)->increment('view_count');

        $categories= WallpaperResource_V1::collection($wallpapers);
        $count_total = CategoryManage::findOrFail($id)
            ->wallpaper()
            ->count();
        $count = count($categories);
        $respon = array(
            'status' => 'ok', 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $categories
        );
        $this->response($this->json($respon), 200);

    }

    public function get_search() {

        if($this->get_request_method() != "GET") $this->response('',406);
        $limit = isset($this->_request['count']) ? ((int)$this->_request['count']) : 10;
        $page = isset($this->_request['page']) ? ((int)$this->_request['page']) : 1;

        $search = $_GET['search'];
        $order = $_GET['order'];

        $offset = ($page * $limit) - $limit;
        $count_total = $this->get_count_result("SELECT COUNT(DISTINCT g.id) FROM tbl_gallery g, tbl_category c WHERE c.cid = g.cat_id AND (g.image_name LIKE '%$search%' OR g.tags LIKE '%$search%')");

        $query = "SELECT g.id AS 'image_id', g.image_name, g.image AS 'image_upload', g.image_url, g.type, g.image_resolution AS 'resolution', g.image_size AS 'size', g.image_extension AS 'mime', g.view_count AS 'views', g.download_count AS 'downloads', g.featured, g.tags, c.cid AS 'category_id', c.category_name, g.last_update FROM tbl_category c, tbl_gallery g WHERE c.cid = g.cat_id AND (g.image_name LIKE '%$search%' OR g.tags LIKE '%$search%') $order LIMIT $limit OFFSET $offset";

        $post = $this->get_list_result($query);
        $count = count($post);
        $respon = array(
            'status' => 'ok', 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $post
        );
        $this->response($this->json($respon), 200);

    }

    public function get_search_category() {

        include ("../../includes/config.php");

        if($this->get_request_method() != "GET") $this->response('',406);

        $search = $_GET['search'];

        $query = "SELECT DISTINCT c.cid AS 'category_id', c.category_name, c.category_image, COUNT(DISTINCT g.id) as total_wallpaper
			FROM tbl_category c LEFT JOIN tbl_gallery g ON c.cid = g.cat_id WHERE c.category_name LIKE '%$search%' GROUP BY c.cid ORDER BY c.cid DESC";

        $post = $this->get_list_result($query);
        $count = count($post);
        $respon = array(
            'status' => 'ok', 'count' => $count, 'categories' => $post
        );
        $this->response($this->json($respon), 200);
    }

    public function update_view() {
        $image_id = $_POST['image_id'];
        $wallpaper = Wallpapers::find($image_id);
        if ($wallpaper) {
            $wallpaper->view_count = $wallpaper->view_count + 1;
            $wallpaper->save();
            header( 'Content-Type: application/json; charset=utf-8' );
            echo json_encode(array('response' => "View updated"));
        } else {
            header( 'Content-Type: application/json; charset=utf-8' );
            echo json_encode(array('response' => "Failed"));
        }
    }

    public function update_download() {
        $image_id = $_POST['image_id'];

        $wallpaper = Wallpapers::find($image_id);
        if ($wallpaper) {
            $wallpaper->like_count = $wallpaper->like_count + 1;
            $wallpaper->save();
            header( 'Content-Type: application/json; charset=utf-8' );
            echo json_encode(array('response' => "View updated"));
        } else {
            header( 'Content-Type: application/json; charset=utf-8' );
            echo json_encode(array('response' => "Failed"));
        }

    }

    public function get_ads() {
        if($this->get_request_method() != "GET") $this->response('',406);

        $domain=$_SERVER['SERVER_NAME'];
        $data = SiteManage::where('site_name',$domain)
            ->first()->toArray();

        $ads = json_decode($data['ads'], true);

        $ads_arr = [
            'id' => $data['id'],
            'ad_status' => $data['ad_switch'] == 1 ? 'on' : 'off',
            'ad_type' => 'admob',
            'admob_publisher_id' => $ads['AdMob_Publisher_ID'],
            'admob_app_id' => $ads['AdMob_App_ID'],
            'admob_banner_unit_id' => $ads['AdMob_Banner_Ad_Unit_ID'],
            'admob_interstitial_unit_id' => $ads['AdMob_Interstitial_Ad_Unit_ID'],
            'admob_native_unit_id' => $ads['AdMob_Native_Ad_Unit_ID'],
            'admob_app_open_ad_unit_id' => $ads['AdMob_App_Open_Ad_Unit_ID'],
            'fan_banner_unit_id' => 0,
            'fan_interstitial_unit_id' => 0,
            'fan_native_unit_id' => 0,
            'startapp_app_id' => 0,
            'unity_game_id' => 0,
            'unity_banner_placement_id' => 'banner',
            'unity_interstitial_placement_id' => 'video',
            'applovin_banner_ad_unit_id' => 0,
            'applovin_interstitial_ad_unit_id' => 0,
            'mopub_banner_ad_unit_id' => 0,
            'mopub_interstitial_ad_unit_id' => 0,
            'interstitial_ad_interval' => 0,
            'native_ad_interval' => 0,
            'native_ad_index' => 6,
            'last_update_ads' => $data['updated_at'],
        ];
        $ads_status = [
            'ads_status_id' => 1,
            'banner_ad_on_home_page' => 1,
            'banner_ad_on_search_page' => 1,
            'banner_ad_on_wallpaper_detail' => 1,
            'banner_ad_on_wallpaper_by_category' => 1,
            'interstitial_ad_on_click_wallpaper' => 1,
            'interstitial_ad_on_wallpaper_detail' => 1,
            'native_ad_on_wallpaper_list' => 1,
            'native_ad_on_exit_dialog' => 1,
            'app_open_ad' => 1,
            'last_update_ads_status' => $data['updated_at'],

        ];

        $respon = array(
            'status' => 'ok', 'ads' => $ads_arr, 'ads_status' => $ads_status
        );
        $this->response($this->json($respon), 200);
    }

    public function get_settings() {

        if($this->get_request_method() != "GET") $this->response('',406);

        $domain=$_SERVER['SERVER_NAME'];
        $data = SiteManage::where('site_name',$domain)
            ->first()->toArray();

        $settings = [
            'onesignal_app_id' =>'d',
            'privacy_policy' =>$data['policy'],
        ];

        $respon = array(
            'status' => 'ok', 'settings' => $settings
        );
        $this->response($this->json($respon), 200);
    }

    /*
    * ======================================================================================================
    * =============================== API utilities # DO NOT EDIT ==========================================
    */

    private function get_list($query) {
        $r = $this->mysqli->query($query) or die($this->mysqli->errog.__LINE__);
        if($r->num_rows > 0) {
            $result = array();
            while($row = $r->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200); // send user details
        }
        $this->response('',204);	// If no records "No Content" status
    }

    private function get_list_result($data) {
        $result = array();
//        dd(!empty($query));
//        $r = $this->mysqli->query($query) or die($this->mysqli->errog.__LINE__);
        if(!empty($data)) {
            foreach ($data as $item)

            while($query = $r->fetch_assoc()) {
                $result[] = $row;
            }
        }
        return $result;
    }

    private function get_category_result($query) {
        $result = array();
        $r = $this->mysqli->query($query) or die($this->mysqli->errog.__LINE__);
        if($r->num_rows > 0) {
            while($row = $r->fetch_assoc()) {
                $result = $row;
            }
        }
        return $result;
    }

    private function get_one_result($query) {
        $result = array();
        $r = $this->mysqli->query($query) or die($this->mysqli->errog.__LINE__);
        if($r->num_rows > 0) $result = $r->fetch_assoc();
        return $result;
    }

    private function get_one($query) {
        $r = $this->mysqli->query($query) or die($this->mysqli->errog.__LINE__);
        if($r->num_rows > 0) {
            $result = $r->fetch_assoc();
            $this->response($this->json($result), 200); // send user details
        }
        $this->response('',204);	// If no records "No Content" status
    }

    private function get_one_detail($query) {
        $result = array();
        $r = $this->mysqli->query($query) or die($this->mysqli->errog.__LINE__);
        if($r->num_rows > 0) $result = $r->fetch_assoc();
        return $result;
    }

    private function get_count($query) {
        $r = $this->mysqli->query($query) or die($this->mysqli->errog.__LINE__);
        if($r->num_rows > 0) {
            $result = $r->fetch_row();
            $this->response($result[0], 200);
        }
        $this->response('',204);	// If no records "No Content" status
    }

    private function get_count_result($query) {
        $r = $this->mysqli->query($query) or die($this->mysqli->errog.__LINE__);
        if($r->num_rows > 0) {
            $result = $r->fetch_row();
            return $result[0];
        }
        return 0;
    }

    private function post_one($obj, $column_names, $table_name) {
        $keys 		= array_keys($obj);
        $columns 	= '';
        $values 	= '';
        foreach($column_names as $desired_key) { // Check the recipe received. If blank insert blank into the array.
            if(!in_array($desired_key, $keys)) {
                $$desired_key = '';
            } else {
                $$desired_key = $obj[$desired_key];
            }
            $columns 	= $columns.$desired_key.',';
            $values 	= $values."'".$this->real_escape($$desired_key)."',";
        }
        $query = "INSERT INTO ".$table_name."(".trim($columns,',').") VALUES(".trim($values,',').")";
        //echo "QUERY : ".$query;
        if(!empty($obj)) {
            //$r = $this->mysqli->query($query) or trigger_error($this->mysqli->errog.__LINE__);
            if ($this->mysqli->query($query)) {
                $status = "success";
                $msg 		= $table_name." created successfully";
            } else {
                $status = "failed";
                $msg 		= $this->mysqli->errog.__LINE__;
            }
            $resp = array('status' => $status, "msg" => $msg, "data" => $obj);
            $this->response($this->json($resp),200);
        } else {
            $this->response('',204);	//"No Content" status
        }
    }

    private function post_update($id, $obj, $column_names, $table_name) {
        $keys = array_keys($obj[$table_name]);
        $columns = '';
        $values = '';
        foreach($column_names as $desired_key){ // Check the recipe received. If key does not exist, insert blank into the array.
            if(!in_array($desired_key, $keys)) {
                $$desired_key = '';
            } else {
                $$desired_key = $obj[$table_name][$desired_key];
            }
            $columns = $columns.$desired_key."='".$this->real_escape($$desired_key)."',";
        }

        $query = "UPDATE ".$table_name." SET ".trim($columns,',')." WHERE id=$id";
        if(!empty($obj)) {
            // $r = $this->mysqli->query($query) or die($this->mysqli->errog.__LINE__);
            if ($this->mysqli->query($query)) {
                $status = "success";
                $msg 	= $table_name." update successfully";
            } else {
                $status = "failed";
                $msg 	= $this->mysqli->errog.__LINE__;
            }
            $resp = array('status' => $status, "msg" => $msg, "data" => $obj);
            $this->response($this->json($resp),200);
        } else {
            $this->response('',204);	// "No Content" status
        }
    }

    private function delete_one($id, $table_name) {
        if($id > 0) {
            $query="DELETE FROM ".$table_name." WHERE id = $id";
            if ($this->mysqli->query($query)) {
                $status = "success";
                $msg 		= "One record " .$table_name." successfully deleted";
            } else {
                $status = "failed";
                $msg 		= $this->mysqli->errog.__LINE__;
            }
            $resp = array('status' => $status, "msg" => $msg);
            $this->response($this->json($resp),200);
        } else {
            $this->response('',204);	// If no records "No Content" status
        }
    }

    private function responseInvalidParam() {
        $resp = array("status" => 'Failed', "msg" => 'Invalid Parameter' );
        $this->response($this->json($resp), 200);
    }

    /* ==================================== End of API utilities ==========================================
     * ====================================================================================================
     */

    /* Encode array into JSON */
    private function json($data) {
        if(is_array($data)) {
            // return json_encode($data, JSON_NUMERIC_CHECK);
            return json_encode($data);
        }
    }

    /* String mysqli_real_escape_string */
    private function real_escape($s) {
        return mysqli_real_escape_string($this->mysqli, $s);
    }

    public function get_request_method(){
        return $_SERVER['REQUEST_METHOD'];
    }

    public function response($data,$status){
        $this->_code = ($status)?$status:200;
        $this->set_headers();
        echo $data;
        exit;
    }
    private function set_headers(){
        header("HTTP/1.1 ".$this->_code." ".$this->get_status_message());
        header("Content-Type:".$this->_content_type);
    }

    private function get_status_message(){
        $status = array(
            200 => 'OK',
            201 => 'Created',
            204 => 'No Content',
            404 => 'Not Found',
            406 => 'Not Acceptable',
            401 => 'Unauthorized');
        return ($status[$this->_code])?$status[$this->_code]:$status[500];
    }



}
