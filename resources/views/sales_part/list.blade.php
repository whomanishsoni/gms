@extends('layouts.app')
@section('content')
<style>
    @media screen and (max-width:540px) {
        div#sales_part_info {
            margin-top: -179px;
        }

        span.titleup {
            margin-left: -10px;
        }
    }
    div.dataTables_processing {
        position: fixed;
        top: 50%;
        left: 50%;
        width: 200px;
        color:#EA6B00;
        margin-left: -100px;
        margin-top: -26px;
        text-align: center;
        padding: 1em 0;
        border: none;
    }
</style>
<!-- page content -->
<div class="right_col" role="main">
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h4 id="myLargeModalLabel" class="modal-title">{{ trans('message.Invoice') }}</h4> -->
                    <h3> {{ getNameSystem() }}</h3>
                    <a href=""><button type="button" class="btn-close"></button></a>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        @if (getActiveCustomer(Auth::user()->id) == 'yes' || getActiveEmployee(Auth::user()->id) == 'yes' || getBranchadminsactive(Auth::user()->id) == 'yes')
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><span class="titleup">{{ trans('message.Part Sells') }}
                            @can('salespart_add')
                            <a href="{!! url('/sales_part/add') !!}" id="" class="addbotton">
                                <img src="{{ URL::asset('public/img/icons/plus Button.png') }}" class="mb-2">
                            </a>
                            @endcan
                        </span>
                        @else
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><span class="titleup">{{ trans('message.Purchase') }}
                        </span>
                        @endif
                    </div>
                    @include('dashboard.profile')
                </nav>
            </div>
        </div>
        @include('success_message.message')
        <div class="row">
        @if(!empty($sales) && count($sales) > 0)
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel table_up_div">
                    <table id="supplier" class="table jambo_table ">
                        <thead>
                            <tr>
                                <th>{{ trans('message.Bill Number') }}</th>
                                <th>{{ trans('message.Customer Name') }}</th>
                                <th>{{ trans('message.Date') }}</th>
                                <!-- <th>{{ trans('message.Part Brand') }}</th> -->
                                <th>{{ trans('message.Salesman') }}</th>
                                <th>{{ trans('message.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                          
                        </tbody>
                    </table>
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

<script>
    $(document).ready(function() {

        var search = "{{ trans('message.Search...') }}";
        var info = "{{ trans('message.Showing page _PAGE_ - _PAGES_') }}";
        var zeroRecords = "{{ trans('message.No Data Found') }}";
        var infoEmpty = "{{ trans('message.No records available') }}";

        /*language change in user selected*/
        // $('#supplier').DataTable({
        //     columnDefs: [{
        //         width: 2,
        //         targets: 0
        //     }],
        //     fixedColumns: true,
        //     paging: true,
        //     scrollCollapse: true,
        //     scrollX: true,
        //     // scrollY: 300,

        //     responsive: true,
        //     "language": {
        //         lengthMenu: "_MENU_ ",
        //         info: info,
        //         zeroRecords: zeroRecords,
        //         infoEmpty: infoEmpty,
        //         infoFiltered: '(filtered from _MAX_ total records)',
        //         searchPlaceholder: search,
        //         search: '',
        //         paginate: {
        //             previous: "<",
        //             next: ">",
        //         }
        //     },
        //     aoColumnDefs: [{
        //         bSortable: false,
        //         aTargets: [-1]
        //     }],
        //     order: [
        //         [2, 'asc']
        //     ]
        // });
        $('#supplier').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ url("/sales_part/list") }}',
        type: 'GET',
    },
    columns: [
       
        {
            data: 'bill_no',
            name: 'bill_no'
        },
        {
            data: 'customer_name',
            name: 'customer_name' // Adjusted to match the key returned from the server
        },
        {
            data: 'date',
            name: 'date'
        },
        {
            data: 'salesman_name',
            name: 'salesman_name' // Adjusted to match the key returned from the server
        },
        
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        }
    ],
    language: {
        lengthMenu: "_MENU_ ",
        info: info,
        zeroRecords: zeroRecords,
        infoEmpty: infoEmpty,
        searchPlaceholder: search,
        search: '',
        paginate: {
            previous: "<",
            next: ">",
        },
        processing: '<div class="loading-indicator"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>'
    },
    order: [[1, 'asc']],
    responsive: true,
    fixedColumns: true,
    paging: true,
    scrollCollapse: true,
    scrollX: true,
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



        $('body').on('click', '.save', function() {

            $('.modal-body').html("");
            var saleid = $(this).attr("saleid");
            var invoice_number = $(this).attr("invoice_number");
            var url = $(this).attr('url');

            var currentPageAction = getParameterByName('page_action');
            // Construct the URL for AJAX request with page_action parameter
            if (currentPageAction) {
                url += '?page_action=' + currentPageAction;
            }
            var msg14 = "{{ trans('message.An error occurred :') }}";

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    saleid: saleid,
                    invoice_number: invoice_number
                },
                dataType:'json',
                success: function(data) {
                    $('.modal-body').html(data.html);
                },
                beforeSend: function() {
                    $(".modal-body").html(
                        "<center><h2 class=text-muted><b>Loading...</b></h2></center>");
                },
                error: function(e) {
                    alert(msg14 + " " + e.responseText);
                    console.log(e);
                }
            });
        });
    });
    // For get getParameterByName
    function getParameterByName(name, url = window.location.href) {
      name = name.replace(/[\[\]]/g, '\\$&');
      var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
      results = regex.exec(url);
      if (!results) return null;
      if (!results[2]) return '';
      return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }
</script>
@endsection