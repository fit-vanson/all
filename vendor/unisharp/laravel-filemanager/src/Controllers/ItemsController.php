<?php

namespace UniSharp\LaravelFilemanager\Controllers;

use App\Models\FileManage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use UniSharp\LaravelFilemanager\Events\FileIsMoving;
use UniSharp\LaravelFilemanager\Events\FileWasMoving;
use UniSharp\LaravelFilemanager\Events\FolderIsMoving;
use UniSharp\LaravelFilemanager\Events\FolderWasMoving;

class ItemsController extends LfmController
{
    /**
     * Get the images to load for a selected folder.
     *
     * @return mixed
     *
     */

    public function getItems()
    {
        $currentPage = self::getCurrentPageFromRequest();
        $keyword = request()->keyword;

        $perPage = $this->helper->getPaginationPerPage();
        $items = array_merge($this->lfm->folders(), $this->lfm->files());
        $arr_items = array_map(function ($item) {
            $file = FileManage::where('name',$item->fill()->attributes['name'])->where('dir',request()->working_dir)->where('url',$item->fill()->attributes['url'])->first();
            if($file){
                FileManage::updateOrCreate(
                    [
                        'id'=>$file->id,
                        'name'=>$file->name,
                        'dir'=>$file->dir,
                        'url'=>$file->url,
                    ],
                    [
                        'icon'=>$item->fill()->attributes['icon'],
                        'is_file'=>$item->fill()->attributes['is_file'],
                        'is_image'=>$item->fill()->attributes['is_image'],
                        'thumb_url'=>$item->fill()->attributes['thumb_url'],
                        'time'=>$item->fill()->attributes['time'],
                    ]
                );
                $file= $file->toArray();
            }else{
                $file=[];
            }
            return array_merge($item->fill()->attributes,$file);
        }, array_slice($items, ($currentPage - 1) * $perPage, $perPage));
        if($keyword != null) {
            $arr_items = FileManage::where('name', 'like', '%' . $keyword . '%')->orwhere('tags', 'like', '%' . $keyword . '%')->get()->toArray();
            $arr_items = array_slice($arr_items, ($currentPage - 1) * $perPage, $perPage);
        }

        return [
            'items' => $arr_items,
            'paginator' => [
                'current_page' => $currentPage,
                'total' => count($items),
                'per_page' => $perPage,
            ],
            'display' => $this->helper->getDisplayMode(),
            'working_dir' => $this->lfm->path('working_dir'),
        ];
    }

    public function like_match($pattern, $subject)
    {
        $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
        return (bool) preg_match("/^{$pattern}$/i", $subject);
    }

    public function move()
    {
        $items = request('items');
        $folder_types = array_filter(['user', 'share'], function ($type) {
            return $this->helper->allowFolderType($type);
        });
        return view('laravel-filemanager::move')
            ->with([
                'root_folders' => array_map(function ($type) use ($folder_types) {
                    $path = $this->lfm->dir($this->helper->getRootFolder($type));

                    return (object) [
                        'name' => trans('laravel-filemanager::lfm.title-' . $type),
                        'url' => $path->path('working_dir'),
                        'children' => $path->folders(),
                        'has_next' => ! ($type == end($folder_types)),
                    ];
                }, $folder_types),
            ])
            ->with('items', $items);
    }

    public function domove()
    {
        $target = $this->helper->input('goToFolder');
        $items = $this->helper->input('items');

        foreach ($items as $item) {
            $old_file = $this->lfm->pretty($item);
            $is_directory = $old_file->isDirectory();

            $file = $this->lfm->setName($item);

            if (!Storage::disk($this->helper->config('disk'))->exists($file->path('storage'))) {
                abort(404);
            }

            $old_path = $old_file->path();

            if ($old_file->hasThumb()) {
                $new_file = $this->lfm->setName($item)->thumb()->dir($target);
                if ($is_directory) {
                    event(new FolderIsMoving($old_file->path(), $new_file->path()));
                } else {
                    event(new FileIsMoving($old_file->path(), $new_file->path()));
                }
                $this->lfm->setName($item)->thumb()->move($new_file);
            }
            $new_file = $this->lfm->setName($item)->dir($target);
            $this->lfm->setName($item)->move($new_file);
            if ($is_directory) {
                event(new FolderWasMoving($old_path, $new_file->path()));
            } else {
                event(new FileWasMoving($old_path, $new_file->path()));
            }
        };

        return parent::$success_response;
    }

    private static function getCurrentPageFromRequest()
    {
        $currentPage = (int) request()->get('page', 1);
        $currentPage = $currentPage < 1 ? 1 : $currentPage;

        return $currentPage;
    }
}
