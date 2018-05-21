<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaction';

    public static $acceptedFilters = 'customer_id, amount, date';

    public $timestamps = false;

    protected $fillable = ['customer_id', 'amount', 'date'];

    public static function boot()
    {

        static::creating(function ($record){
            return $record->date =  Carbon::now();
        });
        parent::boot();

    }

    public function user()
    {
        return $this->belongsTo(Customer::class);
    }


    public function getDateAttribute($val)
    {
        return (new Carbon($val))->format('d.m.Y');
    }

}
