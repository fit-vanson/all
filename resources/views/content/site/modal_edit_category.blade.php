<!-- Edit Permission Modal -->
<div class="modal fade" id="EditSiteCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 pt-0">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Edit Site Category</h1>

                </div>
                <form class="row" id="EditCategoryForm" onsubmit="return false">
                    <div class="modal-body flex-grow-1">
                        <input type="hidden" name="id" id="id" value="">
                        <input  id="image_edit" type="file" name="image" class="form-control" hidden accept="image/*" onchange="changeImg(this)">
                        <img id="avatar_edit" class="thumbnail" width="300px" src="">
                        <div class="mb-1">
                            <label class="form-label" for="basic-icon-default-uname">Tên Category</label>
                            <input type="text" id="category_site_name_edit" class="form-control" placeholder="Category Name" disabled name="category_site_name_edit">
                        </div>
                        <button type="submit" class="btn btn-success" id="submitButton_ed" value="update">Update</button>
                        <button type="reset" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ Edit Permission Modal -->
