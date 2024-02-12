<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
	protected $connection = "warehouse";
    protected $table = 'tbl_logs';

    protected $primaryKey = 'logId';

    protected $fillable = [
        'tblName' 
    ];

    public $timestamps = false;
}
