@extends('layouts.app')
@section('content')
<style>
  .right_side .table_row,
  .member_right .table_row {
    border-bottom: 1px solid #dedede;
    float: left;
    width: 100%;
    padding: 1px 0px 4px 2px;
  }

  .table_row .table_td {
    padding: 8px 8px !important;
  }

  .report_title {
    float: left;
    font-size: 20px;
    width: 100%;
  }
</style>
<!-- page content -->
<div class="right_col" role="main">
  <!-- vehicle model-->
  <div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 id="myLargeModalLabel" class="modal-title"><?php echo 'Sales'; ?></h4>
        </div>
        <div class="modal-body">
        </div>
      </div>
    </div>
  </div>
  <!-- All sales view -->
  <div id="myModal-sales" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 id="myLargeModalLabel" class="modal-title"><?php echo 'Sales Datails'; ?></h4>
        </div>
        <div class="modal-body">
        </div>
      </div>
    </div>
  </div>
  <!--  Completed service view -->
  <div id="myModal-service" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 id="myLargeModalLabel" class="modal-title"><?php echo 'Service'; ?></h4>
        </div>
        <div class="modal-body">

        </div>
      </div>
    </div>
  </div>
  <!-- All Completed service view -->
  <div id="myModal-completed" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 id="myLargeModalLabel" class="modal-title"><?php echo 'Completed Service'; ?></h4>
        </div>
        <div class="modal-body">

        </div>
      </div>
    </div>
  </div>
  <!-- All upcoming service view -->
  <div id="myModal-upcoming" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 id="myLargeModalLabel" class="modal-title"><?php echo 'Upcoming Service'; ?></h4>
        </div>
        <div class="modal-body">

        </div>
      </div>
    </div>
  </div>

  <!--  upcoming service view -->
  <div id="myModal-up-service" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 id="myLargeModalLabel" class="modal-title"><?php echo 'Upcoming Service'; ?></h4>
        </div>
        <div class="modal-body">

        </div>
      </div>
    </div>
  </div>
  <div class="">
    <div class="page-title">
      <div class="nav_menu">
        <nav>
          <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><span class="titleup"><a href="{!! url('/supportstaff/list') !!}"><img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="me-2 back-arrow"> {{ $supportstaff->name . ' ' . $supportstaff->lastname }}</span></a>
          </div>
          @include('dashboard.profile')
        </nav>
      </div>
    </div>
  </div>
  <div class="row view_page_header_bg ms-0">
    <div class="row">
      <div class="col-xl-10 col-md-9 col-sm-10">
        <div class="user_profile_header_left">
          <img class="user_view_profile_image" src="{{ URL::asset('public/supportstaff/' . $supportstaff->image) }}">
          <div class="row">
            <div class="view_top1">
              <div class="col-xl-12 col-md-12 col-sm-12">
                <label class="nav_text h5 user-name">
                  {{ $supportstaff->name . ' ' . $supportstaff->lastname }}&nbsp;
                </label>
                @can('supportstaff_edit')
                <div class="view_user_edit_btn d-inline">
                  <a href="{!! url('/supportstaff/list/edit/' . $supportstaff->id) !!}">
                    <img src="{{ URL::asset('public/img/dashboard/Edit.png') }}">
                  </a>
                </div>
                @endcan
              </div>
              <div class="col-xl-12 col-md-12 col-sm-12 nav_text mt-2">
                <div class="d-lg-inline">
                  <i class=" fa fa-phone"></i> {{ $supportstaff->mobile_no }}
                </div>
                <div class="d-lg-inline">
                  <i class=" fa fa-envelope"></i> {{ $supportstaff->email }}
                </div>
              </div>
              <div class="col-xl-12 col-md-12 col-sm-12 heading_view mt-3" style="width: 90%;">
                <i class="fa-solid fa-location-dot"></i>
                <lable class="">
                  {{ $supportstaff->address }}
                  <!-- , <?php echo getCityName($supportstaff->city_id) != null ? getCityName($supportstaff->city_id) . ',' : ''; ?>{{ getStateName($supportstaff->state_id) }}, {{ getCountryName($supportstaff->country_id) }}. -->
                </lable>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-2 col-lg-3 col-md-3 col-sm-2">
        <div class="group_thumbs">
          <img src="{{ URL::asset('public/img/dashboard/Design.png') }}" height="93px" width="134px">
        </div>
      </div>
    </div>
  </div>
  @if (session('message'))
  <div class="row massage">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="checkbox checkbox-success checkbox-circle mb-2 alert alert-success alert-dismissible fade show">
        <input id="checkbox-10" type="checkbox" checked="">
        <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ session('message') }} </label>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1rem 0.75rem;"></button>
      </div>
    </div>
  </div>
  @endif
  <section id="" class="">

    <div class="panel-body padding_0 pb-0">
      <div class="row mt-4">
        <div class="col-xl-12 col-md-12 col-sm-12 table-responsive">
          <ul class="nav nav-tabs" role="tablist">
            <li class="active">
              <a href="" class="tab active fw-bold">
                {{ trans('message.GENERAL') }}</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="row margin_top_15px mx-1">
        <div class="col-xl-3 col-md-3 col-sm-6">
          <label class="">{{ trans('message.Display Name') }} </label><br>
          <label class="fw-bold">{{ $supportstaff->display_name  ?? $supportstaff->name }}<br></label>
        </div>
        <div class="col-xl-3 col-md-3 col-sm-6">
          <label class="">{{ trans('message.Date of Birth') }}</label><br>
          <label class="fw-bold">
            @if (!empty($supportstaff->birth_date))
            {{ date(getDateFormat(), strtotime($supportstaff->birth_date)) }}
            @else
            {{ trans('message.Not Added') }}
            @endif<br>
          </label>
        </div>
        <div class="col-xl-3 col-md-3 col-sm-6">
          <label class="">{{ trans('message.Gender') }} </label><br>
          <span class="txt_color fw-bold">
            @if ($supportstaff->gender == '0')
            <?php echo trans('message.Male'); ?>
            @elseif ($supportstaff->gender == '1')
            <?php echo trans('message.Female'); ?>
            @else
            <?php echo trans('message.Not Added'); ?>
            @endif
          </span>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-xl-6 col-md-6 col-sm-6">
          <div class="guardian_div mb-3">
            <div class="row">
              <p class="fw-bold overflow-visible h5"> {{ trans('message.More Info') }}. </p>
              <div class="col-xl-6 col-md-6 col-sm-12 mt-1">
                <label class=""> {{ trans('message.Landline No.') }} : </label>
                <label class="fw-bold">
                  {{ $supportstaff->landline_no ?? trans('message.Not Added') }}
                </label>
              </div>


              @if (!$tbl_custom_fields->count() !== 0)
              @foreach ($tbl_custom_fields as $tbl_custom_field)
              <?php
              $tbl_custom = $tbl_custom_field->id;
              $customerid = $supportstaff->id;

              $datavalue = getCustomData($tbl_custom, $customerid);
              ?>
              @if ($tbl_custom_field->type == 'radio')
              @if ($datavalue != '')
              <?php
              $radio_selected_value = getRadioSelectedValue($tbl_custom_field->id, $datavalue);
              ?>
              <div class="col-xl-6 col-md-6 col-sm-12 mt-1">
                <label class="">{{ $tbl_custom_field->label }} : </label>
                <label class="fw-bold">
                  {{ $radio_selected_value }}<br>
                </label>
              </div>

              @endif
              @else
              @if ($datavalue != '')
              <div class="col-xl-6 col-md-6 col-sm-12 mt-1">
                <label class="">{{ $tbl_custom_field->label }} : </label>
                <label class="fw-bold">
                  {{ $datavalue }}<br>
                </label>
              </div>
              @endif
              @endif
              @endforeach
              @else
              <p style="text-align: center;">{{ trans('message.Data not available') }}</p>
              @endif
            </div>
          </div>
        </div>
        <div class="col-xl-6 col-md-6 col-sm-6">
          <div class="guardian_div mb-1">
            <p class="fw-bold overflow-visible h5"> {{ trans('message.Address Details') }} </p>
            <div class="row">
              <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                <label class=""> {{ trans('message.Country') }}: </label>
                <label class="fw-bold">
                  {{ getCountryName($supportstaff->country_id) }}
                </label>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12 mt-1">
                <label class=""> {{ trans('message.State') }}: </label>
                <label class="fw-bold">
                  {{ getStateName($supportstaff->state_id)  ?? trans('message.Not Added') }}
                </label>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12 mt-1">
                <label class=""> {{ trans('message.Town/City') }}: </label>
                <label class="fw-bold">
                  {{ getCityName($supportstaff->city_id)  ?? trans('message.Not Added') }}
                </label>
              </div>
              <!-- <div class="col-xl-6 col-md-6 col-sm-12 mt-1">
                <label class=""> {{ trans('message.Address') }}: </label>
                <label class="text-dark fw-bold">{{ $supportstaff->address }}</label>
              </div> -->
            </div>

          </div>

        </div>
      </div>
    </div>
    <!-- END PANEL BODY DIV-->

  </section>
</div>

@endsection