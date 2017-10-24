@extends('layouts.admin')

@section('css')
    <style type="text/css">
        #entry_list{overflow: auto; margin: 0; padding: 0}
        li {list-style-type:none;}
        img{border-radius: 50%}
    </style>
@endsection

@section('content')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('admin')}}">首页</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>活动管理</span>
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
                        <span class="caption-subject font-dark sbold uppercase">活动列表</span>
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
                                <th width="20%"> 活动标题</th>
                                <th width="15%"> 发布时间</th>
                                <th>参加人数</th>
                                <th>活动状态</th>
                                <th> 状态</th>
                                <th> 操作</th>
                            </tr>
                            <tr role="row" class="filter">
                                <td></td>
                                <td>
                                    <div class="form-group form-md-line-input">
                                        <div class="input-group has-success">
                                    <span class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </span>
                                            <input type="text" class="form-control form-filter" name="title"
                                                   placeholder="标题">
                                            <div class="form-control-focus"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group date date-picker margin-bottom-5"
                                         data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control form-filter input-sm" readonly
                                               placeholder="From" name="from">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>

                                    <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control form-filter input-sm" readonly
                                               placeholder="To" name="to">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </td>
                                <td></td>
                                <td>
                                    <div class="form-group form-md-line-input">
                                        <select class="bs-select form-control form-filter bs-select-hidden"
                                                data-show-subtext="true" name="status">
                                            <option value="" data-icon="fa-glass icon-success">全部</option>
                                            <option value="1" data-icon="fa fa-paw"> 报名中</option>
                                            <option value="2" data-icon="fa fa-navicon"> 活动中</option>
                                            <option value="3" data-icon="fa fa-navicon"> 已结束</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group form-md-line-input">
                                        <select class="bs-select form-control form-filter bs-select-hidden"
                                                data-show-subtext="true" name="state">
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


    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">活动详情</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-6 col-md-4 col-xs-offset-3 col-md-offset-4 text-center">
                            <img src="" alt="" width="56" id="pic">
                            <p id="user-name"></p>
                        </div>

                        <div class="col-xs-12 col-md-12 col-xs-offset-1 col-md-offset-1">
                            <h4 id="title"></h4>
                        </div>

                        <div class="col-xs-12 col-md-12 col-xs-offset-1 col-md-offset-1">
                            <p id="activity_date"></p>
                            <p id="user_phone"></p>
                            <p id="activity_price"></p>
                            <p id="activity_address"></p>
                        </div>

                        <div class="col-xs-6 col-md-4 col-xs-offset-3 text-center">
                            <h4>活动详情介绍</h4>
                        </div>

                        <div class="col-xs-12 col-md-12  col-xs-offset-1 col-md-offset-1" id="activity_detail">

                        </div>

                        <div class="col-xs-6 col-md-4 col-xs-offset-3 text-center">
                            <h4>活动报名人数</h4>
                            <span id="entry_num"></span>
                        </div>

                        <div class="col-xs-12 col-md-12 col-xs-offset-1 col-md-offset-1" id="activity_detail">
                            <ul id="entry_list">

                            </ul>
                        </div>

                        <div class="col-xs-12 col-md-12 col-xs-offset-1 col-md-offset-1" id="activity_detail">
                            <ul id="images_list">

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('plugins/datatables/datatables.all.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/bootstrap-datepicker/locales/bootstrap-datepicker.zh-CN.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/activity/activity-list.js')}}?v=1236"></script>
    <script type="text/javascript">
        $(function(){
            TableDatatablesAjax.init();
        });
    </script>
@endsection

