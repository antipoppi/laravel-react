<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Requests\CsvDeviceStoreRequest;
use App\Http\Requests\CsvCustomerStoreRequest;
use App\Http\Requests\CsvUserStoreRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Exception;
use Session;
use Alert;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Customer;
use App\User;
use App\OperatingSystem;
use App\Device;
use App\DeviceType;
use App\License;
use App\Software;
use App\Warrantie;
use App\SecuritySoftware;
use App\Vendor;
use App\Manufacturer;
use App\BackupSolution;

class CsvController2 extends Controller
{
    // Haetaan kaikki henkilön laitteet
    public function getDevices($customerId) {
        return view('export'); //blade sivu tähän
    }

    // Haetaan kaikki laitetiedot
    public function getInfo($deviceId) {
        return view('export'); //blade sivu tähän
    }

    // Haetaan kaikki tiedot
    public function getAll() {
        return view('export'); //blade sivu tähän
    }

    // Tiedostojen lataamista varten, täysin kesken
    public function exportHandler(Request $request) {
        //
    }

    // CSV-tiedoston lataaminen palvelimelle, lukeminen/muototarkistus ja tallentaminen tietokantaan
    // numero kertoo mihin tauluun lisätään (0=device, 1=customer, 2=account)
    public function importHandler(Request $request)
    {

        $option = $request->csvfilemeaning;
        $request_model = array();
        $db_model = null;
        $db = null;
        $rivinro = 0;
        $path = "";
        $fk_request_model = Array();
        $row_headers = Array();
        $success_counter = 0;
        $success_rows = Array();


        // taulun tarkoitus: avain = sama, mikä request_modelissa (helppo mätchäys),
        // arvoista ensimmäinen menee validaattorille (kentän nimi),
        // toinen arvo on taulu, mistä kenttä löytyy, menee myöhemmin DB:n query builderille,
        // kolmas arvo on avaimen nimi em. taulussa, menee myös query builderille
        $foreign_keys = Array();
        $foreign_keys['customer_name'] = ['customer_id', 'customers', 'name'];
        $foreign_keys['user_name'] = ['user_id', 'users', 'name'];
        $foreign_keys['operating_system_name'] = ['operating_system_id', 'operating_systems', 'name'];
        $foreign_keys['device_type_type'] = ['device_type_id', 'device_types', 'type'];
        $foreign_keys['license_name'] = ['license_id', 'licenses', 'name'];
        $foreign_keys['software_items'] = ['software_id', 'softwares', 'items'];
        $foreign_keys['warranty_description'] = ['warranty_id', 'warranties', 'description'];
        $foreign_keys['security_software_description'] = ['security_software_id', 'security_softwares', 'description'];
        $foreign_keys['vendor_name'] = ['vendor_id', 'vendors', 'name'];
        $foreign_keys['manufacturer_name'] = ['manufacturer_id', 'manufacturers', 'name'];
        $foreign_keys['backup_solution_description'] = ['backup_solution_id', 'backup_solutions', 'description'];


            // Jos lomakkeelta tuli CSV-tiedosto, tallennetaan se aikaleiman
            // mukaiselle nimelle esim.
            // storage/app/[tiedostonnimi]-device-2018-10-21--06-59-48.csv

            if ($request->hasFile('csvfile')) {
                $filenameWithExt = $request->file('csvfile')->getClientOriginalName();
                //get filename, esim. harkat
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                //get ext esim. csv
                $extension = $request->file('csvfile')->getClientOriginalExtension();
                //Filename to storage
                $fileNameToStore = $filename . '-device-' . date("Y-m-d--H-i-s") . '.' . $extension;
                //Upload file
                $path = $request->file('csvfile')->storeAs('public', $fileNameToStore);
            }

            $filename = base_path("/storage/app/$path");
            $file = fopen($filename, "r");

            // Luetaan CSV-tiedostoa riveittäin
            // Tässä ei virheenkäsittelyä: oletetaan riviltä löytyvän 26 kenttää
            // Tiedostossa tulee olla kentät tietyssä järjestyksessä ja alla oleva järjestys on toimeksiantajan esimerkkitiedoston mukainen

            while (($data = fgetcsv($file, 2000, ";")) !== FALSE) {
                if ($option == 0) {
                    $headers = ['Laitetunnus', 'Asiakkaan nimi', 'Admin', 'Admin-salasana', 'Käyttäjän nimi',
                        'Käyttöjärjestelmä', 'Laitteen malli', 'Laitetyyppi', 'Laitten nimi', 'Lisälaitteet', 'Muistiinpanot',
                        'Lisenssit', 'Luotu', 'Ohjelmisto(t)','Ostopäivä', 'Sarjanumero', 'Takuun tyyppi', 'Takuu, voimassa asti',
                        'Teamviewer ID', 'Tietoturvaohjelmistot', 'Aktiivisuus', 'Tilausnumero', 'Toimittaja',
                        'Tuotenumero', 'Valmistaja', 'Varmuuskopiointi', 'Leasing sopimusnumero'];
                    $row_headers = $headers;
                    $db = 'devices';
                    $db_model = new Device;
                    $rivinro++;
                    $request_model['device_token'] = trim($data[0]);
                    //customer_name
                    $fk_request_model['customer_name'] = trim($data[1]);
                    $request_model['customer_name'] = trim($data[1]);
                    $adminAndPassword = !empty($data[2]) ? explode("/", trim($data[2])) : null;
                    $request_model['admin'] = !empty($adminAndPassword[0]) ? trim($adminAndPassword[0]) : null;
                    $request_model['admin_password'] = !empty($adminAndPassword[1]) ? trim($adminAndPassword[1]) : null;

                    //user_name
                    $fk_request_model['user_name'] = trim($data[3]);
                    $request_model['user_name'] = trim($data[3]);

                    //operating_system_name
                    $fk_request_model['operating_system_name'] = trim($data[4]);
                    $request_model['operating_system_name'] = trim($data[4]);
                    $request_model['device_model'] = trim($data[5]);

                    //device_type_type (??)
                    $fk_request_model['device_type_type'] = trim($data[6]);
                    $request_model['device_type_type'] = trim($data[6]);
                    $request_model['device_name'] = trim($data[7]);
                    $request_model['addon_device_description'] = trim($data[8]);     //ei lisätä devices tauluun vaan addon devices tauluun
                    $request_model['notes'] = trim($data[9]);

                    //license_name
                    $fk_request_model['license_name'] = trim($data[10]);
                    $request_model['license_name'] = trim($data[10]);
                    $created_at_date = !empty(trim($data[11])) ? date_create(trim($data[11]), timezone_open("Europe/Oslo")) : null;
                    $request_model['created_at'] = $created_at_date != null ? date_format($created_at_date, "Y-m-d H:i:s") : null;

                    //software_items
                    $fk_request_model['software_items'] = trim($data[12]);
                    $request_model['software_items'] = trim($data[12]);
                    $buy_date_at = !empty(trim($data[13])) ? date_create(trim($data[13]), timezone_open("Europe/Oslo")) : null;
                    $request_model['buy_date'] = $buy_date_at != null ? date_format($buy_date_at, "Y-m-d") : null;
                    $request_model['serial_no'] = trim($data[14]);
                    //warranty_description
                    $fk_request_model['warranty_description'] = trim($data[15]);
                    $request_model['warranty_description'] = trim($data[15]);
                    $warranty_valid_until_at = !empty(trim($data[16])) ? date_create(trim($data[16]), timezone_open("Europe/Oslo")) : null;
                    $request_model['warranty_valid_until'] = $warranty_valid_until_at != null ? date_format($warranty_valid_until_at, "Y-m-d") : null;
                    $request_model['teamviewer_id'] = trim($data[17]);

                    //security_software_description
                    $fk_request_model['security_software_description'] = trim($data[18]);
                    $request_model['security_software_description'] = trim($data[18]);
                    $request_model['active'] = trim($data[19]);
                    $request_model['order_no'] = trim($data[20]);

                    //vendor_name
                    $fk_request_model['vendor_name'] = trim($data[21]);
                    $request_model['vendor_name'] = trim($data[21]);
                    $request_model['product_no'] = trim($data[22]);

                    //manufacturer_name
                    $fk_request_model['manufacturer_name'] = trim($data[23]);
                    $request_model['manufacturer_name'] = trim($data[23]);

                    //backup_solution_description
                    $fk_request_model['backup_solution_description'] = trim($data[24]);
                    $request_model['backup_solution_description'] = trim($data[24]);
                    $request_model['lease_contract_no'] = trim($data[25]) ? trim($data[25]) : null;


                } elseif ($option == 1) {
                    $db = 'customers';
                    $db_model = new Customer;
                    $rivinro++;

                    $request_model['customer_token'] = trim($data[0]) ? trim($data[0]) : null;
                    $request_model['name'] = trim($data[1]) ? trim($data[1]) : null;
                    $request_model['address'] = trim($data[2]) ? trim($data[2]) : null;
                    $request_model['contact_person_name'] = trim($data[3]) ? trim($data[3]) : null;
                    $request_model['active'] = trim($data[4]) ? trim($data[4]) : null;
                    $request_model['notes'] = trim($data[5]) ? trim($data[5]) : null;

                }elseif ($option == 2) {
                    $db = 'users';
                    $db_model = new User;
                    $rivinro++;

                    $request_model['customer_id'] = trim($data[4]) ? trim($data[4]) : null;
                    $surname = trim($data[0]) ? trim($data[0]) : null;
                    $family_name = trim($data[2]) ? trim($data[2]) : null;
                    if ($surname and $family_name) {
                        $request_model['name'] = $family_name . ", " . $surname; //sukunimi, etunimi
                    } else {
                        if ($surname) {
                            $request_model['name'] = $surname;
                        } elseif ($family_name) {
                            $request_model['name'] = $family_name;
                        } else {
                            $request_model['name'] = null;
                        }
                    }
                    $request_model['notes'] = !empty(trim($data[64])) ? trim($data[64]) : null;
                    $request_model['user_role_id'] = 4;  // restricted
                    $request_model['password'] = ""; //Hash::make($data[0].$data[2]); //hashataan password samalla kun luetaan se, muutos: hash pois käytöstä; kirjautuminen estetty
                    $request_model['active'] = 1; //aktiivinen = 1
                    $primary_email = !empty(trim($data[74])) ? trim($data[74]) : null;
                    $secondary_email = !empty(trim($data[76])) ? trim($data[76]) : null;
                    $request_model['email'] = $primary_email != null ? $primary_email : $secondary_email;
                    $primary_tel = !empty(trim($data[30])) ? trim($data[30]) : null;
                    $secondary_tel = !empty(trim($data[39])) ? trim($data[39]) : null;
                    $request_model['tel'] = $primary_tel != null ? $primary_tel : $secondary_tel;

                    // kesken
                    //$customer_id = Customer::where('name', $data[4])->first()->id;
                }else {
                    return redirect()
                        ->back();
                }
                if (empty($request_model)){
                    $error = "Väärä vaihtoehto valittu!";
                    return redirect()
                        ->back()
                        ->with('info_msg', $error);
                }
                $request_extras = Array();
                if (!empty($fk_request_model)){
                    $fk_validator = $this->validate_device_foreign_keys($fk_request_model);
                    if ($fk_validator->fails()) {
                        $row_contents = Array();
                        foreach ($request_model as $val){
                            array_push($row_contents,$val);
                        }
                        $row_info = [$row_headers, $row_contents];
                        $error = $rivinro;
                        return redirect()
                            ->back()
                            ->withErrors($fk_validator)
                            ->with('info_msg', $error)
                            ->with('row_info', $row_info);
                    }
                    }
                    else{
                        foreach ($fk_request_model as $key=>$val){
                            $model_key = $foreign_keys[$key][0];
                            $table = $foreign_keys[$key][1];
                            $tbl_key = $foreign_keys[$key][2];
                            $tbl_val = $val;
                            $request_extras[$model_key] = DB::table($table)->where($tbl_key, $tbl_val)->first()->id();
                        }
                        return dd($request_model);
                    }

                }
                $validator= $this->validate_rows($request_model, $db);
                if ($validator->fails()) {
                    $row_contents = Array();
                    foreach ($request_model as $val){
                        array_push($row_contents,$val);
                    }
                    $row_info = [$row_headers, $row_contents];
                    $error = $rivinro;
                    return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->with('info_msg', $error)
                        ->with('row_info', $row_info);
                }
                if (!empty($request_extras)){
                    array_push($db_model, $request_extras);
                $new_entry = $db_model::create($request_model);
                if (function_exists('$new_entry->getMessage')){
                    $row_contents = Array();
                    foreach ($request_model as $val){
                        array_push($row_contents,$val);
                    }
                    $row_info = [$row_headers, $row_contents];
                    $error = $rivinro;
                    return redirect()
                        ->back()
                        ->withErrors($new_entry)
                        ->with('info_msg', $error)
                        ->with('row_info', $row_info);
                }
                $success_counter++;
            }
            fclose($file);
            $success_msg = "Tiedoston kaikki rivi lisätty onnistuneesti!";
            return redirect()
                ->back()
                ->withSuccess($success_msg)
                ->with('row_info', $success_counter);

    }
    private function validate_rows($request, $database) {
        switch ($database) {
            case 'devices':
                $request_model = new CsvDeviceStoreRequest();
                break;
            case 'users':
                $request_model = new CsvUserStoreRequest();
                break;
            case 'customers':
                $request_model = new CsvCustomerStoreRequest();
                break;
        }
        return Validator::make($request, $request_model->rules(), $request_model->messages());

    }

    private function validate_device_foreign_keys($request) {

        $csv_device_store_request = new CsvDeviceStoreRequest();
        return Validator::make($request, $csv_device_store_request->foreign_key_rules(), $csv_device_store_request->messages());

    }
} // CsvController-luokka päättyy tähän
