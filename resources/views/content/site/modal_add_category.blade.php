<!-- Edit Permission Modal -->
<div class="modal fade" id="AddSiteCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 pt-0">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Add Site Category</h1>

                </div>
                <form class="row" id="AddCategorySiteForm" onsubmit="return false">
                    <div class="modal-body flex-grow-1">
                        <input type="hidden" name="id_site" id="id_site" value="">
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
                        <button type="submit" class="btn btn-success" id="submitButton_ed" value="update">Update</button>
                        <button type="reset" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ Edit Permission Modal -->
