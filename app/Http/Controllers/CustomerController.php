<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Tuodaan Customer-luokka nimiavaruuksineen käyttöön
use App\Customer;

class CustomerController extends Controller
{
    public function listCustomers()
    {
        // haetaan kaikki asiakkaat ja palautetaan ne dashboard-näkymään
        $customers = Customer::all();
        //dd($customers);
        return view('dashboard')->with('customers', $customers);
    }

    // show
    public function showCustomer($customerID) {
        // Haetaan asiakkaan tiedot asiakkaan id:llä
        $customer = Customer::findOrFail($customerID);
        $devices = $customer->devices;
        $users = $customer->users;
        //dd($devices);
        // palautetaan näkymä asiakkaan tiedoilla ja laitteilla
        return view('customer')->with('customer', $customer)->with('devices', $devices);
    }

    // create
    public function create() {
        return view('addnewitem');
    }

    // save
    public function store() {
        // asiakkaan tallentamiseen käytetyt validoinnit
        request()->validate([
            'customer_token' => ['required', 'regex:/^\d{1,255}$/'],
            'name' => ['required', 'regex:/^.{1,255}$/'],
            'address' => ['required', 'regex:/^.{1,255}$/'],
            'contact_person_name' => ['regex:/^.{0,255}$/'],
            'active' => ['required', 'regex:/^[0|1]$/'],
            'notes' => ['regex:/^.{0,1000}$/'],
        ],
        ['customer_token.regex' => 'Sallitaan: 1-255kpl numeroa'],
        ['name.regex' => 'Sallitaan: 1-255kpl mitä tahansa merkkiä'],
        ['address.regex' => 'Sallitaan: 1-255kpl mitä tahansa merkkiä'],
        ['contact_person_name.regex' => 'Sallitaan: 0-255kpl mitä tahansa merkkiä'],
        ['active.regex' => 'Sallitaan: Vain 0 tai 1'],
        ['notes.regex' => 'Sallitaan: 0-1000kpl mitä tahansa merkkiä']
        );
    }
}
