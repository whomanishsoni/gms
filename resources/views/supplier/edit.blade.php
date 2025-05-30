@extends('layouts.app')
@section('content')
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><a href="{{ URL::previous() }}" id=""><i class=""><img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="mb-1 back-arrow"></i><span class="titleup">
                                {{ trans('message.Edit Supplier') }}</span>
                    </div>
                    @include('dashboard.profile')
                </nav>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            <div class="x_panel">
                <div class="x_content">

                    <!-- Supplier Edit Form Start -->
                    <form id="supplier_edit_form" method="post" action="update/{{ $user->id }}" enctype="multipart/form-data" class="form-horizontal upperform">

                        <!-- Personal Information Part Start -->
                        <div class="col-md-12 col-xs-12 col-sm-12 space">
                            <h4><b>{{ trans('message.PERSONAL INFORMATION') }}</b></h4>
                            <p class="col-md-12 col-xs-12 col-sm-12 ln_solid"></p>
                        </div>

                        <!-- FirstName and LastName Field -->
                        <div class="row row-mb-0">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 {{ $errors->has('firstname') ? ' has-error' : '' }}">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="firstname">{{ trans('message.First Name') }} <label class="color-danger">*</label></label>

                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" id="firstname" name="firstname" class="form-control" value="{{ $user->name }}" placeholder="{{ trans('message.Enter First Name') }}" maxlength="50">
                                    @if ($errors->has('firstname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 {{ $errors->has('lastname') ? ' has-error' : '' }}">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="lastname">{{ trans('message.Last Name') }} <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" id="lastname" name="lastname" class="form-control" value="{{ $user->lastname }}" placeholder="{{ trans('message.Enter Last Name') }}" maxlength="50">
                                    @if ($errors->has('lastname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- FirstName and LastName Field End -->

                        <!-- CompanyName and Email Field -->
                        <div class="row row-mb-0">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label for="displayname" class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">{{ trans('message.Company Name') }}
                                    <label class="color-danger">*</label></label>

                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" id="displayname" class="form-control companyname" name="displayname" value="{{ $user->company_name }}" placeholder="{{ trans('message.Enter Company Name') }}" maxlength="100">
                                    @if ($errors->has('displayname'))
                                    <span class="help-block" style="color:#a94442">
                                        <strong>{{ $errors->first('displayname') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="email">{{ trans('message.Email') }} <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" id="email" name="email" class="form-control" value="{{ $user->email }}" placeholder="{{ trans('message.Enter Email') }}" maxlength="50">
                                    @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- CompanyName and Email Field End -->

                        <!-- Mobile and Landline Field -->
                        <div class="row row-mb-0">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 {{ $errors->has('mobile') ? ' has-error' : '' }}">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="mobile">{{ trans('message.Mobile No') }} <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" id="mobile" name="mobile" class="form-control" value="{{ $user->mobile_no }}" maxlength="16" minlength="6" placeholder="{{ trans('message.Enter Mobile No') }}">
                                    @if ($errors->has('mobile'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('mobile') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 {{ $errors->has('landlineno') ? ' has-error' : '' }}">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="landlineno">{{ trans('message.Landline No') }}</label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" id="landlineno" name="landlineno" class="form-control" value="{{ $user->landline_no }}" maxlength="16" minlength="6" placeholder="{{ trans('message.Enter LandLine No') }}">
                                    @if ($errors->has('landlineno'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('landlineno') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>


                        <!-- ContactPerson and Image Field -->
                        <div class="row row-mb-0">

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 {{ $errors->has('image') ? ' has-error' : '' }}">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="image">{{ trans('message.Image') }}</label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="file" id="image" name="image" class="form-control chooseImage">
                                    <img src="{{ URL::asset('public/supplier/' . $user->image) }}" width="52px" height="52px" id="imagePreview" alt="User Image" class="datatable_img" style="margin-top:10px;">
                                </div>
                            </div>

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group my-form-group has-feedback">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">{{ trans('message.Gender') }}
                                    <label class="color-danger"></label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 gender ps-0">
                                    <input type="radio" name="gender" value="0" <?php if ((int)$user->gender === 0) {
                                                                                    echo 'checked';
                                                                                } ?>> {{ trans('message.Male') }}

                                    <input type="radio" name="gender" value="1" <?php if ((int)$user->gender === 1) {
                                                                                    echo 'checked';
                                                                                } ?>> {{ trans('message.Female') }}
                                </div>
                            </div>
                        </div>

                        <!-- ContactPerson and Image Field End -->

                        <!-- Address Part -->
                        <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 mt-3">
                            <h4><b>{{ trans('message.ADDRESS') }}</b></h4>
                            <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
                        </div>

                        <div class="row row-mb-0">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="country_id">{{ trans('message.Country') }} <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <div class="select-wrapper">
                                        <select class="form-control select_country form-select" name="country_id" countryurl="{!! url('/getstatefromcountry') !!}">
                                            <option value="">{{ trans('message.Select Country') }}</option>
                                            @foreach ($country as $countrys)
                                            <option value="{{ $countrys->id }}" <?php if ($user->country_id == $countrys->id) {
                                                                                    echo 'selected';
                                                                                } ?>>
                                                {{ $countrys->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="arrow-icon"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="state">{{ trans('message.State') }} </label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <div class="select-wrapper">
                                        <select class="form-control state_of_country form-select" name="state" stateurl="{!! url('/getcityfromstate') !!}">
                                            <option value="">{{ trans('message.Select State') }}</option>
                                            @if (count($state) > 0)
                                            @foreach ($state as $states)
                                            <option value="{!! $states->id !!}" <?php if ($user->state_id == $states->id) {
                                                                                    echo 'selected';
                                                                                } ?>>
                                                {!! $states->name !!}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <div class="arrow-icon"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="city">{{ trans('message.Town/City') }}</label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <div class="select-wrapper">
                                        <select class="form-control city_of_state form-select" name="city">
                                            <option value="">{{ trans('message.Select City') }}</option>
                                            @if (count($city) > 0)
                                            @foreach ($city as $citys)
                                            <option value="{!! $citys->id !!}" <?php if ($user->city_id == $citys->id) {
                                                                                    echo 'selected';
                                                                                } ?>>
                                                {!! $citys->name !!}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <div class="arrow-icon"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="address">{{ trans('message.Address') }} <label class="color-danger">*</label>
                                </label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <textarea id="address" name="address" maxlength="100" class="form-control addressTextarea">{{ $user->address }}</textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Address Part End-->

                        <!-- Note Functionality -->
                        <div class="row col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 form-group note-row">
                            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <h2 class="fw-bold">{{ trans('message.Add Notes') }} </h2></span>
                            </div>
                            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 text-end">
                                <button type="button" class="btn btn-outline-secondary btn-sm addNotes mt-1 fl margin-left-0">{{ trans('+') }}</button><br>
                            </div>
                            <hr>
                            @foreach ($user->notes as $key => $note)
                            <div class="row notes-row" id="notes-{{ $key }}">
                                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2" for="">{{ trans('message.Notes') }} <label class="color-danger"></label></label>
                                <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3">
                                    <textarea class="form-control" id="" name="notes[{{ $key }}][note_text]" maxlength="100">{{ $note->notes }}</textarea>
                                    <input type="hidden" name="notes[{{ $key }}][note_id]" value="{{ $note->id }}">
                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3 form-group my-form-group">
                                    <input type="file" name="notes[{{ $key }}][note_file][]" class="form-control imageclass my-2" data-max-file-size="5M" accept="image/*,application/pdf,video/*" multiple />
                                    <div class="row mt-2" id="image_preview">
                                        @php
                                        $attachmentIds = json_decode($note->attachment, true);
                                        $attachments = \App\note_attachments::where('note_id','=', $note->id)->get();
                                        @endphp
                                        @foreach ($attachments as $attachment)
                                        @php
                                        $extension = pathinfo($attachment->attachment, PATHINFO_EXTENSION);
                                        $attachmentUrl = URL::asset('public/notes/' . basename($attachment->attachment));
                                        @endphp
                                        <div class="col-md-3 col-sm-3 col-xs-12 removeimage delete_image" id="image_remove_<?php echo $attachment->id; ?>" imgaeid="{{ $attachment->id }}" delete_image="{!! url('/deleteAttachment') !!}">
                                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                            <a href=""><img src="{{ $attachmentUrl }}" width="50px" height="50px">
                                                <p class="text">{{ trans('message.Remove') }}</p>
                                            </a>
                                            @elseif ($extension === 'pdf')
                                            <a href=""><img src="{{ asset('public/img/icons/pdf_download.png') }}" width="50px" height="50px">
                                                <p class="text">{{ trans('message.Remove') }}</p>
                                            </a>
                                            @else
                                            <a href=""><img src="{{ asset('public/img/icons/video.png') }}" width="50px" height="50px">
                                                <p class="text">{{ trans('message.Remove') }}</p>
                                            </a>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3 pt-0">
                                    <div class="d-flex">
                                        <input type="checkbox" name="notes[{{ $key }}][internal]" id="" class="form-check" style="height:20px; width:20px; margin-right:5px; position: relative; top: 1px; margin-bottom: 12px;" {{ $note->internal ? 'checked' : '' }}>
                                        <label class="control-label pt-1" for="">{{ trans('message.Internal Notes') }} <label class="text-danger"></label></label>
                                    </div>
                                    <div class="d-flex">
                                        <input type="checkbox" name="notes[{{ $key }}][shared]" id="" class="form-check" style="height:20px; width:20px; margin-right:5px; position: relative; top: 1px; margin-bottom: 12px;" {{ $note->shared_with_customer ? 'checked' : '' }}>
                                        <label class="control-label pt-1" for="">{{ trans('message.Shared with supplier') }} <label class="text-danger"></label></label>
                                    </div>
                                </div>
                                <div class="col-md-1 col-lg-1 col-xl-1 col-xxl-1 col-sm-1 col-xs-1 text-center pt-3">
                                   <i class="fa fa-trash fa-2x deletedatas" url="{!! url('/deleteNote/' . $note->id ) !!}"></i>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- CustomField Part -->
                        @if (!$tbl_custom_fields->isEmpty())
                        <div class="col-md-12 col-xs-12 col-sm-12 space">
                            <h4><b>{{ trans('message.CUSTOM FIELDS') }}</b></h4>
                            <p class="col-md-12 col-xs-12 col-sm-12 ln_solid"></p>
                        </div>
                        <?php
                        $subDivCount = 0;
                        ?>
                        @foreach ($tbl_custom_fields as $myCounts => $tbl_custom_field)
                        <?php
                        if ($tbl_custom_field->required == 'yes') {
                            $required = 'required';
                            $red = '*';
                        } else {
                            $required = '';
                            $red = '';
                        }

                        $tbl_custom = $tbl_custom_field->id;
                        $userid = $user->id;
                        $datavalue = getCustomData($tbl_custom, $userid);

                        $subDivCount++;
                        ?>

                        @if ($myCounts % 2 == 0)
                        <div class="row">
                            @endif

                            <div class="row col-md-6  col-sm-6 col-xs-12 error_customfield_main_div_{{ $myCounts }}">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="account-no">{{ $tbl_custom_field->label }} <label class="color-danger">{{ $red }}</label></label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    @if ($tbl_custom_field->type == 'textarea')
                                    <textarea name="custom[{{ $tbl_custom_field->id }}]" class="form-control textarea_{{ $tbl_custom_field->id }} textarea_simple_class common_simple_class common_value_is_{{ $myCounts }}" placeholder="{{ trans('message.Enter') }} {{ $tbl_custom_field->label }}" maxlength="100" isRequire="{{ $required }}" type="textarea" fieldNameIs="{{ $tbl_custom_field->label }}" rows_id="{{ $myCounts }}" {{ $required }}>{{ $datavalue }}</textarea>

                                    <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger" style="display: none"></span>
                                    @elseif($tbl_custom_field->type == 'radio')
                                    <?php
                                    $radioLabelArrayList = getRadiolabelsList($tbl_custom_field->id);
                                    ?>
                                    @if (!empty($radioLabelArrayList))
                                    <div style="margin-top: 5px;">
                                        @foreach ($radioLabelArrayList as $k => $val)
                                        <input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}]" value="{{ $k }}" <?php
                                                                                                                                                $getRadioValue = getRadioLabelValueForUpdate($user->id, $tbl_custom_field->id);

                                                                                                                                                if ($k == $getRadioValue) {
                                                                                                                                                    echo 'checked';
                                                                                                                                                } ?>>
                                        {{ $val }} &nbsp;
                                        @endforeach
                                    </div>
                                    @endif
                                    @elseif($tbl_custom_field->type == 'checkbox')
                                    <?php
                                    $checkboxLabelArrayList = getCheckboxLabelsList($tbl_custom_field->id);
                                    ?>
                                    @if (!empty($checkboxLabelArrayList))
                                    <?php
                                    $getCheckboxValue = getCheckboxLabelValueForUpdate($user->id, $tbl_custom_field->id);
                                    ?>
                                    <div class="required_checkbox_parent_div_{{ $tbl_custom_field->id }}" style="margin-top: 5px;">
                                        @foreach ($checkboxLabelArrayList as $k => $val)
                                        <input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}][]" value="{{ $val }} " isRequire="{{ $required }}" fieldNameIs="{{ $tbl_custom_field->label }}" custm_isd="{{ $tbl_custom_field->id }}" class="checkbox_{{ $tbl_custom_field->id }} required_checkbox_{{ $tbl_custom_field->id }} checkbox_simple_class common_value_is_{{ $myCounts }} common_simple_class" rows_id="{{ $myCounts }}" <?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                                        if ($val == getCheckboxVal($user->id, $tbl_custom_field->id, $val)) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                            echo 'checked';
                                                                                                                                                                                                                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ?>>
                                        {{ $val }} &nbsp;
                                        @endforeach
                                        <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger" style="display: none"></span>
                                    </div>
                                    @endif
                                    @elseif($tbl_custom_field->type == 'textbox')
                                    <input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}]" placeholder="{{ trans('message.Enter') }} {{ $tbl_custom_field->label }}" value="{{ $datavalue }}" maxlength="30" class="form-control textDate_{{ $tbl_custom_field->id }} textdate_simple_class common_value_is_{{ $myCounts }} common_simple_class" isRequire="{{ $required }}" fieldNameIs="{{ $tbl_custom_field->label }}" rows_id="{{ $myCounts }}" {{ $required }}>

                                    <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger" style="display:none"></span>
                                    @elseif($tbl_custom_field->type == 'date')
                                    <input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}]" placeholder="{{ trans('message.Enter') }}  {{ $tbl_custom_field->label }}" value="{{ $datavalue }}" maxlength="30" class="form-control datepicker textDate_{{ $tbl_custom_field->id }} date_simple_class common_value_is_{{ $myCounts }} common_simple_class" isRequire="{{ $required }}" fieldNameIs="{{ $tbl_custom_field->label }}" rows_id="{{ $myCounts }}" onkeydown="return false" {{ $required }}>

                                    <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger" style="display:none"></span>
                                    @endif
                                </div>
                            </div>
                            @if ($myCounts % 2 != 0)
                            @endif
                            @endforeach
                            <?php
                            if ($subDivCount % 2 != 0) {
                                echo '</div>';
                            }
                            ?>
                            @endif
                            <!-- Custom Field Part End-->

                            <!-- Submit and Cancel Part -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="row">
                                <!-- <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-1 mx-0">
                                    <a class="btn btn-primary" href="{{ URL::previous() }}">{{ trans('message.CANCEL') }}</a>
                                </div> -->
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-1 mx-0">
                                    <button type="submit" class="btn btn-success updateSupplierButton">{{ trans('message.UPDATE') }}</button>
                                </div>
                            </div>
                            <!-- Submit and Cancel Part End-->
                    </form>
                    <!-- Supplier Edit Form End -->
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>


<!-- Scripts starting -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select_country').change(function() {
            countryid = $(this).val();
            var url = $(this).attr('countryurl');
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    countryid: countryid
                },
                success: function(response) {
                    $('.state_of_country').html(response);
                }
            });
        });

        $('body').on('change', '.state_of_country', function() {
            stateid = $(this).val();

            var url = $(this).attr('stateurl');
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    stateid: stateid
                },
                success: function(response) {
                    $('.city_of_state').html(response);
                }
            });
        });


        // Basic
        $('.dropify').dropify();

        // Translated
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                remove: 'Supprimer',
                error: 'Désolé, le fichier trop volumineux'
            }
        });

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();

            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })


        $('.datepicker').datetimepicker({
            format: "<?php echo getDatepicker(); ?>",
            todayBtn: true,
            autoclose: 1,
            minView: 2,
            endDate: new Date(),

        });



        /*For image preview at selected image*/
        function readUrl(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#image").change(function() {
            readUrl(this);
            $("#imagePreview").css("display", "block");
        });

        $('body').on('change', '.chooseImage', function() {
            var imageName = $(this).val();
            var imageExtension = /(\.jpg|\.jpeg|\.png)$/i;

            if (imageExtension.test(imageName)) {
                $('.imageHideShow').css({
                    "display": ""
                });
            } else {
                $('.imageHideShow').css({
                    "display": "none"
                });
            }
        });


        /*If any white space for companyname, firstname, lastname and addresstext are then make empty value of these all field*/
        $('body').on('keyup', '.companyname', function() {

            var companyName = $(this).val();

            if (!companyName.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '#firstname', function() {

            var firstName = $(this).val();

            if (!firstName.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '#lastname', function() {

            var lastName = $(this).val();

            if (!lastName.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '.addressTextarea', function() {

            var addressValue = $(this).val();

            if (!addressValue.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        /*images show in multiple in for loop*/
        $(".imageclass").click(function() {
            $(".classimage").empty();
        });

        function preview_images() {
            var total_file = document.getElementById("images").files.length;
            for (var i = 0; i < total_file; i++) {
                $('#image_preview').append(
                    "<div class='col-md-3 col-sm-3 col-xs-12 removeimage delete_image classimage'><img src='" + URL
                    .createObjectURL(event.target.files[i]) + "' width='100px' height='60px'> </div>");
            }
        }

        $('body').on('click', '.delete_image', function() {

            var delete_image = $(this).attr('imgaeid');
            var url = $(this).attr('delete_image');

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    delete_image: delete_image
                },
                success: function(response) {
                    $('div#image_preview div#image_remove_' + delete_image).remove();
                },
                error: function(e) {
                    alert(msg100 + " " + e.responseText);
                    console.log(e);
                }
            });
            return false;
        });


    });


    /*Custom Field manually validation*/
    var msg1 = "{{ trans('message.field is required') }}";
    var msg2 = "{{ trans('message.Only blank space not allowed') }}";
    var msg3 = "{{ trans('message.Special symbols are not allowed.') }}";
    var msg4 = "{{ trans('message.At first position only alphabets are allowed.') }}";

    /*Form submit time check validation for Custom Fields */
    $('body').on('click', '.updateSupplierButton', function(e) {
        $('#supplier_edit_form input, #supplier_edit_form select, #supplier_edit_form textarea').each(

            function(index) {
                var input = $(this);

                if (input.attr('name') == "displayname" || input.attr('name') == "email" || input.attr(
                        'name') ==
                    "mobile" || input.attr('name') == "country_id" || input.attr('name') == "address") {
                    if (input.val() == "") {
                        return true;
                    } else {
                        return true;
                    }
                } else if (input.attr('isRequire') == 'required') {
                    var rowid = (input.attr('rows_id'));
                    var labelName = (input.attr('fieldnameis'));

                    if (input.attr('type') == 'textbox' || input.attr('type') == 'textarea') {
                        if (input.val() == '' || input.val() == null) {
                            $('.common_value_is_' + rowid).val("");
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                            $('#common_error_span_' + rowid).css({
                                "display": ""
                            });
                            $('.error_customfield_main_div_' + rowid).addClass('has-error');
                            e.preventDefault();
                            return false;
                        } else if (!input.val().replace(/\s/g, '').length) {
                            $('.common_value_is_' + rowid).val("");
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg2);
                            $('#common_error_span_' + rowid).css({
                                "display": ""
                            });
                            $('.error_customfield_main_div_' + rowid).addClass('has-error');
                            e.preventDefault();
                            return false;
                        } else if (!input.val().match(/^[(a-zA-Z0-9\s)\p{L}]+$/u)) {
                            $('.common_value_is_' + rowid).val("");
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg3);
                            $('#common_error_span_' + rowid).css({
                                "display": ""
                            });
                            $('.error_customfield_main_div_' + rowid).addClass('has-error');
                            e.preventDefault();
                            return false;
                        }
                    } else if (input.attr('type') == 'checkbox') {
                        var ids = input.attr('custm_isd');
                        if ($(".required_checkbox_" + ids).is(':checked')) {
                            $('#common_error_span_' + rowid).css({
                                "display": "none"
                            });
                            $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                            $('.required_checkbox_parent_div_' + ids).css({
                                "color": ""
                            });
                            $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                        } else {
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                            $('#common_error_span_' + rowid).css({
                                "display": ""
                            });
                            $('.error_customfield_main_div_' + rowid).addClass('has-error');
                            $('.required_checkbox_' + ids).css({
                                "outline": "2px solid #a94442"
                            });
                            $('.required_checkbox_parent_div_' + ids).css({
                                "color": "#a94442"
                            });
                            e.preventDefault();
                            return false;
                        }
                    } else if (input.attr('type') == 'date') {
                        if (input.val() == '' || input.val() == null) {
                            $('.common_value_is_' + rowid).val("");
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                            $('#common_error_span_' + rowid).css({
                                "display": ""
                            });
                            $('.error_customfield_main_div_' + rowid).addClass('has-error');
                            e.preventDefault();
                            return false;
                        } else {
                            $('#common_error_span_' + rowid).css({
                                "display": "none"
                            });
                            $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                        }
                    }
                } else if (input.attr('isRequire') == "") {
                    //Nothing to do
                }
            }
        );
    });


    /*Anykind of input time check for validation for Textbox, Date and Textarea*/
    $('body').on('keyup', '.common_simple_class', function() {

        var rowid = $(this).attr('rows_id');
        var valueIs = $('.common_value_is_' + rowid).val();
        var requireOrNot = $('.common_value_is_' + rowid).attr('isrequire');
        var labelName = $('.common_value_is_' + rowid).attr('fieldnameis');
        var inputTypes = $('.common_value_is_' + rowid).attr('type');

        if (requireOrNot != "") {
            if (inputTypes != 'radio' && inputTypes != 'checkbox' && inputTypes != 'date') {
                if (valueIs == "") {
                    $('.common_value_is_' + rowid).val("");
                    $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                    $('#common_error_span_' + rowid).css({
                        "display": ""
                    });
                    $('.error_customfield_main_div_' + rowid).addClass('has-error');
                } else if (valueIs.match(/^\s+/)) {
                    $('.common_value_is_' + rowid).val("");
                    $('#common_error_span_' + rowid).text(labelName + " : " + msg4);
                    $('#common_error_span_' + rowid).css({
                        "display": ""
                    });
                    $('.error_customfield_main_div_' + rowid).addClass('has-error');
                } else if (!valueIs.match(/^[(a-zA-Z0-9\s)\p{L}]+$/u)) {
                    $('.common_value_is_' + rowid).val("");
                    $('#common_error_span_' + rowid).text(labelName + " : " + msg3);
                    $('#common_error_span_' + rowid).css({
                        "display": ""
                    });
                    $('.error_customfield_main_div_' + rowid).addClass('has-error');
                } else {
                    $('#common_error_span_' + rowid).css({
                        "display": "none"
                    });
                    $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                }
            } else if (inputTypes == 'date') {
                if (valueIs != "") {
                    $('#common_error_span_' + rowid).css({
                        "display": "none"
                    });
                    $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                } else {
                    $('.common_value_is_' + rowid).val("");
                    $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                    $('#common_error_span_' + rowid).css({
                        "display": ""
                    });
                    $('.error_customfield_main_div_' + rowid).addClass('has-error');
                }
            } else {
                //alert("Yes i am radio and checkbox");
            }
        } else {
            if (inputTypes != 'radio' && inputTypes != 'checkbox' && inputTypes != 'date') {
                if (valueIs != "") {
                    if (valueIs.match(/^\s+/)) {
                        $('.common_value_is_' + rowid).val("");
                        $('#common_error_span_' + rowid).text(labelName + " : " + msg4);
                        $('#common_error_span_' + rowid).css({
                            "display": ""
                        });
                        $('.error_customfield_main_div_' + rowid).addClass('has-error');
                    } else if (!valueIs.match(/^[(a-zA-Z0-9\s)\p{L}]+$/u)) {
                        $('.common_value_is_' + rowid).val("");
                        $('#common_error_span_' + rowid).text(labelName + " : " + msg3);
                        $('#common_error_span_' + rowid).css({
                            "display": ""
                        });
                        $('.error_customfield_main_div_' + rowid).addClass('has-error');
                    } else {
                        $('#common_error_span_' + rowid).css({
                            "display": "none"
                        });
                        $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                    }
                } else {
                    $('#common_error_span_' + rowid).css({
                        "display": "none"
                    });
                    $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                }
            }
        }
    });


    /*For required checkbox checked or not*/
    $('body').on('click', '.checkbox_simple_class', function() {

        var rowid = $(this).attr('rows_id');
        var requireOrNot = $('.common_value_is_' + rowid).attr('isrequire');
        var labelName = $('.common_value_is_' + rowid).attr('fieldnameis');
        var inputTypes = $('.common_value_is_' + rowid).attr('type');
        var custId = $('.common_value_is_' + rowid).attr('custm_isd');

        if (requireOrNot != "") {
            if ($(".required_checkbox_" + custId).is(':checked')) {
                $('.required_checkbox_' + custId).css({
                    "outline": ""
                });
                $('.required_checkbox_' + custId).css({
                    "color": ""
                });
                $('#common_error_span_' + rowid).css({
                    "display": "none"
                });
                $('.required_checkbox_parent_div_' + custId).css({
                    "color": ""
                });
                $('.error_customfield_main_div_' + rowid).removeClass('has-error');
            } else {
                $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                $('.required_checkbox_' + custId).css({
                    "outline": "2px solid #a94442"
                });
                $('.required_checkbox_' + custId).css({
                    "color": "#a94442"
                });
                $('#common_error_span_' + rowid).css({
                    "display": ""
                });
                $('.required_checkbox_parent_div_' + custId).css({
                    "color": "#a94442"
                });
                $('.error_customfield_main_div_' + rowid).addClass('has-error');
            }
        }
    });


    $('body').on('change', '.date_simple_class', function() {

        var rowid = $(this).attr('rows_id');
        var valueIs = $('.common_value_is_' + rowid).val();
        var requireOrNot = $('.common_value_is_' + rowid).attr('isrequire');
        var labelName = $('.common_value_is_' + rowid).attr('fieldnameis');
        var inputTypes = $('.common_value_is_' + rowid).attr('type');
        var custId = $('.common_value_is_' + rowid).attr('custm_isd');

        if (requireOrNot != "") {
            if (valueIs != "") {
                $('#common_error_span_' + rowid).css({
                    "display": "none"
                });
                $('.error_customfield_main_div_' + rowid).removeClass('has-error');
            } else {
                $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                $('#common_error_span_' + rowid).css({
                    "display": ""
                });
                $('.error_customfield_main_div_' + rowid).addClass('has-error');
            }
        }
    });
</script>


<!-- For form field validate -->
{!! JsValidator::formRequest('App\Http\Requests\SupplierAddEditFormRequest', '#supplier_edit_form') !!}
<script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js') }}"></script>

@endsection