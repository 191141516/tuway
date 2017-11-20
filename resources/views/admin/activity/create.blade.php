<div class="modal fade" id="createModel" tabindex="-1" role="dialog" aria-labelledby="createModelLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">创建官方活动</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="publish-account" class="control-label">发布帐号:</label>
                        <select name="publish_account_id" class="form-control" id="publish-account">
                            <option value="">请选择</option>
                            @foreach ($accounts as $account)
                                <option value="{{$account->id}}">{{$account->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">详情:</label>

                        <script type="text/plain" id="myEditor">
                            <p>活动详情</p>
                        </script>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary">保存</button>
            </div>
        </div>
    </div>
</div>