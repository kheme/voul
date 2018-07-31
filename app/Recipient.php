<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Recipient extends Model
{
    protected $table      = 'recipient';
    protected $primaryKey = 'recipient_id';
    protected $fillable   = [
        'recipient_name', 'recipient_surname', 'recipient_email'
    ];

}
