@extends('layouts.app')
@section('content')
<style>
  @media screen and (max-width:540px) {
    div#color_info {
      margin-top: -169px;
    }

    span.titleup {
      /* margin-left: -10px; */
    }
  }
</style>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="nav_menu">
        <nav>
          <div class="nav toggle">
          <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
            <span class="titleup">{{ trans('message.Colors') }}
              @can('colors_add')
              <a href="{!! url('/color/add') !!}" id="" class="addbotton">
                <img src="{{ URL::asset('public/img/icons/plus Button.png') }}">
              </a>
              @endcan
            </span>
          </div>
          @include('dashboard.profile')
        </nav>
      </div>
    </div>
    @include('success_message.message')
    <div class="row">
    @if(!empty($color) && count($color) > 0)
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel ">
          <table id="supplier" class="table jambo_table" style="width:100%">
            <thead>
              <tr>
                @can('colors_delete')
                <th> </th>
                @endcan

                <th>{{ trans('message.Name') }}</th>
                <th>{{ trans('message.Color') }}</th>
                <!-- Custom Field Data Label Name-->
                @if (!empty($tbl_custom_fields))
                @foreach ($tbl_custom_fields as $tbl_custom_field)
                <th>{{ $tbl_custom_field->label }}</th>
                @endforeach
                @endif
                <!-- Custom Field Data End -->

                @canany(['colors_edit', 'colors_delete'])
                <th>{{ trans('message.Action') }}</th>
                @endcanany

              </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>

              @foreach ($color as $colors)
              <tr data-user-id="{{ $colors->id }}">
                <!-- <td>{{ $i }}</td> -->
                @can('colors_delete')
                <td>
                  <label class="container checkbox">
                    <input type="checkbox" name="chk">
                    <span class="checkmark"></span>
                  </label>
                </td>
                @endcan
                <td><a href="{!! url('/color/list/edit/' . $colors->id) !!}">{{ $colors->color }}</a> 
                  <!-- <a data-toggle="tooltip" data-placement="bottom" title="Color Name" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> -->
                </td>
                <td><a href="{!! url('/color/list/edit/' . $colors->id) !!}"><div class="color_code" style="background-color:{{ getColor($colors->id) }};">{{ $colors->color_code }}</div></a></td>
                <!-- Custom Field Data Value-->
                @if (!empty($tbl_custom_fields))
                @foreach ($tbl_custom_fields as $tbl_custom_field)
                <?php
                $tbl_custom = $tbl_custom_field->id;
                $userid = $colors->id;

                $datavalue = getCustomDataColors($tbl_custom, $userid);
                ?>

                @if ($tbl_custom_field->type == 'radio')
                @if ($datavalue != '')
                <?php
                $radio_selected_value = getRadioSelectedValue($tbl_custom_field->id, $datavalue);
                ?>
                <td>{{ $radio_selected_value }}</td>
                @else
                <td>{{ trans('message.Data not available') }}</td>
                @endif
                @else
                @if ($datavalue != null)
                <td>{{ $datavalue }}</td>
                @else
                <td>{{ trans('message.Data not available') }}</td>
                @endif
                @endif
                @endforeach
                @endif
                <!-- Custom Field Data End -->

                @if (Gate::allows('colors_edit') || Gate::allows('colors_delete'))
                <td>
                  <div class="dropdown_toggle">
                    <img src="{{ URL::asset('public/img/list/dots.png') }}" class="btn dropdown-toggle border-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">

                    <ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2" aria-labelledby="dropdownMenuButton1">

                      @can('colors_edit')
                      <li><a class="dropdown-item" href="{!! url('/color/list/edit/' . $colors->id) !!}"><img src="{{ URL::asset('public/img/list/Edit.png') }}" class="me-3"> {{ trans('message.Edit') }}</a></li>
                      @endcan

                      @can('colors_delete')
                      <div class="dropdown-divider m-0"></div>
                      <li><a class="dropdown-item deletedatas" url="{!! url('/color/list/delete/' . $colors->id) !!}" style="color:#FD726A"><img src="{{ URL::asset('public/img/list/Delete.png') }}" class="me-3">{{ trans('message.Delete') }}</a></li>
                      @endcan
                    </ul>
                  </div>
                </td>
                @endif
              </tr>
              <?php $i++; ?>
              @endforeach
            </tbody>
          </table>
          @can('colors_delete')
            <button id="select-all-btn" class="btn select_all"><input type="checkbox" name="selectAll"> {{ trans('message.Select All') }}</button>
            <button id="delete-selected-btn" class="btn btn-danger text-white border-0" data-url="{!! url('/color/list/delete/') !!}"><i class="fa fa-trash" aria-hidden="true"></i></button>
          @endcan
        </div>
      </div>
    @else
      <p class="d-flex justify-content-center mt-5 pt-5"><img src="{{ URL::asset('public/img/dashboard/No-Data.png') }}" width="300px"></p>
    @endif
    </div>
  </div>
</div>
<!-- /page content -->


<!-- Scripts starting -->
<script src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<!-- language change in user selected -->
<script>
  $(document).ready(function() {

    var search = "{{ trans('message.Search...') }}";
    var info = "{{ trans('message.Showing page _PAGE_ - _PAGES_') }}";
    var zeroRecords = "{{ trans('message.No Data Found') }}";
    var infoEmpty = "{{ trans('message.No records available') }}";

    $('#supplier').DataTable({
      columnDefs: [{
        width: 2,
        targets: 0
      }],
      fixedColumns: true,
      paging: true,
      scrollCollapse: true,
      scrollX: true,
      // scrollY: 300,

      responsive: true,
      "language": {
        lengthMenu: "_MENU_ ",
        info: info,
        zeroRecords: zeroRecords,
        infoEmpty: infoEmpty,
        infoFiltered: '(filtered from _MAX_ total records)',
        searchPlaceholder: search,
        search: '',
        paginate: {
          previous: "<",
          next: ">",
        }
      },
      aoColumnDefs: [{
        bSortable: false,
        aTargets: [-1]
      }],
    });


    /*delete color*/
    $('body').on('click', '.deletedatas', function() {

      var url = $(this).attr('url');
      var msg1 = "{{ trans('message.Are You Sure?') }}";
      var msg2 = "{{ trans('message.You will not be able to recover this data afterwards!') }}";
      var msg3 = "{{ trans('message.Cancel') }}";
      var msg4 = "{{ trans('message.Yes, delete!') }}"; 

      swal({
        title: msg1,
        text: msg2,
        icon: 'warning',
        cancelButtonColor: '#C1C1C1',
        buttons: [msg3, msg4],
        dangerMode: true,
      }).then((willDelete) => {
        if (willDelete) {
          window.location.href = url;
        }
      });

    });
  });
</script>
@endsection