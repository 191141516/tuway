<div class="modal fade" id="createModel" tabindex="-1" role="dialog" aria-labelledby="createModelLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">创建官方用户</h4>
            </div>
            <div class="modal-body">
                <form id="form-operate-account">
                    <div class="form-group row">
                        <div class="col-md-2 col-md-offset-5">
                            <img src="" id="show-img" onclick="document.querySelector('#avatar').click()" alt="请上传头像" width="58" height="58">
                        </div>
                        <input type="hidden" value="" name="avatar_url">
                        <input type="file" id="avatar" required accept="image/png, image/jpeg, image/jpg" class="form-control hidden">
                    </div>

                    <div class="form-group">
                        <label for="name" class="control-label">帐号名:</label>
                        <input type="text" required name="name" id="name" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary save-account">保存</button>
            </div>
        </div>
    </div>
</div>