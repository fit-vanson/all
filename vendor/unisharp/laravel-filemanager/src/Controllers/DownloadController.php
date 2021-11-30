<?php

namespace UniSharp\LaravelFilemanager\Controllers;

use App\Models\FileManage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use File;

class DownloadController extends LfmController
{
    public function getDownload()
    {
        $download_id = explode(',',request()->download_id);
        $files = FileManage::whereIn('id',$download_id)->get();
        if(count($files)>1){
            $zip      = new ZipArchive;
            $fileName = Auth::user()->name.'_'.time().'.zip';
            if ($zip->open(storage_path('app/public/files/downloads/'.$fileName), ZipArchive::CREATE) === TRUE) {
                foreach ($files as $file){
                    $item = storage_path('app/public/files').$file->dir.'/'.$file->name;
                    $zip->addFile($item, $file->name);
                }
                $zip->close();
            }
            return response()->download(storage_path('app/public/files/downloads/'.$fileName));
        }else{
            $file = $files[0];
            return response()->download(storage_path('app/public/files/'.$file->dir.'/'.$file->name));
        }
    }
}
