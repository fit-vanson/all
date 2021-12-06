<?php

namespace UniSharp\LaravelFilemanager\Controllers;

use App\Models\FileManage;
use App\Models\Tags;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use UniSharp\LaravelFilemanager\Events\ImageIsUploading;
use UniSharp\LaravelFilemanager\Events\ImageWasUploaded;
use UniSharp\LaravelFilemanager\Lfm;

class UploadController extends LfmController
{
    protected $errors;

    public function __construct()
    {
        parent::__construct();
        $this->errors = [];
    }

    /**
     * Upload files
     *
     * @param void
     *
     * @return JsonResponse
     */
    public function upload()
    {
        if(request()->tags_name){
            $tags_name = json_decode(request()->tags_name,true);
            foreach ($tags_name as $value){
                $temp[] = $value['value'];
                Tags::updateOrCreate(
                    [
                        'tags_name'=>$value['value'],
                    ]
                );
            }
            $tags = implode(',',$temp);
        }else{
            $tags = '';
        }

        $dir = request()->working_dir;
        $uploaded_files = request()->file('upload');
        $error_bag = [];
        $new_filename = null;

        foreach (is_array($uploaded_files) ? $uploaded_files : [$uploaded_files] as $file) {
            try {
                $new_filename = $this->lfm->upload($file);
                $url = $this->lfm->setName($new_filename)->url();
                FileManage::updateOrCreate(
                    [
                        'name'=>$new_filename,
                        'ext'=>$file->extension(),
                        'file_size'=>$file->getSize(),
                        'user_id'=>Auth::id(),
                        'url'=>$url,
                        'dir'=>$dir,
                        'tags'=>$tags,
                    ]
                );
            } catch (\Exception $e) {
                Log::error($e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                array_push($error_bag, $e->getMessage());
            }
        }


        if (is_array($uploaded_files)) {
            $response = count($error_bag) > 0 ? $error_bag : parent::$success_response;
        } else { // upload via ckeditor5 expects json responses
            if (is_null($new_filename)) {
                $response = [
                    'error' => [ 'message' =>  $error_bag[0] ]
                ];
            } else {
                $url = $this->lfm->setName($new_filename)->url();
                $response = [
                    'url' => $url,
                    'uploaded' => $url
                ];
            }
        }

        return response()->json($response);
    }
}
