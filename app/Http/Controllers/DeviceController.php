<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;

class DeviceController extends Controller
{
    // list all devices
    public function listDevices($customerID) {
        //$customer = Customer::find($id);
        // Kokeile myös usein parempaa
        $devices = Device::all()->where('customer_id', $customerID);
        //jokukansio/blade (devices)
        return $devices;
    }

    // get device
    public function showDevice($customerID, $devID) {
        $device = Device::findOrFail($devID);
        //dd($device);
        return view('device')->with('device', $device);
    }
}
