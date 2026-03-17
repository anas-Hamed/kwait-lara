<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use CrudTrait, HasFactory;

    protected $table = 'plans';
    protected $guarded = ['id'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
