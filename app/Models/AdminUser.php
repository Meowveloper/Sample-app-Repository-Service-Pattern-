<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class AdminUser extends Model implements Authenticatable
{
    use HasFactory;
    use AuthenticatableTrait;

    protected $guarded = [];
}
