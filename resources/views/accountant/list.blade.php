@extends('layouts.app')
@section('content')
<style>
  @media screen and (max-width:540px) {
    div#accountant_info {
      margin-top: -180px;
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
          <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><span class="titleup">{{ trans('message.Accountant') }}
              @can('accountant_add')
              <a href="{!! url('/accountant/add') !!}" id="" class="addbotton"><img src="{{ URL::asset('public/img/icons/plus Button.png') }}">
              </a>
              @endcan
            </span>
          </div>
          @include('dashboard.profile')
        </nav>
      </div>
    </div>
  </div>
  @include('success_message.message')
  <div class="row">
  @if(!empty($accountant) && count($accountant) > 0)
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel bgr">
        <table id="supplier" class="table jambo_table" style="width:100%">
          <thead>
            <tr>
              @can('accountant_delete')
              <th> </th>
              @endcan
              <th>{{ trans('message.Image') }}</th>
              <th>{{ trans('message.First Name') }}</th>
              <th>{{ trans('message.Last Name') }}</th>
              <th>{{ trans('message.Email') }}</th>
              <th>{{ trans('message.Mobile Number') }}</th>              
              <th>{{ trans('message.Action') }}</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; ?>
            @foreach ($accountant as $accountants)
            <tr data-user-id="{{ $accountants->id }}">
            @can('accountant_delete')
              <td>
                <label class="container checkbox">
                  <input type="checkbox" name="chk">
                  <span class="checkmark"></span>
                </label>
              </td>
            @endcan
              <td><a href="{!! url('/accountant/list/' . $accountants->id) !!}"><img src="{{ url('public/accountant/' . $accountants->image) }}" width="52px" height="52px" class="datatable_img"></a></td>
              <td><a href="{!! url('/accountant/list/' . $accountants->id) !!}">{{ $accountants->name }}</a> 
                <!-- <a data-toggle="tooltip" data-placement="bottom" title="First Name" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> -->
              </td>
              <td>{{ $accountants->lastname }} 
                <!-- <a data-toggle="tooltip" data-placement="bottom" title="Last Name" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> -->
              </td>
              <td>{{ $accountants->email }} 
                <!-- <a data-toggle="tooltip" data-placement="bottom" title="Email" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> -->
              </td>
              <td>{{ $accountants->mobile_no }} 
                <!-- <a data-toggle="tooltip" data-placement="bottom" title="Mobile Number" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> -->
              </td>

              <td>
                <div class="dropdown_toggle">
                  <img src="{{ URL::asset('public/img/list/dots.png') }}" class="btn dropdown-toggle border-0" type="button" id="dropdownMenuButtonaction" data-bs-toggle="dropdown" aria-expanded="false">

                  <ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2" aria-labelledby="dropdownMenuButtonaction">
                    @can('accountant_view')
                    <li><a class="dropdown-item" href="{!! url('/accountant/list/' . $accountants->id) !!}"><img src="{{ URL::asset('public/img/list/Vector.png') }}" class="me-3">{{ trans('message.View') }}</a></li>
                    @endcan

                    @can('accountant_edit')
                    <li><a class="dropdown-item" href="{!! url('/accountant/list/edit/' . $accountants->id) !!}"><img src="{{ URL::asset('public/img/list/Edit.png') }}" class="me-3"> {{ trans('message.Edit') }}</a></li>
                    @endcan

                    @can('accountant_delete')
                    <div class="dropdown-divider m-0"></div>
                    <li><a class="dropdown-item deletedatas" url="{!! url('/accountant/list/delete/' . $accountants->id) !!}" style="color:#FD726A"><img src="{{ URL::asset('public/img/list/Delete.png') }}" class="me-3">{{ trans('message.Delete') }}</a></li>
                    @endcan
                  </ul>
                </div>
              </td>
            </tr>
            <?php $i++; ?>
            @endforeach
          </tbody>
        </table>
        @can('accountant_delete')
        <button id="select-all-btn" class="btn select_all"><input type="checkbox" name="selectAll"> {{ trans('message.Select All') }}</button>
        <button id="delete-selected-btn" class="btn btn-danger text-white border-0" data-url="{!! url('/accountant/list/delete/') !!}"><i class="fa fa-trash" aria-hidden="true"></i></button>
        @endcan
      </div>
    </div>
  @else
    <p class="d-flex justify-content-center mt-5 pt-5"><img src="{{ URL::asset('public/img/dashboard/No-Data.png') }}" width="300px"></p>
  @endif
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


    /*delete accountant*/
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