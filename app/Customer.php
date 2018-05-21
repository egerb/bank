<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $table = 'customer';

    public $timestamps = false;

    protected $fillable = ['name', 'cnp'];


    public function transactions()
    {
        return $this->hasMany('transaction', 'customer_id', 'id');
    }

}
