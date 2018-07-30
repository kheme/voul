<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Voucher extends Model
{
    protected $table      = 'voucher';
    protected $primaryKey = 'voucher_id';
    protected $fillable   = [
        'voucher_recipient_id', 'offer_discount',
        'voucher_offer_id', 'voucher_expiry_date',
        'voucher_used_date', 'voucher_code'
    ];

    /**
     * Return the model for the recipient who used this voucher
     *
     * @return model App/Recipient
     */
    public function recipient()
    {
        return $this->belongsTo(
            'App\Recipient',
            'recipient_id', 'voucher_recipient_id'
        );
    }

    /**
     * Return the model for the offer this voucher was used with
     *
     * @return model App/Offer
     */
    public function offer()
    {
        return $this->belongsTo(
            'App\Offer',
            'offer_id', 'voucher_offer_id'
        );
    }
}