<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainTime extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'train_time';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'data'];
}
