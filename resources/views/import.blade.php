@extends('layouts.master')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">CSV: Lisäyslomake</div>
                <div class="card-body">

                @if (session('info_msg'))
                    <div class="alert alert-info">
                        {{ session('info_msg') }}
                    </div>
                @endif

                @if(Session::has('virheet'))
                <div class="alert alert-danger">
                    <h3>CSV-tiedoston lukemisessa virhe</h3>
                    <b>Virhe CSV-tiedoston rivillä {{ Session::get('rivinro') }}</b>
                    <pre> {{ Session::get('rivi') }}</pre>
                    <b>Virheelliset kentät:</b>
                    <ul>
                    @foreach (Session::get('virheet') as $virhe)
                        <li>{!! $virhe !!}</li>
                    @endforeach
                    </ul>
                    </div>
                    @endif

                    <b>CSV-tiedoston formaatti on (ei tule otsikkoriviä)</b>
                    <br><br>
                    Device kentät:<br>
                    device_token; customer_id; admin; admin_password; user_id; operating_system_id; 
                    device_model; device_type_id; device_name; notes; license_id; created_at; 
                    software_id; buy_date; serial_no; warranty_id; warranty_valid_until; teamviewer_id; 
                    security_software_id; active; order_no; vendor_id; product_no; manufacturer_id; 
                    backup_solution_id; lease_contract_no<br>
                    Data:<br>
                    VT166666;Nakki-palvelut Oy;admin / 654645654645; Makunakki, Matti; MS Windows 10 Pro;Lifebook E558;Kannettava;LAPTOP-5P374444;Hiiri Logitech M135;;;15.10.2019 11:50;DeskUpdate, TeamViewer, Office 365, Acrobat Reader, VLCPlayer, Google Chrome, Mozilla Firefox;14.11.2018;DSAD0121236;On-Site;20.11.2023;1115948691;Computer Protection xxxx-xxxx-xxxx-xxxx-xxxx;Aktiivinen;12341515;Also;PCK:E5583210ONC;Fujitsu;OneDrive;
                    <br><br>
                    Customer:<br>
                    customer_token;name;address;contact_person_name;active;notes
                    1;testaus tatti;nakkitie 666;Supernakki;1;tärkeät nodet
                    <br><br>
                    Account:<br>
                    Toimii outlookista ladatun contacts csv tiedoston kanssa. Tiedostosta tulee löytyä vähintään:
                    First Name, Last Name, E-mail Address, Business Phone tai Mobile Phone, Company, notes
                    <br><br>
                    <h2 id="uploadhead">Lisää dataa CSV-tiedostosta</h2>
                    <hr>
                    <form method="POST" enctype="multipart/form-data" action="{{ url('/csvsaved')}}">
                    @csrf
                        Mitä ladattavalla CSV tiedostolla halutaan tehdä?<br>
                        <select id="csvfilemeaning" name="csvfilemeaning" class="btn btn-dark">
                            <option value=0>Create Device CSV</option>
                            <option value=1>Create Customer CSV</option>
                            <option value=2>Create Account CSV</option>
                        </select>
                        <br><br>CSV-tiedosto:<br>
                        <input name="csvfile" type="file">
                        <br><br>
                        <input type="submit" name="nappi" value="Lataa CSV-tiedosto" class="btn btn-dark">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection