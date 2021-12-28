<!-- Edit Permission Modal -->
<div class="modal fade" id="addSiteBlockIpsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 pt-0">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Add Block Ips</h1>

                </div>
                <form class="row" id="addBlockIpsSiteForm" onsubmit="return false">
                    <div class="modal-body flex-grow-1">

                        <input type="hidden" name="id_site" id="id_site">
                        <div class="mb-1">
                            <label class="form-label" for="basic-icon-default-uname">Block Ips</label>
{{--                            <input type="text" id="block_ips_site" class="form-control" placeholder="Category Name" disabled name="block_ips_site">--}}
{{--                            <input type="text" id="block_ips_site" class="form-control" placeholder="Category Name" disabled name="block_ips_site">--}}
                            <select class="form-select" id="block_ips_site" name="block_ips_site[]" multiple>
                                @foreach($blockIps as $blockIp)
                                    <option value="{{$blockIp->id}}">{{$blockIp->ip_address}}</option>
                                @endforeach
                            </select>
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
