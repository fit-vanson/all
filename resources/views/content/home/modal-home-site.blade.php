<!-- Add Permission Modal -->
<div class="modal fade" id="HomeSiteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-transparent">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-sm-5 pb-5">
        <div class="text-center mb-2">
          <h1 class="mb-1 homeSiteModalLabel"></h1>
        </div>
          <form class="row" id="HomeSiteForm" onsubmit="return false">
              <div class="modal-body flex-grow-1">
                  <input type="hidden" name="id" id="id" value="">


                  <input  id="header_image" type="file" name="header_image" class="form-control" hidden accept="image/*" onchange="changeImg(this)">
                  <img id="logo" class="thumbnail" width="200px" src="../images/avatars/1.png">
                  <div class="mb-1">
                      <label class="form-label" >Site</label>
                      <select class="form-control" id="select_site" name="select_site" required>
                          @foreach($sites as $site)
                              <option value="{{$site->id}}">{{$site->site_name}}</option>
                          @endforeach
                      </select>
                  </div>

                  <div class="mb-1">
                      <label class="form-label" for="basic-icon-default-uname">Header Title</label>
                      <input type="text" id="header_title" class="form-control" placeholder="Header Title" name="header_title">
                  </div>
                  <div class="mb-1">
                      <label class="form-label">Header Content</label>
                      <textarea class="form-control" id="header_content" name="header_content" rows="3" placeholder="Header Content"></textarea>
                  </div>
                  <div class="mb-1">
                      <label class="form-label" for="basic-icon-default-uname">Body Title</label>
                      <input type="text" id="body_title" class="form-control" placeholder="Body Title" name="body_title">
                  </div>
                  <div class="mb-1">
                      <label class="form-label">Body Content</label>
                      <textarea class="form-control" id="body_content" name="body_content" rows="3" placeholder="Body Content"></textarea>
                  </div>
                  <div class="mb-1">
                      <label class="form-label" for="basic-icon-default-uname">Footer Title</label>
                      <input type="text" id="footer_title" class="form-control" placeholder="Footer Title" name="footer_title">
                  </div>
                  <div class="mb-1">
                      <label class="form-label">Footer Content</label>
                      <textarea class="form-control" id="footer_content" name="footer_content" rows="3" placeholder="Footer Content"></textarea>
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
