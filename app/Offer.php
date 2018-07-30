<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Offer extends Model
{
    protected $table      = 'offer';
    protected $primaryKey = 'offer_id';
    protected $fillable   = ['offer_name', 'offer_discount'];
}
