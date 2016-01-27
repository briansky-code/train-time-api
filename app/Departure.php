<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departure extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'departure';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['data'];
}
