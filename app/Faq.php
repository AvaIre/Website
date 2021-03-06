<?php

namespace App;

use App\Traits\HideTimestampsTrait;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HideTimestampsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category', 'title', 'body'
    ];
}
