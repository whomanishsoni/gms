@extends('layouts.app')
@section('content')
  <!-- page content -->
  <div class="right_col"
    role="main">
    <div class="">
      <div class="page-title">
        <div class="nav_menu">
          <nav>
            <div class="nav toggle">
              <a id="menu_toggle"><i class="fa fa-bars"></i><span class="titleup">&nbsp;
                  {{ trans('message.Observation Type') }}</span></a>
            </div>
            @include('dashboard.profile')
          </nav>
        </div>
        @if (session('message'))
          <div class="row massage">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="checkbox checkbox-success checkbox-circle mb-2 alert alert-success alert-dismissible fade show">
                <input id="checkbox-10"
                  type="checkbox"
                  checked="">
                <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ session('message') }} </label>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1rem 0.75rem;"></button>
              </div>
            </div>
          </div>
        @endif
      </div>
      <div class="x_content">
        <ul class="nav nav-tabs bar_tabs"
          role="tablist">
          <li role="presentation"
            class=""><a href="{!! url('/observation_type/list') !!}"><span class="visible-xs"></span><i
                class="fa fa-list fa-lg">&nbsp;</i>{{ trans('message.List Of Observation Type') }}</span></a></li>

          <li role="presentation"
            class=""><a href="{!! url('/observation_type/add') !!}"><span class="visible-xs"></span><i
                class="fa fa-plus-circle fa-lg">&nbsp;</i>{{ trans('message.Add Observation Type') }}</span></a></li>

          <li role="presentation"
            class="active"><a href="{!! url('/observation_type/list/edit/' . $editid) !!}"><span class="visible-xs"></span><i
                class="fa fa-pencil-square-o"
                aria-hidden="true">&nbsp;</i><b>{{ trans('message.Edit Observation Type') }}</b></span></a></li>
        </ul>
      </div>

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">

            <div class="x_content">


              <form method="post"
                action="update/{{ $o_type_point->id }}"
                enctype="multipart/form-data"
                class="form-horizontal upperform">





                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback"
                  style="margin-top:15px;">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12"
                    for="rto_tax">{{ trans('message.Observation Type') }} <label class="text-danger">*</label>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">

                    <input type="text"
                      id="o_type"
                      name="o_type"
                      class="form-control"
                      value="{{ $o_type_point->type }}"
                      required>
                  </div>
                </div>





                <input type="hidden"
                  name="_token"
                  value="{{ csrf_token() }}">

                <div class="form-group">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                    <button type="submit"
                      class="btn btn-success">{{ trans('message.Submit') }}</button>
                  </div>
                </div>

              </form>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"
    src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
@endsection
