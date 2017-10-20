@extends('layouts.admin')

@section('content')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('admin')}}">首页</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>用户管理</span>
            </li>
        </ul>
    </div>
    <!-- END PAGE BAR -->
    <div class="margin-top-40">
        <div class="col-md-12">
            <div class="portlet light portlet-fit portlet-datatable bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject font-dark sbold uppercase">用户列表</span>
                    </div>
                    <div class="actions">
                        <div class="btn-group">
                        </div>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-container">
                        <table class="table table-striped table-bordered table-hover table-checkable"
                               id="datatable_ajax">
                            <thead>
                            <tr role="row" class="heading">
                                <th>#</th>
                                <th width="10%"> 头像</th>
                                <th width="15%"> 用户名</th>
                                <th>参加活动次数</th>
                                <th>发布活动次数</th>
                                <th> 状态</th>
                                <th> 操作</th>
                            </tr>
                            <tr role="row" class="filter">
                                <td></td>
                                <td></td>
                                <td>
                                    <div class="form-group form-md-line-input">
                                        <div class="input-group has-success">
                                    <span class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </span>
                                            <input type="text" class="form-control form-filter" name="name"
                                                   placeholder="name">
                                            <div class="form-control-focus"></div>
                                        </div>
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                                <td>
                                    <div class="form-group form-md-line-input">
                                        <select class="bs-select form-control form-filter bs-select-hidden"
                                                data-show-subtext="true" name="status">
                                            <option value="" data-icon="fa-glass icon-success">全部</option>
                                            <option value="1" data-icon="fa fa-paw"> 正常</option>
                                            <option value="0" data-icon="fa fa-navicon"> 拉黑</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="margin-bottom-5">
                                        <button class="btn btn-sm green btn-outline filter-submit margin-bottom">
                                            <i class="fa fa-search"></i> 查询
                                        </button>
                                    </div>
                                    <button class="btn btn-sm red btn-outline filter-cancel">
                                        <i class="fa fa-times"></i> 重置
                                    </button>
                                </td>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('plugins/datatables/datatables.all.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/user/user-list.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            TableDatatablesAjax.init();

        });
    </script>
@endsection

