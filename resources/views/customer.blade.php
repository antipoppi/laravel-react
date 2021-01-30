@extends('layouts.master')
@section('content')
<div class="container" id="customerDevices">
    <div class="col-md-12 mx-auto">
        <div class="card">
            <div class="card-header text-white bg-secondary ">
                <h3 id="customerNameHeader">{{$customer->name}}</h3>
                <ul class="nav nav-tabs card-header-tabs" id="customerCard" role="tablist">
                    <li class="nav-item rounded bg-dark">
                        <a class="nav-link active" href="#devices" role="tab" aria-controls="devices" aria-selected="true">Devices</a>
                    </li>
                    <li class="nav-item rounded bg-dark">
                        <a class="nav-link" href="#users" role="tab" aria-controls="users" aria-selected="false">Users</a>
                    </li>
                    <li class="nav-item rounded bg-dark">
                        <a class="nav-link" href="#customerInfo" role="tab" aria-controls="customerInfo" aria-selected="false">Customer info</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="devices" role="tabpanel">
                        <div class="table-responsive-lg">
            @if(!$devices->isEmpty())
                            <table  id="customerDevicesTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Device token</th>
                                        <th scope="col">Admin</th>
                                        <th scope="col">Admin password</th>
                                        <th scope="col">Device name</th>
                                        <th scope="col">Device model</th>
                                        <th scope="col">Teamviewer ID</th>
                                        <th shope="col">User</th>
                                        <th scope="col">Security</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($devices as $device)
                                    @if($device->active)
                                    <tr style="background:linear-gradient(45deg,rgb(2,170,176,0.2),rgb(0,205,172,0.5))">
                                    @else
                                    <tr style="background:linear-gradient(45deg,rgb(238,156,167,0.5),rgb(255,221,225,0.2))">
                                    @endif
                                        <th scope="row"><a href="/harjoitustyo/public/customer/{{ $customer->id }}/{{ $device->id }}">{{ $device->device_token }}</a></th>
                                        <td>{{ $device->admin }}</td>
                                        <td class="text-nowrap">
                                            <input type="password" value="{{ $device->admin_password }}" style="all:unset" disabled>
                                            <i class="fa fa-eye eyeicon" aria-hidden="true"></i>
                                        </td>
                                        <td>{{ $device->device_name }}</td>
                                        <td>{{ $device->device_model }}</td>
                                        <td>{{ $device->teamviewer_id }}</td>
                                        <td>{{ $device->user->name }}</td>
                                        @if($device->securitySoftware == null)
                                        <td></td>
                                        @else
                                        <td>
                                            <img 
                                                src="/harjoitustyo/public/img/{{ $device->securitySoftware->description }}.png" 
                                                width="30" 
                                                alt="{{ $device->securitySoftware->description }}" 
                                                class="img-circle"
                                            >
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
            @else
                        <div class="card-body">
                            <span>Customer does not have any devices</span>
                        </div>
                    </div>
                </div>
            @endif
                    <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
                        <div class="table-responsive-lg">
                    @if(!$customer->users->isEmpty())
                            <table id="customerUsersTable" class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($customer->users as $user)
                                    @if($user->active)
                                    <tr style="background:linear-gradient(45deg,rgb(2,170,176,0.2),rgb(0,205,172,0.5))">
                                    @else
                                    <tr style="background:linear-gradient(45deg,rgb(238,156,167,0.5),rgb(255,221,225,0.2))">
                                    @endif
                                        <th scope="row">{{ $user->name }}</th>
                                        <td>{{ $user->tel }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->notes }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
            @else
                                <div class="card-body">
                                    <span>Customer does not have any users</span>
                                </div>
                        </div>
                    </div>
            @endif
                    <div class="tab-pane fade" id="customerInfo" role="tabpanel" aria-labelledby="users-tab">
                        <div class="container mx-auto col-sm-8">
                            <div class="card border-dark">
                                <div class="card-header text-center bg-light border-dark" style="height:45px">
                                    <h4>Edit info</h4>
                                </div>
                                @if($customer->active)
                                <div class="card-body" style="background:linear-gradient(45deg,rgb(2,170,176,0.1),rgb(0,205,172,0.4))">
                                @else
                                <div class="card-body" style="background:linear-gradient(45deg,rgb(238,156,167,0.5),rgb(255,221,225,0.2))">
                                @endif
                                    <form id="customerInfoForm">
                                        @csrf
                                        <div class="form-group row">
                                            <label for="customerId" class="col-md-3 col-form-label-sm font-weight-bold" style="text-align:right">id</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control form-control-sm" id="customerID" value="{{ $customer->id }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="customerToken" class="col-md-3 col-form-label-sm font-weight-bold" style="text-align:right">Customer token</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control form-control-sm" id="customerToken" value="{{ $customer->customer_token }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="customerName" class="col-md-3 col-form-label-sm font-weight-bold" style="text-align:right">Name</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control form-control-sm" id="customerName" value="{{ $customer->name }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="customerAddress" class="col-md-3 col-form-label-sm font-weight-bold" style="text-align:right">Address</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control form-control-sm" id="customerAddress" value="{{ $customer->address }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="customerContactPers" class="col-md-3 col-form-label-sm font-weight-bold" style="text-align:right">Contact person name</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control form-control-sm" id="customerContactPers" value="{{ $customer->contact_person_name }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="customerNotes" class="col-md-3 col-form-label-sm font-weight-bold" style="text-align:right">Notes</label>
                                            <div class="col-md-9">
                                                <textarea class="form-control form-control-sm" id="customerNotes" value="{{ $customer->notes }}"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="customerActive" class="col-md-11 col-form-label-sm font-weight-bold" style="text-align:right">Active</label>
                                            <div class="col-md-1">
                                            @if($customer->active)
                                                <input class="form-control-sm float-right" type="checkbox" id="customerActive" checked style="background:black">
                                            @else
                                                <input class="form-control-sm float-right" type="checkbox" id="customerActive">
                                            @endif
                                            </div>
                                        </div>
                                        <button id="btnCustomerInfoSubmit" type="submit" class="btn btn-dark float-right" style="color:#f39200">Save</button>
                                    </form>
                                </div>
                            </div>
                            <div id="customerAlert" role="alert" class="mb-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>
@endsection
