@extends('layouts.master')

@section('content')
<div class="container">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <table id="dashboardTable" class="table table-striped table-hover table-responsive-lg">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Address</th>
                        <th scope="col">Updated at</th>
                        <th scope="col">Active</th>
                    </tr>
                </thead>
                <tbody>

@foreach ($customers as $customer)

<tr style='cursor:pointer' onclick="window.location='customer/{{ $customer->id }}'">
    <th scope="row">{{ $customer->id }}</th>
    <td>{{ $customer->name }}</td>
    <td>{{ $customer->address }}</td>
    <td style="text-align:center">{{ $customer->updated_at }}</td>
@if($customer->active)
    <td style="background:linear-gradient(45deg,rgb(2,170,176,0.2),rgb(0,205,172,0.5));text-align:center">Yes</td>
@else
    <td style="background:linear-gradient(45deg,rgb(238,156,167,0.5),rgb(255,221,225,0.2));text-align:center">No</td>
@endif
</tr>

@endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection