<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlugRedirect extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'model_type',
        'old_slug',
        'target_slug',
    ];
}
