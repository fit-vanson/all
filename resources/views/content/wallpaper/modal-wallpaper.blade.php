<!-- Add Modal -->
<div class="modal fade" id="WallpaperModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-transparent">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-sm-5 pb-5">
        <div class="text-center mb-2">
          <h1 class="mb-1 exampleModalLabel">Add New</h1>
        </div>
          <form method="post" action="{{route('wallpaper.create')}}" enctype="multipart/form-data"
                class="dropzone" id="wallpaperForm">
              @csrf
              <div class="col-md-12">
                  <label class="form-label">Category</label>
                  <select class="form-select" id="select_category" name="select_category" required multiple >
                      @foreach($categories as $category)
                          <option value="{{$category->id}}">{{$category->category_name}}</option>
                      @endforeach
                  </select>
              </div>
          </form>
      </div>
    </div>
  </div>
</div>
<!--/ Add  Modal -->

<!-- Edit Modal -->
<div class="modal fade" id="EditWallpaperModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 pb-5">
                <div class="text-center mb-2">
                    <h1 class="mb-1 exampleModalLabel">Edit Wallpaper</h1>
                </div>
                <form class="row" id="editWallpaperForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id_edit" value="">
                    <div class="col-xl-4 col-md-6 col-12">
                        <div class="mb-1">
                            <p class="form-label" for="basicInput">Image Thumbnail</p>
                            <input  id="image_thumbnail" type="file" name="image_thumbnail" class="form-control" hidden accept="image/*" onchange="changeImg_thumbnail(this)">
                            <img id="avatar_thumbnail" class="thumbnail" height="100px" src="{{asset('images/avatars/1.png')}}">
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 col-12">
                        <div class="mb-1">
                            <p class="form-label" for="helpInputTop">Image Detail</p>
                            <input  id="image_detail" type="file" name="image_detail" class="form-control" hidden accept="image/*" onchange="changeImg_detail(this)">
                            <img id="avatar_detail" class="thumbnail" height="100px" src="{{asset('images/avatars/1.png')}}">
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 col-12">
                        <div class="mb-1">
                            <p class="form-label" for="helpInputTop">Image Download</p>
                            <input  id="image_download" type="file" name="image_download" class="form-control" hidden accept="image/*" onchange="changeImg_download(this)">
                            <img id="avatar_download" class="thumbnail" height="100px" src="{{asset('images/avatars/1.png')}}">
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="wallpaper_name"  name="wallpaper_name" />
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="basicInput">View Count</label>
                            <input type="text" class="form-control" id="wallpaper_viewCount"  name="wallpaper_viewCount" />
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="basicInput">Like Count</label>
                            <input type="text" class="form-control" id="wallpaper_likeCount"  name="wallpaper_likeCount" />
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Category</label>
                        <select class="form-select" id="select_category_edit" name="select_category" required >
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->category_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Định dạng</label>
                        <select class="form-select" id="select_wallpaper_image_extension" name="wallpaper_image_extension" required >
                                <option value="image/jpeg">image/jpeg</option>
                                <option value="image/gif">image/gif</option>
                        </select>

                    </div>

                    <div class="col-xl-4 col-md-6 col-12">
                        <div class="d-flex align-items-center mt-1">
                            <div class="form-check form-switch form-check-primary">
                                <input type="checkbox" class="form-check-input" id="feature" name="feature" checked />
                                <label class="form-check-label" for="feature">
                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                </label>
                            </div>
                            <label class="form-check-label fw-bolder" for="feature">Feature</label>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success" id="submitButton" value="update">Update</button>
                        <button type="reset" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ Edit  Modal -->


