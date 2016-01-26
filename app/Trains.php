<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trains extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trains';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sched', 'train_id', 'track'];
}
