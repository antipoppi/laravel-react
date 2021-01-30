<?php

namespace App;
use App\Customer;
use App\User;
use App\OperatingSystem;
use App\DeviceType;
use App\License;
use App\Software;
use App\Warrantie;
use App\Manufacturer;
use App\SecuritySoftware;
use App\Vendor;
use App\BackupSolution;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\QueryException;
//use Illuminate\Support\Facades\Validator;
//use Illuminate\Http\Request;
//use Illuminate\Validation\Rule;

class Device extends Model
{
    //use Notifiable;
    protected $table = 'devices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'device_token', 'admin','admin_password', 'customer_id', 'user_id', 'operating_system_id', 'device_type_id', 'license_bundle_id', 'software_bundle_id', 'warranty_type_id', 'manufacturer_id', 'security_subscriptions_id', 'vendor_id', 'backups_id', 'device_name', 'device_model', 'notes', 'buy_date', 'serial_no', 'warranty_valid_until', 'teamviewer_id', 'active', 'order_no', 'product_no', 'lease_contract_no', 'attachments',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];
    public function backup()
    {
        return $this->belongsTo('App\BackupSolution', 'backup_solution_id');
    }
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
    public function deviceType()
    {
        return $this->belongsTo('App\DeviceType', 'device_type_id');
    }
    public function license()
    {
        return $this->belongsTo('App\License', 'license_id');
    }
    public function manufacturer()
    {
        return $this->belongsTo('App\Manufacturer', 'manufacturer_id');
    }
    public function operatingSystem()
    {
        return $this->belongsTo('App\OperatingSystem', 'operating_system_id');
    }
    public function securitySoftware()
    {
        return $this->belongsTo('App\SecuritySoftware', 'security_software_id');
    }
    public function software()
    {
        return $this->belongsTo('App\Software', 'software_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function vendor()
    {
        return $this->belongsTo('App\Vendor', 'vendor_id');
    }
    public function warranty()
    {
        return $this->belongsTo('App\Warrantie', 'warranty_id');
    }
    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    public function verify(Request $request) {

        $todayDate = date('Y-m-d');
        $todayDatetime = new DateTime('now');
        $todayformatted= $todayDatetime->format('Y-m-d H:i:s');
        $validatedData = $request->validate([
            'device_token' => ['string,required,unique:devices,starts_with:VT,VE,regex:/^V[T|E][\d]{1,253}$/i,max:255'],
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
	    'updated_at' => ['date_format:"Y-m-d H:i:s/",before_or_equal:'.$todayformatted]

        ]);
        return $validatedData;
    }
    public function trimOrCreate($fields) {
        $db_model = null;
        $foreign_keys = ['customer_name', 'user_name', 'operating_system_name','device_type_type', 'license_name', 'software_items', 'warranty_description', 'manufacturer_name', 'security_software_description', 'vendor_name', 'backup_solution_description'];
	$new_fields = [];
	foreach ($fields as $key=>$val){
        if (in_array($key, $foreign_keys)){
		//$new_fields[$key] = $val;
            switch ($key) {
                case 'operating_system_name':
                    $db_model = new OperatingSystem;
                    $vals = ['name'=>$val];
					$entry = null;
				    try {
				    	$entry = $db_model::create($vals);
					} catch(QueryException $e) {
						$error = ['error' => $e->getMessage()];
					return $error;
			   	    }
                    $operating_system_id = $entry->id;
                    //unset($fields->$key);
                    $new_fields['operating_system_id'] = $operating_system_id;
		    		break 1;

                case 'device_type_type':
                    $db_model = new DeviceType;
                    $vals = ['asd'=>$val];
					$entry = null;
					try {
				    	$entry = $db_model::create($vals);
					} catch(QueryException $e) {
						$error = ['error' => $e->getMessage()];
						return $error;
			   	    }
                    $device_type_id = $entry->id;
                    //unset($fields->$key);
                    $new_fields['device_type_id'] = $device_type_id;
		    		break 1;

                case 'license_name':
                    $db_model = new License;
                    $vals = ['name'=>$val];
                    $entry = null;
					try {
				    	$entry = $db_model::create($vals);
					} catch(QueryException $e) {
						$error = ['error' => $e->getMessage()];
					return $error;
			   	    }
                    $license_id = $entry->id;
                    //unset($fields->$key);
                    $new_fields['license_id'] = $license_id;
		    		break 1;

                case 'software_items':
                    $db_model = new Software;
                    $vals = ['items'=>$val];
                    $entry = null;
					try {
				    	$entry = $db_model::create($vals);
					} catch(QueryException $e) {
						$error = ['error' => $e->getMessage()];
						return $error;
			   	    }
                    $software_id = $entry->id;
                    //unset($fields->$key);
                    $new_fields['software_id'] = $software_id;
		    		break 1;

                case 'warranty_description':
                    $db_model = new Warrantie;
                    $vals = ['description'=>$val];
                    $entry = null;
					try {
				    	$entry = $db_model::create($vals);
					}catch(QueryException $e) {
						$error = ['error' => $e->getMessage()];
						return $error;
			   	    }
                    $warranty_id = $entry->id;
                    //unset($fields->$key);
                    $new_fields['warranty_id'] = $warranty_id;
		    		break 1;

                case 'manufacturer_name':
                    $db_model = new Manufacturer;
                    $vals = ['name'=>$val];
                    $entry = null;
					try {
				    	$entry = $db_model::create($vals);
					} catch(QueryException $e) {
						$error = ['error' => $e->getMessage()];
						return $error;
			   	    }
                    $manufacturer_id = $entry->id;
                    //unset($fields->$key);
                    $new_fields['manufacturer_id'] = $manufacturer_id;
		    		break 1;

                case 'security_software_description':
                    $db_model = new SecuritySoftware;
                    $vals = ['description'=>$val];
                    $entry = null;
					try {
				                $entry = $db_model::create($vals);
					} catch(QueryException $e) {
						$error = ['error' => $e->getMessage()];
						return $error;
			   	    }
                    $security_software_id =$entry->id;
                    //unset($fields->$key);
                    $new_fields['security_software_id'] = $security_software_id;
		    		break 1;

                case 'backup_solution_description':
                    $db_model = new BackupSolution;
                    $vals = ['description'=>$val];
                    $entry = null;
					try {
				                $entry = $db_model::create($vals);
					} catch(QueryException $e) {
						$error = ['error' => $e->getMessage()];
						return $error;
			   	    }
                    $backup_solution_id = $entry->id;
                    //unset($fields->$key);
                    $new_fields['backup_solution_id'] = $backup_solution_id;
		    		break 1;

                case 'vendor_name':
                    $db_model = new Vendor;
                    $vals = ['name'=>$val];
                    $entry = null;
					try {
				                $entry = $db_model::create($vals);
					} catch(QueryException $e) {
						$error = ['error' => $e->getMessage()];
						return $error;
			   	    }
                    $vendor_id = $entry->id;
                    //unset($fields->$key);
                    $new_fields['vendor_id'] = $vendor_id;
		    		break 1;

				default: 
					break 1;
            }

        }
    }
	return $new_fields;

    }
}
