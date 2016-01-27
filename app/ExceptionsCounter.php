<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExceptionsCounter extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exceptions_counter';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['counter'];
}
