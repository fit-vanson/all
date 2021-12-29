<!-- Add Permission Modal -->
<div class="modal fade" id="ApiKeysModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-transparent">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-sm-5 pb-5">
        <div class="text-center mb-2">
          <h1 class="mb-1 apikeyleModalLabel">Add New Api Keys</h1>
        </div>
          <form class="row" id="apiKeysForm" onsubmit="return false">
              <div class="modal-body flex-grow-1">
                  <input type="hidden" name="id" id="id" value="">
                  <div class="mb-1">
                      <label class="form-label" for="basic-icon-default-uname">Key Name</label>
                      <input type="text" id="apikey_name" class="form-control" placeholder="Key Name" name="apikey_name">
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
