<?php
namespace App\Http\Controllers;
//namespace App\Http\Request;
//use App\Http\Controllers\DeviceStoreRequest;
use App\Http\Requests\DeviceStoreRequest;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\AddonDevice;
use App\BackupSolution;
use App\Customer;
use App\DeviceType;
use App\Device;
use App\License;
use App\Manufacturer;
use App\OperatingSystem;
use App\SecuritySoftware;
use App\Software;
use App\UserRole;
use App\User;
use App\Vendor;
use App\Warrantie;
use DateTime;
use date;
use DB;


class DatabaseController extends Controller
{

    public function create(Request $request) {
        $user = Auth::user();
	$logged_user = $request->user();
	if ($logged_user !== $user || ($user = null || $logged_user == null)){
	    return route('login');
	}
	$db_name = $request->input('db');
   	$db_model = null;
	$db_fields = [];
	switch ($db_name) {
    	case 'addon_devices':
            $db_model = new AddonDevice;
	    break;
    	case 'backup_solutions':
            $db_model = new BackupSolution;
	    break;
        case 'customers':
            $db_model = new Customer;
	    break;
	case 'device_types':
            $db_model = new DeviceType;
	    break;
	case 'devices':
            $db_model = new Device;
	    break;
	case 'licenses':
            $db_model = new License;
	    break;
	case 'manufacturers':
            $db_model = new Manufacturer;
	    break;
	case 'operating_systems':
            $db_model = new OperatingSystem;
	    break;
	case 'security_softwares':
            $db_model = new SecuritySoftware;
	    break;
	case 'softwares':
            $db_model = new Software;
	    break;
	case 'user_roles':
            $db_model = new UserRole;
	    break;
	case 'users':
            $db_model = new User;
	    break;
	case 'vendors':
            $db_model = new Vendor;
	    break;
	case 'warranties':
            $db_model = new Warrantie;
	    break;
	default: Log::info('incorrect db');return ['error'=>"incorrect database!"];
	}
	$validated = null;
	/*
	if ($db_name === 'devices'){
		$validated = $this->validateNewDevice($request);
	}
	*/
	//return $validated;
	$db_fields = $db_model->getTableColumns();
	$unset_fields = ['id', 'created_at', 'updated_at'];
	foreach ($db_fields as $key=>$var){
    	    if (in_array($var, $unset_fields) && null !==$request->input($var)){
        	unset($db_fields[$key]);
    	    }
	}
	$vals = [];
	foreach ($db_fields as $field){
	    try {
	    	$vals[$field] = $request->input($field);
	    } catch(Exception $e) {
	    	continue;
	    }
	}
   	$new_entry = $db_model::create($vals);
	if (function_exists('$new_entry->getMessage')){
                $error = $new_entry->getMessage();
                return $error;
        }
	return $new_entry;
    }

    public function  remove(Request $request) {
	$user = Auth::user();
	$logged_user = $request->user();
	if ($logged_user !== $user || ($user = null || $logged_user == null)){
	    return route('login');
	}
	$db_name = $request->input('db');
	$db_id = $request->input('id');
   	$db_model = null;
	switch ($db_name) {
    	case 'addon_devices':
            $db_model = new AddonDevice;
	    break;
    	case 'backup_solutions':
            $db_model = new BackupSolution;
	    break;
        case 'customers':
            $db_model = new Customer;
	    break;
	case 'device_types':
            $db_model = new DeviceType;
	    break;
	case 'devices':
            $db_model = new Device;
	    break;
	case 'licenses':
            $db_model = new License;
	    break;
	case 'manufacturers':
            $db_model = new Manufacturer;
	    break;
	case 'operating_systems':
            $db_model = new OperatingSystem;
	    break;
	case 'security_softwares':
            $db_model = new SecuritySoftware;
	    break;
	case 'softwares':
            $db_model = new Software;
	    break;
	case 'user_roles':
            $db_model = new UserRole;
	    break;
	case 'users':
            $db_model = new User;
	    break;
	case 'vendors':
            $db_model = new Vendor;
	    break;
	case 'warranties':
            $db_model = new Warrantie;
	    break;

	}
	//$entry = $db_model::find($db_id);
	$status = $db_model::destroy($db_id);
	if (function_exists('$status->getMessage')){
                $error = $status->getMessage();
                return $error;
        }
        return $status;
    }
    public function update(Request $request) {
        $user = Auth::user();
		$logged_user = $request->user();
		//return dd($request->user());
		if ($logged_user !== $user || ($user = null || $logged_user == null)){
			return route('login');
		}
		$db_name = $request->input('db');
	   	$db_model = null;
		$db_fields = [];
		switch ($db_name) {
		case 'addon_devices':
	        $db_model = new AddonDevice;
		break;
		case 'backup_solutions':
	        $db_model = new BackupSolution;
		break;
	    case 'customers':
	        $db_model = new Customer;
		break;
		case 'device_types':
		        $db_model = new DeviceType;
			break;
		case 'devices':
		        $db_model = new Device;
			break;
		case 'licenses':
		        $db_model = new License;
			break;
		case 'manufacturers':
		        $db_model = new Manufacturer;
			break;
		case 'operating_systems':
		        $db_model = new OperatingSystem;
			break;
		case 'security_softwares':
		        $db_model = new SecuritySoftware;
			break;
		case 'softwares':
		        $db_model = new Software;
			break;
		case 'user_roles':
		        $db_model = new UserRole;
			break;
		case 'users':
		        $db_model = new User;
			break;
		case 'vendors':
		        $db_model = new Vendor;
			break;
		case 'warranties':
		        $db_model = new Warrantie;
			break;

		}
		$db_id = $request->input('id');
		$entry = $db_model::find($db_id);
		$db_fields = $db_model->getTableColumns();
		$unset_fields = ['id', 'created_at', 'updated_at'];
		foreach ($db_fields as $key=>$var){
			    if (in_array($var, $unset_fields) && null !== $request->input($var)){
		    	unset($db_fields[$key]);
			    }
		}
		foreach ($db_fields as $field){
			try {
				$entry->$field = $request->input($field);

			} catch(Exception $e) {
				return $e;
			}
		    }
		$status = $entry->save();
		if (function_exists('$status->getMessage')){
			$error = $status->getMessage();
			return $error;
	  	}
		return $status;
		}
        public function fetch(Request $request) {
        $user = Auth::user();
	$logged_user = $request->user();
	//return dd($request->user());
	if ($logged_user !== $user || ($user = null || $logged_user == null)){
	    return route('login');
	}
	$db_name = $request->input('db');
   	$db_model = null;
	switch ($db_name) {
    	case 'addon_devices':
            $db_model = new AddonDevice;
	    break;
    	case 'backup_solutions':
            $db_model = new BackupSolution;
	    break;
        case 'customers':
            $db_model = new Customer;
	    break;
	case 'device_types':
            $db_model = new DeviceType;
	    break;
	case 'devices':
            $db_model = new Device;
	    break;
	case 'licenses':
            $db_model = new License;
	    break;
	case 'manufacturers':
            $db_model = new Manufacturer;
	    break;
	case 'operating_systems':
            $db_model = new OperatingSystem;
	    break;
	case 'security_softwares':
            $db_model = new SecuritySoftware;
	    break;
	case 'softwares':
            $db_model = new Software;
	    break;
	case 'user_roles':
            $db_model = new UserRole;
	    break;
	case 'users':
            $db_model = new User;
	    break;
	case 'vendors':
            $db_model = new Vendor;
	    break;
	case 'warranties':
            $db_model = new Warrantie;
	    break;

	}
    $entries = $db_model::all();
    return $entries;
    }
/*
    public function validateNewDevice(Request $request) {
	$todayDate = date('Y-m-d');
        $todayDatetime = new DateTime('now');
        $todayformatted= $todayDatetime->format('Y-m-d H:i:s');
        $validatedData = $request->validate([
            'device_token' => ['string','required','unique:devices','starts_with:VT,VE,regex:/^V[T|E][\d]{1,253}$/i','max:255'],
            'admin' => ['string,nullable,max:255'],
            'admin_password' => ['min:8,confirmed,nullable,max:255'],
            'device_name' => ['string,required,unique:devices,max:255',
            'device_model' => ['string,required,max:255'],
            'notes' => 'nullable',
            'buy_date' => ['date,before_or_equal:'.$todayDate.',nullable'],
            'serial_no' => ['nullable,max:255'],
            'warranty_valid_until' => ['date,nullable,max:255'],
            'teamviewer_id' => ['integer,required,max:255'],
            'active' => ['boolean,required'],
            'order_no' => 'nullable',
            'product_no' => 'nullable',
            'lease_contract_no' => 'nullable',
            'attachments' => ['nullable,json'],
            'customer_name' => ['required,exists:customers,name'],
            'user_name' => ['required,exists:users,name'],
            'operating_system_name' => ['required,exists:operating_systems,name'],
            'device_type_type' => ['required,exists:device_types,type'],
            'license_name' => ['nullable|exists:licenses,name'],
            'software_items' => ['nullable|exists:softwares,items'],
            'warranty_description' => ['nullable|exists:warranties,description'],
            'manufacturer_name' => ['required,exists:manufacturers,name'],
            'security_software_description' => ['nullable|exists:security_softwares,description'],
            'vendor_name' => '[required,exists:vendors,name'],
            'backup_solution_description' => ['nullable|exists:backup_solutions,description'],
            'created_at' => ['date_format:"Y-m-d H:i:s/",before_or_equal:'.$todayformatted],
	    'updated_at' => ['date_format:"Y-m-d H:i:s/",before_or_equal:'.$todayformatted],

        ]);
        return $validatedData;
    }
*/
	public function fields(Request $request) {
        $user = Auth::user();
		$logged_user = $request->user();
		//return dd($request->user());
		if ($logged_user !== $user || ($user = null || $logged_user == null)){
			return route('login');
		}
		$db_name = $request->input('db');
		$db_fields = DB::select('describe '.$db_name);
		return $db_fields;

    }
    public function test(){
		return view('add_any_item');

    }
	public function create_device(DeviceStoreRequest $request) {

		/* mustiinpano 14.8:
			* tsekkaa tämä:
			* $deviceRequest = new DeviceStoreRequest($request()->all());
			* $deviceRequest->validate();
			* // näin saamme muutettua geneerisen Requestin spesifiksi yhden taulun Requestiksi
		*/

        $user = Auth::user();
		$logged_user = $request->user();
		if ($logged_user !== $user || ($user = null || $logged_user == null)){
			return route('login');
		}
		$db_name = $request->input('db');
	   	$db_model = null;
		$db_fields = [];
		switch ($db_name) {
		case 'devices':
		        $db_model = new Device;
				break;
		default: Log::info('incorrect db');return ['error'=>"incorrect database!"];
		}
		
        $validated = $request->validated();

		$db_fields = $db_model->getTableColumns();
		$unset_fields = ['id', 'created_at', 'updated_at'];
		foreach ($db_fields as $key=>$var){
			    if (in_array($var, $unset_fields) && null !==$request->input($var)){
		    	unset($db_fields[$key]);
			    }
		}
		$vals = [];
		foreach ($db_fields as $field){
			try {
				$vals[$field] = $request->input($field);
			} catch(Exception $e) {
				continue;
			}
		}
	   	$new_entry = $db_model::create($vals);
		if (function_exists('$new_entry->getMessage')){
		            $error = $new_entry->getMessage();
		            return $error;
		    }
		return $new_entry;
    }
        
	public function create_customer(CustomerStoreRequest $request) {
        $user = Auth::user();
		$logged_user = $request->user();
		if ($logged_user !== $user || ($user = null || $logged_user == null)){
			return route('login');
		}
		$db_name = $request->input('db');
	   	$db_model = null;
		$db_fields = [];
		switch ($db_name) {
		case 'customers':
		        $db_model = new Customer;
				break;
		default: Log::info('incorrect db');return ['error'=>"incorrect database!"];
		}
		
        $validated = $request->validated();

		$db_fields = $db_model->getTableColumns();
		$unset_fields = ['id', 'created_at', 'updated_at'];
		foreach ($db_fields as $key=>$var){
			    if (in_array($var, $unset_fields) && null !==$request->input($var)){
		    	unset($db_fields[$key]);
			    }
		}
		$vals = [];
		foreach ($db_fields as $field){
			try {
				$vals[$field] = $request->input($field);
			} catch(Exception $e) {
				continue;
			}
		}
	   	$new_entry = $db_model::create($vals);
		if (function_exists('$new_entry->getMessage')){
		            $error = $new_entry->getMessage();
		            return $error;
		    }
		return $new_entry;
    }

    public function create_user(UserStoreRequest $request) {

        $user = Auth::user();
		$logged_user = $request->user();
		if ($logged_user !== $user || ($user = null || $logged_user == null)){
			return route('login');
		}
		$db_name = $request->input('db');
	   	$db_model = null;
		$db_fields = [];
		switch ($db_name) {
		case 'users':
		        $db_model = new User;
				break;
		default: Log::info('incorrect db');return ['error'=>"incorrect database!"];
		}
		
        $validated = $request->validated();

		$db_fields = $db_model->getTableColumns();
		$unset_fields = ['id', 'created_at', 'updated_at'];
		foreach ($db_fields as $key=>$var){
			    if (in_array($var, $unset_fields) && null !==$request->input($var)){
		    	unset($db_fields[$key]);
			    }
		}
		$vals = [];
		foreach ($db_fields as $field){
			try {
				$vals[$field] = $request->input($field);
			} catch(Exception $e) {
				continue;
			}
		}
	   	$new_entry = $db_model::create($vals);
		if (function_exists('$new_entry->getMessage')){
		            $error = $new_entry->getMessage();
		            return $error;
		    }
		return $new_entry;
	}
}
