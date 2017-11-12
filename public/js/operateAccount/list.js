var TableDatatablesAjax = function () {
    var datatableAjax = function () {
        var update_status_url = '/admin/operate-account/update-status';
        var action = 'create';
        var user_id = 0;

        dt = $('#datatable_ajax');
        ajax_datatable = dt.DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ajax": {
                'url': '/admin/operate-account/ajax',
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
                    "data": "statistics.publish",
                    "name": "statistics.publish",
                    "orderable": false
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
                        var html = [];
                        if (data == 1) {
                            html.push('<button type="button" class="btn btn-xs btn-warning to-black" data-id="'+row.id+'">拉黑</button>');
                        }else{
                            html.push('<button type="button" class="btn btn-xs btn-success recover" data-id="'+row.id+'">恢复</button>');
                        }

                        html.push('<button type="button" class="btn btn-xs btn-primary edit" data-id="'+row.id+'">编辑</button>');

                        return html.join('');
                    }
                }
            ],
            "drawCallback": function (settings) {
                ajax_datatable.$('.tooltips').tooltip({
                    placement: 'top',
                    html: true
                });
            },
            language: dataTable_language,
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

                ajax(update_status_url, { "status": 1, "user_id": user_id}, 'PUT', function(){
                    layer.close(index);
                    ajax_datatable.ajax.reload();
                });

            }, function(){

            });
        });

        dt.on('click', '.edit', function () {
            action = 'edit';
            user_id = $(this).attr('data-id');


            ajax('/admin/operate-account/'+user_id, {}, 'GET', function (result) {
                if (result.code != 200) {
                    layer.alert(result.message);
                }else{

                    $('#show-img').attr('src', result.data.avatar_url);
                    $('input[name=avatar_url]').val(result.data.avatar_url);
                    $('#name').val(result.data.name);
                    $('#createModel').modal('show');
                }

            })
        });

        dt.on('click', '.to-black', function () {
            var user_id = $(this).attr('data-id');

            var index = layer.confirm('确定要拉黑我吗?', {
                icon: 5,
                title: '提示',
                btn: ['去死吧','我错了'] //按钮
            }, function(){

                ajax(update_status_url, { "status": 0, "user_id": user_id}, 'PUT', function(){
                    layer.close(index);
                    ajax_datatable.ajax.reload();
                });
            }, function(){

            });
        });

        $('#create_operate_account').on('click', function () {
            action = 'create';
            user_id = 0;
            $('#createModel').modal('show');
        });

        $('#avatar').on('change', function () {
            var formData = new FormData();
            formData.append('file', $('#avatar')[0].files[0]);
            $.ajax({
                url: '/admin/upload/img',
                type: 'POST',
                cache: false,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                processData: false,
                contentType: false
            }).done(function(res) {
                if (res.code != 200) {
                    layer.alert(res.message);
                }else{
                    $('#show-img').attr('src', res.data.url);
                    $('input[name=avatar_url]').val(res.data.url);
                }
            }).fail(function(res) {
                layer.alert('操作失败')
            });
        });

        $('.save-account').on('click', function () {
            var formData = {};
            formData['avatar_url'] = $('input[name=avatar_url]').val();
            formData['name'] = $('#name').val();

            var url = action == 'create' ? '/admin/operate-account': '/admin/operate-account/'+user_id;
            var method = action == 'create' ? 'POST': 'PUT';

            ajax(url, formData, method, function (result) {
                if (result.code != 200) {

                    for (var item in result.errors ){
                        layer.alert(result['errors'][item][0]);
                        break;
                    }

                }else{
                    $('#createModel').modal('hide');
                    ajax_datatable.ajax.reload();
                    $('#show-img').attr('src', '');
                    $('input[name=avatar_url]').val('');
                }
            }, function (result) {
                layer.alert('操作失败')
            });

        })
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
            layer.close(index);
            success_callback(data, textStatus, jqXHR);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            layer.close(index);
            fail_callback(jqXHR, textStatus, errorThrown);
        }).always(function() {
            layer.close(index);
        });
    }


    return {
        init: datatableAjax
    }
}();