<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'customer_token',
	'name',
        'address',
	'contact_person_name',
	'active',
	'notes',
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

    public function reg_validate(Request $request){
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


    public function devices()
    {
        return $this->hasMany('App\Device');
    }
    public function users()
    {
        return $this->hasMany('App\User');
    }
public function contact_user()
    {
        return $this->belongsTo('App\User');
    }
    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

}
