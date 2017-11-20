@extends('layouts.admin')

@section('css')
    <style type="text/css">
        #entry_list{overflow: auto; margin: 0; padding: 0}
        li {list-style-type:none;}
        #pic, #entry_list img{border-radius: 50%}
    </style>

    <link href="{{asset('plugins/ueditor/themes/default/css/umeditor.css')}}" type="text/css" rel="stylesheet">
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
                            <button type="button" class="btn btn-success activity-create">创建官方活动</button>
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
    @include('admin.activity.detail')
    @include('admin.activity.create')
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('plugins/datatables/datatables.all.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/bootstrap-datepicker/locales/bootstrap-datepicker.zh-CN.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/ueditor/template.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/ueditor/umeditor.config.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/ueditor/umeditor.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/ueditor/lang/zh-cn/zh-cn.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/activity/activity-list.js')}}?v=1"></script>
    <script type="text/javascript">
        $(function(){
            TableDatatablesAjax.init();
        });
    </script>
@endsection

