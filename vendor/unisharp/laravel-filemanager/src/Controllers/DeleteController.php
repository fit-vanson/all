<?php

namespace UniSharp\LaravelFilemanager\Controllers;

use App\Models\FileManage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use UniSharp\LaravelFilemanager\Events\ImageIsDeleting;
use UniSharp\LaravelFilemanager\Events\ImageWasDeleted;
use function GuzzleHttp\Promise\all;

class DeleteController extends LfmController
{
    /**
     * Delete image and associated thumbnail.
     *
     * @return mixed
     */
    public function getDelete()
    {
        $item_names = request('items');
        $id = request('id');
        $errors = [];
        foreach ($item_names as $name_to_delete) {
            $file = $this->lfm->setName($name_to_delete);
            if (!Storage::disk($this->helper->config('disk'))->exists($file->path('storage'))) {
                return '<div style="color: red">Lỗi!</div>';
            }

            $file_to_delete = $this->lfm->pretty($name_to_delete);
            $file_path = $file_to_delete->path();

            event(new ImageIsDeleting($file_path));

            if (is_null($name_to_delete)) {
                array_push($errors, parent::error('folder-name'));
                continue;
            }

            if (! $this->lfm->setName($name_to_delete)->exists()) {
                array_push($errors, parent::error('folder-not-found', ['folder' => $file_path]));
                continue;
            }

            if ($this->lfm->setName($name_to_delete)->isDirectory()) {
                if (! $this->lfm->setName($name_to_delete)->directoryIsEmpty()) {
                    try{
                        FileManage::where('dir',request()->working_dir.'/'.$name_to_delete)->delete();
                    } catch(Exception $e) {
                        Log::error($e->getMessage(), [
                            'delele dir'=>  $e->getMessage(),
                        ]);
                    }
//                    array_push($errors, parent::error('delete-folder'));
//                    continue;
                }
            } else {
                if ($file_to_delete->isImage()) {
                    $this->lfm->setName($name_to_delete)->thumb()->delete();
                }
                try{
                    FileManage::whereIn('id',$id)->delete();
                } catch(Exception $e) {
                    Log::error($e->getMessage(), [
                        'delele file'=>  $e->getMessage(),
                    ]);
                }
            }
            $this->lfm->setName($name_to_delete)->delete();

            event(new ImageWasDeleted($file_path));
        }

        if (count($errors) > 0) {
            return $errors;
        }

        return parent::$success_response;
    }
}
