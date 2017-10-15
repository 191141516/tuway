var TableDatatablesAjax = function () {
    var datatableAjax = function () {
        dt = $('#datatable_ajax');
        ajax_datatable = dt.DataTable({
            "processing": true,
            "language": {
                "loadingRecords": "请等待，数据正在加载中......"
            },
            "serverSide": true,
            "searching": false,
            "ajax": {
                'url': '/admin/user/ajax',
                "data": function (d) {
                    d.name = $('.filter input[name="name"]').val();
                    d.status = $('.filter select[name="status"] option:selected').val();
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
                    "data": "avatar_url",
                    "name": "avatar_url",
                    "orderable": false,
                    render: function (data) {
                        return '<img src="'+data+'" width="30" />';
                    }
                },
                {
                    "data": "name",
                    "name": "name",
                    "orderable": false
                },
                {
                    "data": "statistics.join",
                    "name": "statistics.join"
                },
                {
                    "data": "statistics.publish",
                    "name": "statistics.publish"
                },
                {
                    "data": "status",
                    "name": "status",
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
                    "data": "status",
                    "orderable": false,
                    render: function (data, display, row) {
                        if (data == 1) {
                            return '<button type="button" class="btn btn-xs btn-warning to-black" data-id="'+row.id+'">拉黑</button>';
                        }else{
                            return '<button type="button" class="btn btn-xs btn-success recover" data-id="'+row.id+'">恢复</button>';
                        }
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
            var user_id = $(this).attr('data-id');

            var index = layer.confirm('你还是爱我的', {
                icon: 6,
                title: '提示',
                btn: ['回来吧!亲','想多了!'] //按钮
            }, function(){
                updateStatus(user_id, 1);
                layer.close(index);

                ajax_datatable.ajax.reload();
            }, function(){

            });
        });

        dt.on('click', '.to-black', function () {
            var user_id = $(this).attr('data-id');

            var index = layer.confirm('确定要拉黑我吗?', {
                icon: 5,
                title: '提示',
                btn: ['去死吧','我错了'] //按钮
            }, function(){
                updateStatus(user_id, 0);
                layer.close(index);

                ajax_datatable.ajax.reload();
            }, function(){

            });
        });
    };

    function updateStatus(user_id, status) {
        var index = layer.load(0, {
            shade: [0.4,'#B3B3B3'] //0.1透明度的白色背景
        });

        $.ajax({
            url: "/admin/user/update-status",
            type: "PUT",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            data:{
                "status": status,
                "user_id": user_id
            }
        }).done(function(data, textStatus, jqXHR) {

        }).fail(function(jqXHR, textStatus, errorThrown) {

        }).always(function() {
            layer.close(index);
        });
    }


    return {
        init: datatableAjax
    }
}();