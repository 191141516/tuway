@extends('layouts.admin')

@section('css')
  <link rel="stylesheet" type="text/css" href="{{asset('plugins/datatables/datatables.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('plugins/bootstrap-select/css/bootstrap-select.min.css')}}">
  <style>
    body {
      padding: 25px;
    }

    h1 {
      font-size: 1.5em;
      margin-top: 0;
    }

    .stack {
      font-size: 0.85em;
    }

    .date {
      min-width: 75px;
    }

    .text {
      word-break: break-all;
    }

    a.llv-active {
      z-index: 2;
      background-color: #f5f5f5;
      border-color: #777;
    }
  </style>
@endsection

@section('content')
  <div class="page-bar">
    <ul class="page-breadcrumb">
      <li>
        <a href="{{url('admin')}}">home</a>
        <i class="fa fa-circle"></i>
      </li>
      <li>
        <span>错误日志</span>
      </li>
    </ul>
  </div>
  <br>
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-3 col-md-2 sidebar">
        <h1><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Laravel Log Viewer</h1>
        <p class="text-muted"><i>by Rap2h</i></p>
        <div class="list-group">
          @foreach($files as $file)
            <a href="?l={{ base64_encode($file) }}"
               class="list-group-item @if ($current_file == $file) llv-active @endif">
              {{$file}}
            </a>
          @endforeach
        </div>
      </div>
      <div class="col-sm-9 col-md-10 table-container">
        @if ($logs === null)
          <div>
            Log file >50M, please download it.
          </div>
        @else
          <table id="table-log" class="table table-striped">
            <thead>
            <tr>
              <th>Level</th>
              <th>Context</th>
              <th>Date</th>
              <th>Content</th>
            </tr>
            </thead>
            <tbody>

            @foreach($logs as $key => $log)
              <tr data-display="stack{{{$key}}}">
                <td class="text-{{{$log['level_class']}}}"><span class="glyphicon glyphicon-{{{$log['level_img']}}}-sign"
                                                                 aria-hidden="true"></span> &nbsp;{{$log['level']}}</td>
                <td class="text">{{$log['context']}}</td>
                <td class="date">{{{$log['date']}}}</td>
                <td class="text">
                  @if ($log['stack']) <a class="pull-right expand btn btn-default btn-xs"
                                         data-display="stack{{{$key}}}"><span
                            class="glyphicon glyphicon-search"></span></a>@endif
                  {{{$log['text']}}}
                  @if (isset($log['in_file'])) <br/>{{{$log['in_file']}}}@endif
                  @if ($log['stack'])
                    <div class="stack" id="stack{{{$key}}}"
                         style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}
                    </div>@endif
                </td>
              </tr>
            @endforeach

            </tbody>
          </table>
        @endif
        <div>
          @if($current_file)
            <a href="?dl={{ base64_encode($current_file) }}"><span class="glyphicon glyphicon-download-alt"></span>
              Download file</a>
            -
            <a id="delete-log" href="?del={{ base64_encode($current_file) }}"><span
                      class="glyphicon glyphicon-trash"></span> Delete file</a>
            @if(count($files) > 1)
              -
              <a id="delete-all-log" href="?delall=true"><span class="glyphicon glyphicon-trash"></span> Delete all files</a>
            @endif
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script type="text/javascript" src="{{asset('plugins/datatables/datatables.all.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('plugins/bootstrap-select/js/bootstrap-select.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('plugins/layer/layer.js')}}"></script>
  <script>
      $(document).ready(function () {
          $('.table-container tr').on('click', function () {
              $('#' + $(this).data('display')).toggle();
          });
          $('#table-log').DataTable({
              "order": [1, 'desc'],
              "stateSave": true,
              "stateSaveCallback": function (settings, data) {
                  window.localStorage.setItem("datatable", JSON.stringify(data));
              },
              "stateLoadCallback": function (settings) {
                  var data = JSON.parse(window.localStorage.getItem("datatable"));
                  if (data) data.start = 0;
                  return data;
              }
          });
          $('#delete-log, #delete-all-log').click(function () {
              return confirm('Are you sure?');
          });
      });
  </script>
@endsection
