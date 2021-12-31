<!-- Add Site Modal -->
<div class="modal fade" id="SitesModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-transparent">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-sm-5 pb-5">
        <div class="text-center mb-2">
          <h1 class="mb-1 siteModalLabel"></h1>
        </div>
          <form class="row" id="siteForm" onsubmit="return false" enctype="multipart/form-data">
              <div class="modal-body flex-grow-1">
                  <input type="hidden" name="id" id="id" value="">

                  <input  id="image_logo" type="file" name="image_logo" class="form-control" hidden accept="image/*" onchange="changeImg(this)">
                  <img id="logo" class="thumbnail" width="200px" src="{{asset('images/avatars/1.png')}}">

                  <div class="mb-1">
                      <label class="form-label" >Tên site</label>
                      <input type="text" id="site_name" class="form-control" placeholder="Site Name" name="site_name">
                  </div>
                  <div class="mb-1">
                      <label class="form-label" >Category</label>
                      <div class="row">
                          <div class="col-10">
                              <select class="form-control" id="select_category" name="select_category[]" multiple required>
                                  @foreach($categories as $category)
                                      <option value="{{$category->id}}">{{$category->category_name}}</option>
                                  @endforeach
                              </select>
                          </div>
                          <div class="col-2">
                              <a data-bs-toggle="modal" href="#CategoryModal" class="btn btn-secondary">...</a>
                          </div>
                      </div>

                  </div>
                  <button type="submit" class="btn btn-primary" id="submitButton" value="create">Create</button>
                  <button type="reset" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal">Cancel</button>
              </div>
          </form>
      </div>
    </div>
  </div>
</div>
<!--/ Add Site Modal -->
