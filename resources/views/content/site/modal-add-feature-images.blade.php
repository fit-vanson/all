<!-- Add Modal -->
<div class="modal fade" id="addFeatureImagesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 pb-5">
                <div class="text-center mb-2">
                    <h1 class="mb-1 addFeatureImagesModalLabel">Add New</h1>
                </div>
                <form method="post" action="{{$_SERVER['REQUEST_URI']}}/create" enctype="multipart/form-data"
                      class="dropzone" id="addFeatureImagesForm">
                    @csrf
                    <div class="col-md-12">
                        <input type="hidden" name="id_site" id="id_site" value="{{$site->id}}">

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ Add  Modal -->

<!-- Edit Modal -->
<div class="modal fade" id="editFeatureImagesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 pb-5">
                <div class="text-center mb-2">
                    <h1 class="mb-1 editFeatureImagesModalLabel">Edit Feature Image</h1>
                </div>
                <form class="row" id="editFeatureImagesForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id_edit" value="">
                    <div class="mb-1">
                        <p class="form-label" for="basicInput">Feature Image</p>
                        <input  id="image" type="file" name="image" class="form-control" hidden accept="image/*" onchange="changeImg(this)">
                        <img id="avatar" class="thumbnail" height="200px" >
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" id="feature_image_name"  name="feature_image_name" />
                    </div>
                    <div class="mb-1">
                        <select class="form-select" id="site_id" name="site_id" >
                            @foreach($sites as $item)

                                <option value="{{$item->id}}">{{$item->site_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success" id="submitButton_edit" value="update">Update</button>
                        <button type="reset" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ Edit  Modal -->


