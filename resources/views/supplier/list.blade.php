@extends('layouts.app')

@section('content')
<style>
  /* @media screen and (min-width:0px) and (max-width:652px) {
  .x_panel.table_up_div{
    width: fit-content;
  }
 
} */
  @media screen and (max-width:540px) {
    /* div#supplier_info {
      margin-top: -487px;
    } */

    span.titleup {
      margin-left: -10px;
    }
  }
  div.dataTables_processing {
        position: fixed;
        top: 50%;
        left: 50%;
        width: 150px;
        color:#EA6B00;
        margin-left: -100px;
        margin-top: -26px;
        text-align: center;
        padding: 3px 0;
        border: none;
    }
</style>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="nav_menu">
        <nav>
          <div class="nav toggle">
              <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><span class="titleup">&nbsp;{{ trans('message.Supplier') }}
              @can('supplier_add')
              <a href="{!! url('/supplier/add') !!}" id="" class="addbotton">
                <img src="{{ URL::asset('public/img/icons/plus Button.png') }}">
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
  @if(!empty($user) && count($user) > 0)
    <div class="col-md-12 col-sm-12 col-xs-12 ">
      <div class="x_panel table_up_div">
        <table id="supplier" class=" table jumbo_table " style="width:100%">
          <thead>
            <tr>
              @can('supplier_delete')
              <th> </th>
              @endcan
              <th>{{ trans('message.Image') }}</th>
              <th>{{ trans('message.First Name') }}</th>
              <th>{{ trans('message.Last Name') }}</th>
              <th>{{ trans('message.Company Name') }}</th>
              <th>{{ trans('message.Email') }}</th>
              <th>{{ trans('message.Product Name') }}</th>
              <th>{{ trans('message.Action') }}</th>
            </tr>
          </thead>
          <tbody>

            

          </tbody>
        </table>
        @can('supplier_delete')
        <button id="select-all-btn" class="btn select_all"><input type="checkbox" name="selectAll" class=""> {{ trans('message.Select All') }} </button>
        <button id="delete-selected-btn" class="btn btn-danger text-white border-0" data-url="{!! url('/supplier/list/delete') !!}"><i class="fa fa-trash" aria-hidden="true"></i></button>
        @endcan
        </nav>
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
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
  $(document).ready(function() {

    var search = "{{ trans('message.Search...') }}";
    var info = "{{ trans('message.Showing page _PAGE_ - _PAGES_') }}";
    var zeroRecords = "{{ trans('message.No Data Found') }}";
    var infoEmpty = "{{ trans('message.No records available') }}";

  //   $('#supplier').DataTable({
  //     columnDefs: [{
  //       width: 2,
  //       targets: 0,

  //     }],
  //     fixedColumns: true,
  //     paging: true,
  //     scrollCollapse: true,
  //     scrollX: true,
  //     // scrollY: 300,

  //     // var Search = "{{ trans('message.Search..') }}",

  //     responsive: true,
  //     "language": {
  //       lengthMenu: "_MENU_ ",
  //       info: info,
  //       zeroRecords: zeroRecords,
  //       infoEmpty: infoEmpty,
  //       infoFiltered: '(filtered from _MAX_ total records)',
  //       searchPlaceholder: search,
  //       search: '',
  //       paginate: {
  //         previous: "<",
  //         next: ">",
  //       }
  //     },
  //     aoColumnDefs: [{
  //       bSortable: false,
  //       aTargets: [-1],

  //     }],

  //   });
  // });
  $('#supplier').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ route('supplier.supplierlist') }}",
        type: "GET",
      },
      columns: [
        @can('supplier_delete')
        {
          data: 'id',
          name: 'id',
          orderable: false,
          searchable: false,
          render: function (data) {
            return `<label class="container checkbox">
                      <input type="checkbox" name="chk" value="${data}">
                      <span class="checkmark"></span>
                    </label>`;
          }
        },
        @endcan
        {
          data: 'image',
          name: 'image',
          orderable: false,
          searchable: false
        },
        {
          data: 'name',
          name: 'name'
        },
        {
          data: 'lastname',
          name: 'lastname'
        },
        {
          data: 'company_name',
          name: 'company_name'
        },
        {
          data: 'email',
          name: 'email'
        },
        {
          data: 'product_name',
          name: 'product_name',
          orderable: false,
          searchable: false
        },
        {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        }
      ],
      language: {
        lengthMenu: "_MENU_",
        info: info,
        zeroRecords:zeroRecords,
        infoEmpty: infoEmpty,
        infoFiltered: "{{ trans('message.infoFiltered') }}",
        searchPlaceholder: search,
        search: "",
        paginate: {
          previous: "<",
          next: ">"
        },
        processing: '<div class="loading-indicator"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>'
      },
      order: [[1, 'asc']]
    });
  });

  $(document).on('change', '#select-all-btn input[type="checkbox"]', function () {
    $('input[name="chk"]').prop('checked', $(this).prop('checked'));
});

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
</script>

<script>
  $(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>

<script type="text/javascript">
  function selects() {
    var ele = document.getElementsByName('chk');
    checked = document.getElementsByName('chk').checked;
    for (var i = 0; i < ele.length; i++) {

      ele[i].checked = true;
    }
  }

  function deSelect() {
    var ele = document.getElementsByName('chk');
    for (var i = 0; i < ele.length; i++) {
      if (ele[i].type == 'checkbox')
        ele[i].checked = false;

    }
  }
</script>
<script>
  $(document).on('click', '#delete-selected-btn', function () {
    var selectedIds = $('input[name="chk"]:checked').map(function () {
        return $(this).val(); // Get checked values
    }).get();

    console.log("Selected IDs:", selectedIds); // Debugging: Check if IDs are collected

    if (selectedIds.length === 0) {
        swal("{{ trans('message.Please select at least one record to delete.') }}", { icon: "warning" });
        return;
    }

    var deleteUrl = $(this).data('url');

    swal({
        title: "{{ trans('message.Are You Sure?') }}",
        text: "{{ trans('message.You will not be able to recover this data afterwards!') }}",
        icon: "warning",
        buttons: ["{{ trans('message.Cancel') }}", "{{ trans('message.Yes, delete!') }}"],
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: deleteUrl,
                type: 'POST',
                data: { ids: selectedIds, _token: '{{ csrf_token() }}' },
                success: function (response) {
                    swal("{{ trans('message.Deleted!') }}", response.message, "success");
                    $('#supplier').DataTable().ajax.reload(); // Refresh DataTable
                },
                error: function (xhr) {
                    swal("{{ trans('message.Something went wrong. Please try again.') }}", { icon: "error" });
                }
            });
        }
    });
});

</script>


@endsection