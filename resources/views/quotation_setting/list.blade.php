@extends('layouts.app')
@section('content')
<!-- mail editor -->
<!-- <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script> -->
<script src="{!! URL::asset('public/vendor/ckeditor/ckeditor.js') !!}"></script>
<!-- page content -->
<style>.table-responsive::-webkit-scrollbar {
    height: 8px; /* Height of the horizontal scrollbar */
}

.table-responsive::-webkit-scrollbar-track {
    background:rgb(196, 194, 193); /* Background of the scrollbar track */
}

.table-responsive::-webkit-scrollbar-thumb {
    background:rgb(214, 214, 213); /* Scrollbar thumb color */
    border-radius: 4px; /* Rounded edges for thumb */
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: grey; /* Change color on hover for better visibility */
}</style>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="nav_menu">
        <nav>
          <div class="nav toggle">
          <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><a href="{{ URL::previous() }}" id=""><i class=""><img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="back-arrow"></i><span class="titleup">
                {{ trans('message.Quotation Setting') }}</span></a>
          </div>
          @include('dashboard.profile')
        </nav>
      </div>
    </div>
    @include('success_message.message')
    <div class="x_content table-responsive">
      <ul class="nav nav-tabs">
        @can('generalsetting_view')
        <li class="nav-item">
          <a href="{!! url('setting/general_setting/list') !!}" class="nav-link nav-link-not-active"><span class="visible-xs"></span> <i class="">&nbsp;</i><b>{{ trans('message.GENERAL SETTINGS') }}</b></a>
        </li>
        @endcan
        @can('timezone_view')
        <li class="nav-item">
          <a href="{!! url('setting/timezone/list') !!}" class="nav-link nav-link-not-active"><span class="visible-xs"></span><i class="">&nbsp;</i><b>{{ trans('message.OTHER SETTINGS') }}</b></a>
        </li>
        @endcan
        @can('accessrights_view')
        <li class="nav-item">
          <a href="{!! url('setting/accessrights/show') !!}" class="nav-link nav-link-not-active"><span class="visible-xs"></span><i class="">&nbsp;</i><b>{{ trans('message.ACCESS RIGHTS') }}</b></a>
        </li>
        @endcan
        @can('businesshours_view')
        <li class="nav-item">
          <a href="{!! url('setting/hours/list') !!}" class="nav-link nav-link-not-active"><span class="visible-xs"></span><i class="">&nbsp;</i><b>{{ trans('message.BUSINESS HOURS') }}</b></a>
        </li>
        @endcan
        @can('stripesetting_view')
        <li class="nav-item">
          <a href="{!! url('setting/stripe/list') !!}" class="nav-link nav-link-not-active"><span class="visible-xs"></span><i class="">&nbsp;</i><b>{{ trans('message.STRIPE SETTINGS') }}</b></a>
        </li>
        @endcan
        @can('branchsetting_view')
        <li class="nav-item">
          <a href="{!! url('branch_setting/list') !!}" class="nav-link nav-link-not-active"><span class="visible-xs"></span><i class="">&nbsp;</i><b>{{ trans('message.BRANCH SETTING') }}</b></a>
        </li>
        @endcan
        <li class="nav-item">
          @can('email_view')
          <a href="{!! url('setting/email_setting/list') !!}" class="nav-link nav-link-not-active"><span class="visible-xs"></span><i class="">&nbsp;</i><b>{{ trans('message.EMAIL SETTING') }}</b></a>
          @endcan
        </li>
        <li class="nav-item">
          @can('quotationsetting_view')
          <a href="{!! url('setting/quotation_setting/list') !!}" class="nav-link active"><span class="visible-xs"></span><i class="">&nbsp;</i><b>{{ trans('message.QUOTATION SETTING') }}</b></a>
          @endcan
        </li>
      </ul>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
            <form id="quotation_setting_form" method="post" action="{{ url('setting/quotation_setting/list') }}" enctype="multipart/form-data" class="form-horizontal upperform">
             @csrf
              @can('quotationsetting_view')
              <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space">
                <h4><b>{{ trans('message.QUOTATION SETTING') }}</b></h4>
                <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
              </div>

              <div class="row mt-3"> 
                    <label for="first_name" class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3 mb-2 control-label checkpointtext text-end">{{ trans('message.Terms & Condition Here :') }}
                    </label>
                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                      <!-- Generate a unique ID for each CKEditor instance -->
                      @php
                      $editorId = 'editor_';
                      @endphp
                      <textarea name="terms_and_condition_text" id="{{ $editorId }}" class="form-control validate[required] txt_area" required>{{ old('terms_and_condition_text', $terms_and_condition) }}</textarea>  

                      <!-- Initialize CKEditor with the unique ID -->
                      <script>
                        CKEDITOR.replace('{{ $editorId }}', {
                          toolbar: [{
                              name: 'styles',
                              items: ['Bold', 'Italic']
                            },
                            {
                              name: 'basicstyles',
                              items: ['Underline', 'Subscript', 'Superscript', 'RemoveFormat']
                            },
                            {
                              name: 'paragraph',
                              items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv']
                            },
                            {
                              name: 'undo',
                              items: ['Undo', 'Redo']
                            },
                            {
                              name: 'styles',
                              items: ['Format', 'Font', 'FontSize']
                            },
                            {
                              name: 'document',
                              items: ['Source']
                            }
                          ],
                          format_tags: 'p;h1;h2;h3;h4;h5;h6',
                        });
                      </script>

                    </div>
                  </div>
              @endcan

              <input type="hidden" name="_token" value="{{ csrf_token() }}">

              <div class="row mt-3">
                <!-- <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group">
                  <a class="btn branchsettingCancel" href="{{ URL::previous() }}">{{ trans('message.CANCEL') }}</a>
                </div> -->
                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group">
                  <button type="submit" class="btn btn_success_margin">{{ trans('message.SUBMIT') }}</button>
                </div>
              </div>
    
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- page content end -->

<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>


<!-- Form field validation -->
<!-- {!! JsValidator::formRequest('App\Http\Requests\StoreBranchSettingEditFormRequest', '#branch_setting_edit_form') !!}
<script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js') }}"></script> -->


@endsection