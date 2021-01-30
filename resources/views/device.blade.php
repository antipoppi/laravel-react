@extends('layouts.master')
@section('content')
<div class="container" id="customerDevice">
    <div class="col-md-12 mx-auto">
        <div class="card border-dark">
            <div class="card-header text-center bg-light border-dark" style="height:45px">
                <h4>Edit info</h4>
            </div>
        @if($device->active)
            <div class="card-body" style="background:linear-gradient(45deg,rgb(2,170,176,0.1),rgb(0,205,172,0.4))">
        @else
            <div class="card-body" style="background:linear-gradient(45deg,rgb(238,156,167,0.5),rgb(255,221,225,0.2))">
        @endif
                <form id="deviceInfoForm">
                    @csrf
                    <div class="form-group row">
                        <label for="deviceToken" class="col-md-3 col-form-label-sm font-weight-bold" style="text-align:right">Device Token</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control form-control-sm" id="deviceToken" value="{{ $device->device_token }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="deviceAdmin" class="col-md-3 col-form-label-sm font-weight-bold" style="text-align:right">Admin</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control form-control-sm" id="deviceAdmin" value="{{ $device->admin }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="deviceAdminPwd" class="col-md-3 col-form-label-sm font-weight-bold" style="text-align:right">Admin pwd</label>
                        <div class="form-row">
                            <div class="col-md-5">
                                <input id="deviceAdminPwd" type="password" value="{{ $device->admin_password }}" class="form-control form-control-sm ml-3">
                            </div>
                            <div class="col ml-2">
                                <i class="fa fa-eye eyeicon" aria-hidden="true"></i>
                            </div>
                            <div class="col-md-2">
                                <button id="genPwdBtn" class="btn btn-dark btn-sm float-right" type="button">generate password</button>
                            </div>
                            <div class="col-md-3">
                                <input type="text" id="genPwdField" class="form-control form-control-sm ml-3">
                            </div>
                            
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="deviceActive" class="col-md-11 col-form-label-sm font-weight-bold" style="text-align:right">Active</label>
                        <div class="col-md-1">
                        @if($device->active)
                            <input class="form-control-sm float-right" type="checkbox" id="deviceActive" checked>
                        @else
                            <input class="form-control-sm float-right" type="checkbox" id="deviceActive">
                        @endif
                        </div>
                    </div>
                    <button id="btnDeviceInfoSubmit" type="submit" class="btn btn-dark float-right" style="color:#f39200" disabled>Save</button>
                    <h3 class="mx-auto text-center">WIP</h3>
                </form>
            </div>
        </div>
        <div id="deviceAlert" role="alert" class="mb-2"></div>
    </div>
</div>
@endsection