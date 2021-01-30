<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddOnDevice extends Model
{
    protected $table = 'addon_devices';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'notes',
	'main_device_id',
	'device_type_id',
	'warranty_id',
	'manufacturer_id',
	'vendor_id',
	'buy_date',
	'serial_no',
	'warranty_valid_until',
	'active',
	'order_no',
	'product_no',
	'attachments',
	'created_at',
	'updated_at'
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

	public function device()
    {
        return $this->belongsTo('App\Device', 'main_device_id');
    }
	public function deviceType()
    {
	return $this->belongsTo('App\DeviceType', 'device_type_id');
    }
	public function warranty()
    {
	return $this->belongsTo('App\Warrantie', 'warranty_id');
    }
	public function manufacturer()
    {
	return $this->belongsTo('App\Manufacturer', 'manufacturer_id');
    }
	public function vendor()
    {
	return $this->belongsTo('App\Vendor', 'vendor_id');
    }
    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
