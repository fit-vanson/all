<?php

namespace UniSharp\LaravelFilemanager\Controllers;

use App\Models\FileManage;
use App\Models\Tags;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use UniSharp\LaravelFilemanager\Events\FolderIsRenaming;
use UniSharp\LaravelFilemanager\Events\FolderWasRenamed;
use UniSharp\LaravelFilemanager\Events\ImageIsRenaming;
use UniSharp\LaravelFilemanager\Events\ImageWasRenamed;

class RenameController extends LfmController
{
    public function getRename()
    {
        $old_name = $this->helper->input('file');
        $new_name = $this->helper->input('new_name');
        if(request()->new_tags){
            $new_tags = json_decode(request()->new_tags,true);
            foreach ($new_tags as $value){
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

        $file = $this->lfm->setName($old_name);

        if (!Storage::disk($this->helper->config('disk'))->exists($file->path('storage'))) {
//            abort(404);
            return '<div style="color: red">Lỗi!</div>';
        }
//        dd($new_name, $old_name);



        $old_file = $this->lfm->pretty($old_name);
//        $is_directory = $old_file->isDirectory();
        $is_file = request()->type;
        if (empty($new_name)) {
            if ($is_file === 'false') {
                return '<div style="color: red">Tên thư mục để trống</div>';
            } else {
                return '<div style="color: red">Tên không để trống</div>';
            }
        }

        if (config('lfm.alphanumeric_directory') && preg_match('/[^\w-]/i', $new_name)) {
            return parent::error('folder-alnum');
        // return parent::error('file-alnum');
        } elseif ($this->lfm->setName($new_name)->exists()) {
            if ($new_name == $old_name){
                if(request()->new_tags){
                    if(Auth()->id() ==FileManage::where('id',(int)request()->id)->first()->user_id || Auth::user()->hasRole('Admin')){
                        $rename = FileManage::where('id',(int)request()->id)->first();

                        FileManage::updateOrCreate(
                            [
                                'id'=>$rename->id
                            ],
                            [
                                'tags'=>$tags,
                            ]
                        );
                    }
                    else{
                        return '<div style="color: red">Không có quyền đổi!</div>';
                    }
                }
                return '<div style="color: #3ae814">Cập nhật thành công</div>';
            }
            return '<div style="color: red">Tên đã tồn tại</div>';
        }

        if ($is_file=== 'true') {
            $extension = $old_file->extension();
            if ($extension) {
                $new_name = str_replace('.' . $extension, '', $new_name) . '.' . $extension;
            }
        }

        $new_file = $this->lfm->setName($new_name)->path('absolute');
        if ($is_file === 'false') {
            dd('Liên hệ quản trị viên!');
            $dir = FileManage::where('dir',request()->working_dir.'/'.request()->file)->get();
            foreach ($dir as $item){
                FileManage::updateOrCreate(
                    [
                        'id'=>$item->id
                    ],
                    [
                        'dir'=>request()->working_dir.'/'.request()->new_name,
                    ]
                );
            }
            event(new FolderIsRenaming($old_file->path(), $new_file));
        } else {
            if(Auth()->id() ==FileManage::where('id',(int)request()->id)->first()->user_id || Auth::user()->hasRole('Admin')){
                $rename = FileManage::where('id',(int)request()->id)->first();
                event(new ImageIsRenaming($old_file->path(), $new_file));
//                event(new FileIsRenaming($old_file->path(), $new_file));
                FileManage::updateOrCreate(
                    [
                        'id'=>$rename->id
                    ],
                    [
                        'name'=>$new_name,
                        'tags'=>$tags,
                    ]
                );
//                return '<div style="color: #3ae814">Cập nhật thành công</div>';
            }else{
                return '<div style="color: red">Không có quyền đổi!</div>';
            }

        }

        $old_path = $old_file->path();

        if ($old_file->hasThumb()) {
            $this->lfm->setName($old_name)->thumb()
                ->move($this->lfm->setName($new_name)->thumb());
        }

        $this->lfm->setName($old_name)
            ->move($this->lfm->setName($new_name));

        if ($is_file === 'false') {
            event(new FolderWasRenamed($old_path, $new_file));
        } else {
            event(new ImageWasRenamed($old_path, $new_file));
        }

        return parent::$success_response;
    }
}
