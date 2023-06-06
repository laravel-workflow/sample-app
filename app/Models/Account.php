<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory, HasUuid;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
