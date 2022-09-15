<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'address',
        'email',
        'phone',
        'fax',
        'facebook',
        'instagram',
        'twitter',
        'pinterest',
        'election_year',
        'vigency_year',
        'max_cellphone',
        'max_mails',
        'allow_functions',
        'default_role'
    ];
}
