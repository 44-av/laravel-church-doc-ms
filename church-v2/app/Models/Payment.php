<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'tpayments';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'request_id',
        'name',
        'amount',
        'payment_date',
        'payment_method',
        'payment_status',
        'transaction_id',
    ];

    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id', 'id');
    }

    public function payment() { 
        return $this->hasOne(Payment::class);
         }
        
};