<!-- Add Permission Modal -->
<div class="modal fade" id="CategoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-transparent">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-sm-5 pb-5">
        <div class="text-center mb-2">
          <h1 class="mb-1 categoryModalLabel">Add New Category</h1>
        </div>
          <form class="row" id="categoryForm" onsubmit="return false">
              <div class="modal-body flex-grow-1">
                  <input type="hidden" name="id" id="id" value="">
                  <input  id="image" type="file" name="image" class="form-control" hidden accept="image/*" onchange="changeImg(this)">
                  <img id="avatar" class="thumbnail" width="200px" src="{{asset('images/avatars/1.png')}}">
                  <div class="mb-1">
                      <label class="form-label" for="basic-icon-default-uname">Order</label>
                      <input type="text" id="category_order" class="form-control" value="1" name="category_order">
                  </div>
                  <div class="mb-1">
                      <label class="form-label" for="basic-icon-default-uname">Tên Category</label>
                      <input type="text" id="category_name" class="form-control" placeholder="Category Name" name="category_name">
                  </div>
                  <div class="mb-1">
                      <label class="form-label" for="basic-icon-default-uname">View Count</label>
                      <input type="number" id="view_count" class="form-control" placeholder="View Count" name="view_count" >
                  </div>
                  <div class="mb-1">
                      <div class="d-flex align-items-center mt-1">
                          <label class="form-check-label fw-bolder" for="checked_ip">Fake </label>
                          <div class="form-check form-switch form-check-primary">
                              <input type="checkbox" class="form-check-input" id="checked_ip" name="checked_ip" checked />
                              <label class="form-check-label" for="checked_ip">
                                  <span class="switch-icon-left"><i data-feather="check"></i></span>
                                  <span class="switch-icon-right"><i data-feather="x"></i></span>
                              </label>
                          </div>
                          <label class="form-check-label fw-bolder" for="checked_ip"> Real</label>
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
<!--/ Add Permission Modal -->
