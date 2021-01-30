<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
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

class CsvController extends Controller
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
    public function importHandler(Request $request) {

        $option = $request->csvfilemeaning;

        //device
        if ($option == 0){
            // Aiemmista yrityksistä sessiomuuttujiin kertyneet virheet nollataan
            if ($request->session()->has('virheet')) {
                $request->session()->forget('virheet');
                $request->session()->forget('rivi');
                $request->session()->forget('rivinro');
            }

            // Jos lomakkeelta tuli CSV-tiedosto, tallennetaan se aikaleiman
            // mukaiselle nimelle esim.
            // storage/app/harkat-2018-10-21--06-59-48.csv
            
            if($request->hasFile('csvfile')){
                $filenameWithExt = $request->file('csvfile')->getClientOriginalName();
                //get filename, esim. harkat
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                //get ext esim. csv
                $extension = $request->file('csvfile')->getClientOriginalExtension();
                //Filename to storage
                $fileNameToStore = $filename.'-account-'.date("Y-m-d--H-i-s").'.'.$extension;
                //Upload file
                $path = $request->file('csvfile')->storeAs('public', $fileNameToStore);
            }
            
            $filename = base_path("/storage/app/$path");
            $file = fopen($filename, "r");

            $jokuvirhe = FALSE;
            $rivinro = 0;
            $rivi = '';

            // Luetaan CSV-tiedostoa riveittäin
            // Tässä ei virheenkäsittelyä: oletetaan riviltä löytyvän 4 kenttää
            while ( ($data = fgetcsv($file, 2000, ";")) !==FALSE) {
                
                $rivinro++;

                $device_token               = $data[0];
                $customer_id                = $data[1];
                $adminAndPassword           = explode("/", str_replace(' ', '', $data[2]));
                $admin                      = $adminAndPassword[0];
                $admin_password             = $adminAndPassword[1];
                $user_id                    = $data[3];
                $operating_system_id        = $data[4];
                $device_model               = $data[5];
                $device_type_id             = $data[6];
                $device_name                = $data[7];

                $addon_device_description   = $data[8];     //ei lisätä devices tauluun vaan addon devices tauluun
                
                $notes                      = $data[9];
                $license_id                 = $data[10];
                $created_at_date            = date_create($data[11],timezone_open("Europe/Oslo"));
                $created_at                 = date_format($created_at_date,"Y-m-d H:i:s");
                $software_id                = $data[12];
                $buy_date_at                = date_create($data[13],timezone_open("Europe/Oslo"));
                $buy_date                   = date_format($buy_date_at,"Y-m-d");
                $serial_no                  = $data[14];
                $warranty_id                = $data[15];
                $warranty_valid_until_at    = date_create($data[16],timezone_open("Europe/Oslo"));
                $warranty_valid_until       = date_format($warranty_valid_until_at,"Y-m-d");
                $teamviewer_id              = $data[17];
                $security_software_id       = $data[18];
                $active                     = $data[19];
                $order_no                   = $data[20];
                $vendor_id                  = $data[21];
                $product_no                 = $data[22];
                $manufacturer_id            = $data[23];
                $backup_solution_id         = $data[24];
                $lease_contract_no          = $data[25];
                //updated_at

                // Tarkistetaan kenttien muoto semipätevästi ja otetaan
                // virheet talteen sessiomuuttujaan 'virheet'
                if (!preg_match('/^V[T|E][\d]{1,10}$/i', $device_token)){
                    Session::push('virheet', "device_token:<b>" . $device_token . "</b> (Sallitaan: VT ja VE alkuiset + 1-10kpl numeroa)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{1,255}$/i', $customer_id)){
                    Session::push('virheet', "customer_id:<b>" . $customer_id . "</b> (Sallitaan: 1-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{1,255}$/i', $admin)){
                    Session::push('virheet', "admin:<b>" . $admin . "</b> (Sallitaan: 1-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{1,255}$/i', $admin_password)){
                    Session::push('virheet', "admin_password:<b>" . $admin_password . "</b> (Sallitaan: 1-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{1,255}$/i', $user_id)){
                    Session::push('virheet', "user_id:<b>" . $user_id . "</b> (Sallitaan: 1-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{1,255}$/i', $operating_system_id)){
                    Session::push('virheet', "operating_system_id:<b>" . $operating_system_id . "</b> (Sallitaan: 1-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{1,255}$/i', $device_model)){
                    Session::push('virheet', "device_model:<b>" . $device_model . "</b> (Sallitaan: 1-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{1,255}$/i', $device_type_id)){
                    Session::push('virheet', "device_type_id:<b>" . $device_type_id . "</b> (Sallitaan: 1-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{1,255}$/i', $device_name)){
                    Session::push('virheet', "device_name:<b>" . $device_name . "</b> (Sallitaan: 1-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }           
                if (!preg_match('/^.{0,1000}$/i', $notes)){
                    Session::push('virheet', "notes:<b>" . $notes . "</b> (Sallitaan: 0-1000kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/.{0,255}$/i', $license_id)){
                    Session::push('virheet', "license_id:<b>" . $license_id . "</b> (Sallitaan: 0-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29)) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/i', $created_at)){
                    Session::push('virheet', "created_at:<b>" . $created_at . "</b> (Sallitaan: YYYY-MM-DD HH:MM:SS muodossa päivämäärä klo)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{0,255}$/i', $software_id)){
                    Session::push('virheet', "software_id:<b>" . $software_id . "</b> (Sallitaan: 0-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/i', $buy_date)){
                    Session::push('virheet', "buy_date:<b>" . $buy_date . "</b> (Sallitaan: YYYY-MM-DD muodossa päivämäärä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{1,255}$/i', $serial_no)){
                    Session::push('virheet', "serial_no:<b>" . $serial_no . "</b> (Sallitaan: 1-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{0,255}$/i', $warranty_id)){
                    Session::push('virheet', "warranty_id:<b>" . $warranty_id . "</b> (Sallitaan: 0-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/i', $warranty_valid_until)){
                    Session::push('virheet', "warranty_valid_until:<b>" . $warranty_valid_until . "</b> (Sallitaan: YYYY-MM-DD muodossa päivämäärä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^\d{1,255}$/i', $teamviewer_id)){
                    Session::push('virheet', "teamviewer_id:<b>" . $teamviewer_id . "</b> (Sallitaan: 1-255kpl numeroita)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{0,255}$/i', $security_software_id)){
                    Session::push('virheet', "security_software_id:<b>" . $security_software_id . "</b> (Sallitaan: 0-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{1,255}$/i', $active)){
                    Session::push('virheet', "active:<b>" . $active . "</b> (Sallitaan: 1-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^\d{1,255}$/i', $order_no)){
                    Session::push('virheet', "order_no:<b>" . $order_no . "</b> (Sallitaan: 1-255kpl numeroita)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{1,255}$/i', $vendor_id)){
                    Session::push('virheet', "vendor_id:<b>" . $vendor_id . "</b> (Sallitaan: 1-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{1,255}$/i', $product_no)){
                    Session::push('virheet', "product_no:<b>" . $product_no . "</b> (Sallitaan: 0-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{1,255}$/i', $manufacturer_id)){
                    Session::push('virheet', "manufacturer_id:<b>" . $manufacturer_id . "</b> (Sallitaan: 1-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{1,255}$/i', $backup_solution_id)){
                    Session::push('virheet', "backup_solution_id:<b>" . $backup_solution_id . "</b> (Sallitaan: 1-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{0,255}$/i', $lease_contract_no)){
                    Session::push('virheet', "lease_contract_no:<b>" . $lease_contract_no . "</b> (Sallitaan: 0-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                
                // Jos rivillä oli joku virhe, otetaan rivin tiedot talteen sessiomuuttujaan ja
                // palataan tallennuslomakkeeseen näyttäen ensimmäisen virheellisen rivin
                // tiedot. Näin ollen tietoja ei edes yritetä tallentaa tietokantaan ennen kuin
                // muototarkistus on mennyt läpi
                if($jokuvirhe) {
                    foreach($data as $f) {
                        $rivi .= $f . ";";
                    }
                    $rivi = rtrim($rivi,";");
                    fclose($file);
                    Session::put('rivinro', $rivinro);
                    Session::put('rivi', $rivi);
                    return Redirect::to('/import');
                }
            } // while

            fclose($file);

            // CSV-tiedoston muototarkistus on mennyt läpi, yritetään tallentaa tietokantaan
            $PDO = DB::connection('mysql')->getPdo();

            // Jos yhdenkn rivin lisäyksessä tulee ongelmia, voidaan kaikkien rivien lisäykset peruuttaa
            DB::beginTransaction();

            // Luetaan ladattu tiedosto
            $filename = base_path("/storage/app/$path");
            $file = fopen($filename, "r");

            $jokuvirhe = FALSE;
            $rivinro = 0;
            $rivi = '';

            while (($data = fgetcsv($file, 2000, ";")) !==FALSE) {
                
                $rivinro++;

                $device_token               = $data[0];
                $customer_id                = Customer::where('name', $data[1])->first()->id;
                $adminAndPassword           = explode("/", str_replace(' ', '', $data[2]));
                $admin                      = $adminAndPassword[0];
                $admin_password             = $adminAndPassword[1];
                $user_id                    = User::where('name', $data[3])->first()->id;
                $operating_system_id        = OperatingSystem::where('name', $data[4])->first()->id;
                $device_model               = $data[5];
                $device_type_id             = DeviceType::where('type', $data[6])->first()->id;
                $device_name                = $data[7];
                $notes                      = $data[9];
                $license_id                 = $data[10];
                if($license_id != ""){
                    $license_id                 = License::where('name', $data[10])->first()->id;
                }
                $created_at_date            = date_create($data[11],timezone_open("Europe/Oslo"));
                $created_at                 = date_format($created_at_date,"Y-m-d H:i:s");
                $software_id                = $data[12];
                if($software_id != ""){
                    $software_id                = Software::where('items', $software_id)->first()->id;
                }
                $buy_date_at                = date_create($data[13],timezone_open("Europe/Oslo"));
                $buy_date                   = date_format($buy_date_at,"Y-m-d");
                $serial_no                  = $data[14];
                $warranty_id                = $data[15];
                if($warranty_id != ""){
                    $warranty_id                = Warrantie::where('description', $data[15])->first()->id;
                }
                $warranty_valid_until_at    = date_create($data[16],timezone_open("Europe/Oslo"));
                $warranty_valid_until       = date_format($warranty_valid_until_at,"Y-m-d");
                $teamviewer_id              = $data[17];
                $security_software_id       = $data[18];
                if($security_software_id != ""){
                    $security_software_id       = SecuritySoftware::where('description', $data[18])->first()->id;
                }
                $active                     = $data[19];
                $order_no                   = $data[20];
                $vendor_id                  = Vendor::where('name', $data[21])->first()->id;
                $product_no                 = $data[22];
                $manufacturer_id            = Manufacturer::where('name', $data[23])->first()->id;
                $backup_solution_id         = BackupSolution::where('description', $data[24])->first()->id;
                $backup_solution_id         = $data[24];
                if($backup_solution_id != ""){
                    $backup_solution_id         = BackupSolution::where('description', $data[24])->first()->id;
                }
                $lease_contract_no          = $data[25];

                if($active == "Aktiivinen"){
                    $active = 1;
                }
                elseif($active != "Aktiivinen"){
                    $active = 0;
                }

                // Yritetään tallentaa rivin tietoja
                try
                {
                    if ($license_id == "") {
                        $sql = "INSERT INTO devices (device_token, customer_id, admin, admin_password, user_id, operating_system_id, device_model, device_type_id, device_name, notes, created_at, software_id, buy_date, serial_no, warranty_id, warranty_valid_until, teamviewer_id, security_software_id, active, order_no, vendor_id, product_no, manufacturer_id, backup_solution_id, lease_contract_no, updated_at) VALUES(:f1,:f2,:f3,:f4,:f5,:f6,:f7,:f9,:f10,:f11,:f13,:f14,:f15,:f16,:f17,:f18,:f19,:f20,:f21,:f22,:f23,:f24,:f25,:f26,:f27,(select now()))";
                        $insertsql = $PDO->prepare($sql);
                        $insertsql->execute(array(':f1' => $device_token, ':f2' => $customer_id, ':f3' => $admin, ':f4' => $admin_password, ':f5' => $user_id, ':f6' => $operating_system_id, ':f7' => $device_model, ':f9' => $device_type_id, ':f10' => $device_name, ':f11' => $notes, ':f13' => $created_at, ':f14' => $software_id, ':f15' => $buy_date, ':f16' => $serial_no, ':f17' => $warranty_id, ':f18' => $warranty_valid_until, ':f19' => $teamviewer_id, ':f20' => $security_software_id, ':f21' => $active, ':f22' => $order_no, ':f23' => $vendor_id, ':f24' => $product_no, ':f25' => $manufacturer_id, ':f26' => $backup_solution_id, ':f27' => $lease_contract_no));
                    }
                    else {
                        $sql = "INSERT INTO devices (device_token, customer_id, admin, admin_password, user_id, operating_system_id, device_model, device_type_id, device_name, notes, license_id, created_at, software_id, buy_date, serial_no, warranty_id, warranty_valid_until, teamviewer_id, security_software_id, active, order_no, vendor_id, product_no, manufacturer_id, backup_solution_id, lease_contract_no, updated_at) VALUES(:f1,:f2,:f3,:f4,:f5,:f6,:f7,:f9,:f10,:f11,:f12,:f13,:f14,:f15,:f16,:f17,:f18,:f19,:f20,:f21,:f22,:f23,:f24,:f25,:f26,:f27,(select now()))";
                        $insertsql = $PDO->prepare($sql);
                        $insertsql->execute(array(':f1' => $device_token, ':f2' => $customer_id, ':f3' => $admin, ':f4' => $admin_password, ':f5' => $user_id, ':f6' => $operating_system_id, ':f7' => $device_model, ':f9' => $device_type_id, ':f10' => $device_name, ':f11' => $notes, ':f12' => $license_id, ':f13' => $created_at, ':f14' => $software_id, ':f15' => $buy_date, ':f16' => $serial_no, ':f17' => $warranty_id, ':f18' => $warranty_valid_until, ':f19' => $teamviewer_id, ':f20' => $security_software_id, ':f21' => $active, ':f22' => $order_no, ':f23' => $vendor_id, ':f24' => $product_no, ':f25' => $manufacturer_id, ':f26' => $backup_solution_id, ':f27' => $lease_contract_no));
                    }
                }

                // Jos ongelmia, peruutetaan
                catch(\Exception $e)
                {
                    DB::rollback();

                    if ($e instanceof \PDOException) {

                        $dbCode = trim($e->getCode());
                        //Log::info($e);
                        //Codes specific to mysql errors
                        switch ($dbCode)
                        {
                            // 23000 = Integrity constraint violation eli esim sama PK kuin jo olemassa olevalla tietueella
                            case 23000:
                                $errorMessage = 'Tietokantaongelma: Yritit ehkä tallentaa samoja asioita toiseen kertaan?';
                                break;
                            default:
                                $errorMessage = 'Tietokantaan tallentamisessa ongelmia';
                        }

                        // Ei lisätty yhtään tietuetta (koska rollback), palataan lisäyslomakkeeseen,
                        // jossa näytetään virheviesti
                        return redirect()->back()->with('info_msg',"$errorMessage");
                    }
                }
            }

            // Hyväksytään kaikki rivit tietokantaan, palataan lomakesivulle ja näytetään viesti onnistumisesta
            DB::commit();

            while (($data = fgetcsv($file, 2000, ";")) !==FALSE) {
                
                $rivinro++;

                $main_device_id     = Device::where('device_token', $data[0])->first()->id;
                $description        = $data[8];

                // Yritetään tallentaa rivin tietoja
                try
                {
                    $sql = "INSERT INTO addon_devices (main_device_id, description, created_at, updated_at) VALUES(:f1,:f2,(select now()),(select now()))";
                    $insertsql = $PDO->prepare($sql);
                    $insertsql->execute(array(':f1' => $main_device_id, ':f2' => $description));
                }

                // Jos ongelmia, peruutetaan
                catch(\Exception $e)
                {
                    DB::rollback();

                    if ($e instanceof \PDOException) {

                        $dbCode = trim($e->getCode());

                        //Codes specific to mysql errors
                        switch ($dbCode)
                        {
                            // 23000 = Integrity constraint violation eli esim sama PK kuin jo olemassa olevalla tietueella
                            case 23000:
                                $errorMessage = 'Tietokantaongelma: Yritit ehkä tallentaa samoja asioita toiseen kertaan?';
                                break;
                            default:
                                $errorMessage = 'Tietokantaan tallentamisessa ongelmia';
                        }

                        // Ei lisätty yhtään tietuetta (koska rollback), palataan lisäyslomakkeeseen,
                        // jossa näytetään virheviesti
                        return redirect()->back()->with('info_msg',"$errorMessage");
                    }
                }
            }
            // Hyväksytään kaikki rivit tietokantaan, palataan lomakesivulle ja näytetään viesti onnistumisesta
            DB::commit();
            fclose($file);
            return redirect('/import')->with('info_msg', 'CSV-tiedoston KAIKKI rivit lisätty onnistuneesti');
        }

        //customer
        if ($option == 1){
            // Aiemmista yrityksistä sessiomuuttujiin kertyneet virheet nollataan
            if ($request->session()->has('virheet')) {
                $request->session()->forget('virheet');
                $request->session()->forget('rivi');
                $request->session()->forget('rivinro');
            }

            // Jos lomakkeelta tuli CSV-tiedosto, tallennetaan se aikaleiman
            // mukaiselle nimelle esim.
            // storage/app/harkat-2018-10-21--06-59-48.csv
            
            if($request->hasFile('csvfile')){
                $filenameWithExt = $request->file('csvfile')->getClientOriginalName();
                //get filename, esim. harkat
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                //get ext esim. csv
                $extension = $request->file('csvfile')->getClientOriginalExtension();
                //Filename to storage
                $fileNameToStore = $filename.'-account-'.date("Y-m-d--H-i-s").'.'.$extension;
                //Upload file
                $path = $request->file('csvfile')->storeAs('public', $fileNameToStore);
            }
            
            $filename = base_path("/storage/app/$path");
            $file = fopen($filename, "r");

            $jokuvirhe = FALSE;
            $rivinro = 0;
            $rivi = '';

            // Luetaan CSV-tiedostoa riveittäin
            // Tässä ei virheenkäsittelyä: oletetaan riviltä löytyvän 4 kenttää
            while ( ($data = fgetcsv($file, 2000, ";")) !==FALSE) {
                
                $rivinro++;

                $customer_token         = $data[0];
                $name                   = $data[1];
                $address                = $data[2];
                $contact_person_name    = $data[3];
                $active                 = $data[4];
                $notes                  = $data[5];

                // Tarkistetaan kenttien muoto semipätevästi ja otetaan
                // virheet talteen sessiomuuttujaan 'virheet'
                if (!preg_match('/^\d{1,255}$/i', $customer_token)){
                    Session::push('virheet', "customer_token:<b>" . $customer_token . "</b> (Sallitaan: 1-255kpl numeroa)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{1,255}$/i', $name)){
                    Session::push('virheet', "name:<b>" . $name . "</b> (Sallitaan: 1-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{1,255}$/i', $address)){
                    Session::push('virheet', "address:<b>" . $address . "</b> (Sallitaan: 1-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{0,255}$/i', $contact_person_name)){
                    Session::push('virheet', "contact_person_name:<b>" . $contact_person_name . "</b> (Sallitaan: 0-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^[0|1]$/i', $active)){
                    Session::push('virheet', "active:<b>" . $active . "</b> (Sallitaan: Vain 0 tai 1)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{0,1000}$/i', $notes)){
                    Session::push('virheet', "notes:<b>" . $notes . "</b> (Sallitaan: 0-1000kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }

                // Jos rivillä oli joku virhe, otetaan rivin tiedot talteen sessiomuuttujaan ja
                // palataan tallennuslomakkeeseen näyttäen ensimmäisen virheellisen rivin
                // tiedot. Näin ollen tietoja ei edes yritetä tallentaa tietokantaan ennen kuin
                // muototarkistus on mennyt läpi
                if($jokuvirhe) {
                    foreach($data as $f) {
                        $rivi .= $f . ";";
                    }
                    $rivi = rtrim($rivi,";");
                    fclose($file);
                    Session::put('rivinro', $rivinro);
                    Session::put('rivi', $rivi);
                    return Redirect::to('/import');
                }
            } // while

            fclose($file);

            // CSV-tiedoston muototarkistus on mennyt läpi, yritetään tallentaa tietokantaan
            $PDO = DB::connection('mysql')->getPdo();

            // Jos yhdenkn rivin lisäyksessä tulee ongelmia, voidaan kaikkien rivien lisäykset peruuttaa
            DB::beginTransaction();

            // Luetaan ladattu tiedosto
            $filename = base_path("/storage/app/$path");
            $file = fopen($filename, "r");

            $jokuvirhe = FALSE;
            $rivinro = 0;
            $rivi = '';

            while (($data = fgetcsv($file, 2000, ";")) !==FALSE) {
                
                $rivinro++;

                $customer_token         = $data[0];
                $name                   = $data[1];
                $address                = $data[2];
                $contact_person_name    = $data[3];
                $active                 = $data[4];
                $notes                  = $data[5];

                // Yritetään tallentaa rivin tietoja
                try
                {
                    $sql = "INSERT INTO customers (customer_token, name, address, contact_person_name, active, notes, created_at, updated_at) VALUES(:f1,:f2,:f3,:f4,:f5,:f6,(select now()),(select now()))";
                    $insertsql = $PDO->prepare($sql);
                    $insertsql->execute(array(':f1' => $customer_token, ':f2' => $name, ':f3' => $address, ':f4' => $contact_person_name, ':f5' => $active, ':f6' => $notes));
                }

                // Jos ongelmia, peruutetaan
                catch(\Exception $e)
                {
                    DB::rollback();

                    if ($e instanceof \PDOException) {

                        $dbCode = trim($e->getCode());

                        //Codes specific to mysql errors
                        switch ($dbCode)
                        {
                            // 23000 = Integrity constraint violation eli esim sama PK kuin jo olemassa olevalla tietueella
                            case 23000:
                                $errorMessage = 'Tietokantaongelma: Yritit ehkä tallentaa samoja asioita toiseen kertaan?';
                                break;
                            default:
                                $errorMessage = 'Tietokantaan tallentamisessa ongelmia';
                        }

                        // Ei lisätty yhtään tietuetta (koska rollback), palataan lisäyslomakkeeseen,
                        // jossa näytetään virheviesti
                        return redirect()->back()->with('info_msg',"$errorMessage");
                    }
                }
            }

            // Hyväksytään kaikki rivit tietokantaan, palataan lomakesivulle ja näytetään viesti onnistumisesta
            DB::commit();
            fclose($file);
            return redirect('/import')->with('info_msg', 'CSV-tiedoston KAIKKI rivit lisätty onnistuneesti');
        }

        //account
        if ($option == 2) {
            // Aiemmista yrityksistä sessiomuuttujiin kertyneet virheet nollataan
            if ($request->session()->has('virheet')) {
                $request->session()->forget('virheet');
                $request->session()->forget('rivi');
                $request->session()->forget('rivinro');
            }

            // Jos lomakkeelta tuli CSV-tiedosto, tallennetaan se aikaleiman
            // mukaiselle nimelle esim.
            // storage/app/harkat-2018-10-21--06-59-48.csv
            
            if($request->hasFile('csvfile')){
                $filenameWithExt = $request->file('csvfile')->getClientOriginalName();
                //get filename, esim. harkat
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                //get ext esim. csv
                $extension = $request->file('csvfile')->getClientOriginalExtension();
                //Filename to storage
                $fileNameToStore = $filename.'-account-'.date("Y-m-d--H-i-s").'.'.$extension;
                //Upload file
                $path = $request->file('csvfile')->storeAs('public', $fileNameToStore);
            }
            
            $filename = base_path("/storage/app/$path");
            $file = fopen($filename, "r");

            $jokuvirhe = FALSE;
            $rivinro = 0;
            $rivi = '';

            // Luetaan CSV-tiedostoa riveittäin
            // Tässä ei virheenkäsittelyä: oletetaan riviltä löytyvän 4 kenttää
            while ( ($data = fgetcsv($file, 2000, ",")) !==FALSE) {

                $rivinro++;
                //Log::info($data[2] . ", " . $data[0]);
                $name           = $data[2] . ", " . $data[0]; //sukunimi, etunimi
                $email          = $data[8];
                $tel            = $data[13];
                $customer_id    = $data[31];
                $notes          = $data[60];
                
                //puhelinnumero
                if ($tel=="") {
                    $tel = $data[15];
                }
                
                // Tarkistetaan kenttien muoto semipätevästi ja otetaan
                // virheet talteen sessiomuuttujaan 'virheet'
                if (!preg_match('/^.{1,255}$/i', $name)){
                    Session::push('virheet', "name:<b>" . $name . "</b> (Sallitaan: 1-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^([\w.@]{0,255})$/i', $email)){
                    Session::push('virheet', "email:<b>" . $email . "</b> (Sallitaan: [a-zA-Z0-9_.@] 0-255kpl)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^[+?]{0,1}[\d ]{0,20}$/i', $tel)){
                    Session::push('virheet', "tel:<b>" . $tel . "</b> (Sallitaan: muodossa +358123456789 tai 0404141414141, 0-20 kpl numeroita)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{0,255}$/i', $customer_id)){
                    Session::push('virheet', "customer_id:<b>" . $customer_id . "</b> (Sallitaan: 0-255kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }
                if (!preg_match('/^.{0,1000}$/i', $notes)){
                    Session::push('virheet', "notes:<b>" . $notes . "</b> (Sallitaan: 0-1000kpl mitä tahansa merkkiä)");
                            $jokuvirhe = TRUE;
                }

                // Jos rivillä oli joku virhe, otetaan rivin tiedot talteen sessiomuuttujaan ja
                // palataan tallennuslomakkeeseen näyttäen ensimmäisen virheellisen rivin
                // tiedot. Näin ollen tietoja ei edes yritetä tallentaa tietokantaan ennen kuin
                // muototarkistus on mennyt läpi
                if($jokuvirhe) {
                    foreach($data as $f) {
                    $rivi .= $f . ";";
                    }
                    $rivi = rtrim($rivi,";");
                    fclose($file);
                    Session::put('rivinro', $rivinro);
                    Session::put('rivi', $rivi);
                    return Redirect::to('/import');
                }
            } // while

            fclose($file);

            // CSV-tiedoston muototarkistus on mennyt läpi, yritetään tallentaa tietokantaan
            $PDO = DB::connection('mysql')->getPdo();

            // Jos yhdenkn rivin lisäyksessä tulee ongelmia, voidaan kaikkien rivien lisäykset peruuttaa
            DB::beginTransaction();

            // Luetaan ladattu tiedosto
            $filename = base_path("/storage/app/$path");
            $file = fopen($filename, "r");

            $jokuvirhe = FALSE;
            $rivinro = 0;
            $rivi = '';

            while (($data = fgetcsv($file, 2000, ",")) !==FALSE) {
                $rivinro++;

                $user_role_id   = 1;
                $password       = Hash::make($data[0].$data[2]);
                $active         = 1;

                $name           = $data[2] . ", " . $data[0]; //sukunimi, etunimi
                $email          = $data[8];
                $tel            = $data[13];
                $customer_id    = Customer::where('name', $data[31])->first()->id;
                $notes          = $data[60];
                
                //puhelinnumero
                if ($tel=="") {
                    $tel = $data[15];
                }

                //email
                if ($email=="") {
                    $email = $data[0] . "@" . $data[2]; //etunimi@sukunimi;
                }

                // Yritetään tallentaa rivin tietoja
                try
                {
                    $sql = "INSERT INTO users (user_role_id, customer_id, name, email, password, notes, tel, active, created_at, updated_at) VALUES(:f1,:f2,:f3,:f4,:f5,:f6,:f7,:f8,(select now()),(select now()))";
                    $insertsql = $PDO->prepare($sql);
                    $insertsql->execute(array(':f1' => $user_role_id, ':f2' => $customer_id, ':f3' => $name, ':f4' => $email, ':f5' => $password, ':f6' => $notes, ':f7' => $tel, ':f8' => $active));
                }

                // Jos ongelmia, peruutetaan
                catch(\Exception $e)
                {
                    DB::rollback();

                    if ($e instanceof \PDOException) {

                        $dbCode = trim($e->getCode());

                        //Codes specific to mysql errors
                        switch ($dbCode)
                        {
                            // 23000 = Integrity constraint violation eli esim sama PK kuin jo olemassa olevalla tietueella
                            case 23000:
                                $errorMessage = 'Tietokantaongelma: Yritit ehkä tallentaa samoja asioita toiseen kertaan?';
                                break;
                            default:
                                $errorMessage = 'Tietokantaan tallentamisessa ongelmia';
                        }

                        // Ei lisätty yhtään tietuetta (koska rollback), palataan lisäyslomakkeeseen,
                        // jossa näytetään virheviesti
                        return redirect()->back()->with('info_msg',"$errorMessage");
                    }
                }
            }

            // Hyväksytään kaikki rivit tietokantaan, palataan lomakesivulle ja näytetään viesti onnistumisesta
            DB::commit();
            fclose($file);
            return redirect('/import')->with('info_msg', 'CSV-tiedoston KAIKKI rivit lisätty onnistuneesti');
        }
    }    
} // CsvController-luokka päättyy tähän