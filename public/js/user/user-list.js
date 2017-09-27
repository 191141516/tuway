var TableDatatablesAjax = function() {
  var datatableAjax = function(){
    dt = $('#datatable_ajax');
    ajax_datatable = dt.DataTable({
      "processing": true,
      "serverSide": true,
      "searching" : false,
      "ajax": {
        'url' : '/admin/user/ajaxIndex',
        "data": function ( d ) {
          d.name = $('.filter input[name="name"]').val();
          d.tel = $('.filter input[name="tel"]').val();
          d.email = $('.filter input[name="email"]').val();
          d.status = $('.filter select[name="status"] option:selected').val();
          d.created_at_from = $('.filter input[name="created_at_from"]').val();
          d.created_at_to = $('.filter input[name="created_at_to"]').val();
          d.updated_at_from = $('.filter input[name="updated_at_from"]').val();
          d.updated_at_to = $('.filter input[name="updated_at_to"]').val();
        }
      },
      "pagingType": "bootstrap_full_number",
      "order" : [],
      "orderCellsTop": true,
      "dom" : "<'row'<'col-sm-3'l><'col-sm-6'<'customtoolbar'>><'col-sm-3'f>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-5'i><'col-sm-7'p>>",
      "columns": [
        {
          "data": "id",
          "name" : "id",
        },
        {
          "data": "name",
          "name" : "name",
          "orderable" : false,
        },
        {
            "data": "company.name",
            "name" : "company.name",
            "orderable" : false,
        },
        {
            "data": "tel",
            "name" : "tel",
            "orderable" : false,
        },
        {
          "data": "email",
          "name": "email",
          "orderable" : false,
        },
        {
          "data": "status",
          "name": "status",
          "orderable" : false,
          render:function(data){
            if (data == 1) {
              return '<span class="label label-success"> 验证 </span>';
            }else{
              return '<span class="label label-warning"> 未验证 </span>';
            }
          }
        },
        {
          "data": "created_at",
          "name": "created_at",
          "orderable" : true,
        },
        {
          "data": "updated_at",
          "name": "updated_at",
          "orderable" : true,
        },
        {
          "data": "actionButton",
          "name": "actionButton",
          "orderable" : false,
          render: function (data,type,row) {
              return '<a href="/admin/auto-login/'+row.id+'" ' +
                  'class="btn btn-xs btn-primary tooltips" data-original-title="登录到用户后台" ' +
                  'data-placement="top" target="autoLogin">' +
                  '<i class="fa fa-user"></i>' +
                  '</a>';
          }
        },
      ],
      "drawCallback": function( settings ) {
        ajax_datatable.$('.tooltips').tooltip( {
          placement : 'top',
          html : true
        });
      }
    });

    dt.on('click', '.filter-submit', function(){
      ajax_datatable.ajax.reload();
    });

    dt.on('click', '.filter-cancel', function(){
      $('textarea.form-filter, select.form-filter, input.form-filter', dt).each(function() {
          $(this).val("");
      });

      $('select.form-filter').selectpicker('refresh');

      $('input.form-filter[type="checkbox"]', dt).each(function() {
          $(this).attr("checked", false);
      });
      ajax_datatable.ajax.reload();
    });

    $('.input-group.date').datepicker({
      autoclose: true
    });
    $(".bs-select").selectpicker({
      iconBase: "fa",
      tickIcon: "fa-check"
    });
  };

  return {
    init : datatableAjax
  }
}();