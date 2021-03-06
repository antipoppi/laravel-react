<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BackupSolution extends Model
{
    protected $table = 'backup_solutions';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description'
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
	
    public function devices()
    {
        return $this->hasMany('App\Device', 'backup_solution_id');
    }
    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
