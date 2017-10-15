var TableDatatablesAjax = function () {
    var datatableAjax = function () {
        var updateState_url = "/admin/activity/update-state";
        var url = '/admin/activity/';

        dt = $('#datatable_ajax');
        ajax_datatable = dt.DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ajax": {
                'url': '/admin/activity/ajax',
                "data": function (d) {
                    d.title = $('.filter input[name="title"]').val();
                    d.status = $('.filter select[name="state"] option:selected').val();
                    d.from = $('.filter select[name="from"] option:selected').val();
                    d.to = $('.filter select[name="to"] option:selected').val();
                }
            },
            "pagingType": "bootstrap_full_number",
            "order": [],
            "orderCellsTop": true,
            "dom": "<'row'<'col-sm-3'l><'col-sm-6'<'customtoolbar'>><'col-sm-3'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            "columns": [
                {
                    "data": "id",
                    "name": "id"
                },
                {
                    "data": "title",
                    "name": "title",
                    "orderable": false
                },
                {
                    "data": "created_at",
                    "name": "created_at"
                },
                {
                    "data": "total",
                    "name": "total"
                },
                {
                    "data": "state",
                    "name": "state",
                    "orderable": false,
                    render: function (data) {
                        if (data == 1) {
                            return '<span class="label label-success"> 正常 </span>';
                        } else {
                            return '<span class="label label-warning"> 拉黑 </span>';
                        }
                    }
                },
                {
                    "data": "state",
                    "orderable": false,
                    render: function (data, display, row) {
                        var html = [];

                        html.push('<button type="button" class="btn btn-xs btn-info detail" data-id="'+row.id+'">详情</button>');

                        if (data == 1) {
                            html.push('<button type="button" class="btn btn-xs btn-warning to-black" data-id="'+row.id+'">拉黑</button>');
                        }else{
                            html.push('<button type="button" class="btn btn-xs btn-success recover" data-id="'+row.id+'">恢复</button>');
                        }

                        html.push('<button type="button" class="btn btn-xs btn-danger del" data-id="'+row.id+'">删除</button>');

                        return html.join('');
                    }
                }
            ],
            "drawCallback": function (settings) {
                ajax_datatable.$('.tooltips').tooltip({
                    placement: 'top',
                    html: true
                });
            }
        });

        dt.on('click', '.filter-submit', function () {
            ajax_datatable.ajax.reload();
        });

        dt.on('click', '.filter-cancel', function () {
            $('textarea.form-filter, select.form-filter, input.form-filter', dt).each(function () {
                $(this).val("");
            });

            $('select.form-filter').selectpicker('refresh');

            $('input.form-filter[type="checkbox"]', dt).each(function () {
                $(this).attr("checked", false);
            });
            ajax_datatable.ajax.reload();
        });

        dt.on('click', '.recover', function () {
            var activity_id = $(this).attr('data-id');

            var index = layer.confirm('你还是爱我的', {
                icon: 6,
                title: '提示',
                btn: ['回来吧!亲','想多了!'] //按钮
            }, function(){
                var data = {
                    "state": 1,
                    "activity_id": activity_id
                };
                ajax(updateState_url, data, 'PUT', function () {
                    layer.close(index);
                    ajax_datatable.ajax.reload();
                });

            }, function(){

            });
        });

        dt.on('click', '.to-black', function () {
            var activity_id = $(this).attr('data-id');

            var index = layer.confirm('确定要拉黑我吗?', {
                icon: 5,
                title: '提示',
                btn: ['去死吧','我错了'] //按钮
            }, function(){
                var data = {
                    "state": 0,
                    "activity_id": activity_id
                };
                ajax(updateState_url, data, 'PUT', function () {
                    layer.close(index);
                    ajax_datatable.ajax.reload();
                });

            }, function(){

            });
        });

        $('.input-group.date').datepicker({
            autoclose: true
        });

        dt.on('click', '.del', function(){
            var activity_id = $(this).attr('data-id');

            var index = layer.confirm('我们分手吧!', {
                icon: 5,
                title: '提示',
                btn: ['成全你','我错了'] //按钮
            }, function(){

                ajax(url+activity_id, {}, 'DELETE', function(){
                    layer.close(index);
                    ajax_datatable.ajax.reload();
                });

            }, function(){

            });
        });


        dt.on('click', '.detail', function(){
            var activity_id = $(this).attr('data-id');

            ajax(url+activity_id, {}, 'GET', function(data, textStatus, jqXHR){
                var activity = data.data;

                $('#pic').attr('src', activity.user.avatar_url);
                $('#user-name').text(activity.user.name);
                $('#title').text(activity.title);
                $('#activity_date').text(activity.start_date+'至'+activity.end_date);
                $('#user_phone').text(activity.phone);
                $('#activity_price').text(activity.price);
                $('#activity_address').text(activity.address);
                $('#activity_detail').html(activity.content);
                $('#entry_num').text(activity.num +'/'+activity.total);

                var entry_list = activity.entry_user || [];
                var len = entry_list.length;
                var html = [];

                for (var i = 0; i < len; i++) {
                    html.push('<li class="pull-left"><img src="'+entry_list[i]+'" alt="" width="28"></li>');
                }

                $('#entry_list').html(html.join(''));
                $('#myModal').modal('show');
            });
        });
    };

    function ajax(url, data, method) {
        var success_callback = arguments[3] || function(){};
        var fail_callback = arguments[4] || function(){};

        var index = layer.load(0, {
            shade: [0.4,'#B3B3B3'] //0.1透明度的白色背景
        });

        $.ajax({
            url: url,
            type: method,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            data:data,
        }).done(function(data, textStatus, jqXHR) {
            success_callback(data, textStatus, jqXHR);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            fail_callback(jqXHR, textStatus, errorThrown);
        }).always(function() {
            layer.close(index);
        });
    }


    return {
        init: datatableAjax
    }
}();