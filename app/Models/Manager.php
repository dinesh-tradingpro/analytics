<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    protected $primaryKey = 'manager_id';

    protected $keyType = 'int';

    public $incrementing = false;

    protected $fillable = [
        'manager_id',
        'full_name',
        'phone',
        'email',
    ];
}
