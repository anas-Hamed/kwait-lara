<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CompanyTrustRequest
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyTrustRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyTrustRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyTrustRequest query()
 * @mixin \Eloquent
 */
class CompanyTrustRequest extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $guarded = ['id'];


    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trustCompany()
    {
        $url = backpack_url("company-trust-request/$this->id/trust");
        return "<a class='btn btn-success btn-sm' href='$url'>توثيق</a>";
    }


}
