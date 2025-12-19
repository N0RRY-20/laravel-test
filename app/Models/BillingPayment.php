<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingPayment extends Model
{
    protected $guarded = ['id'];

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }
}
