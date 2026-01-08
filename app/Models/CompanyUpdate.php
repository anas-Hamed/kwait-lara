<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CompanyUpdate
 *
 * @property int $id
 * @property int $company_id
 * @property mixed|null $old_values
 * @property mixed $new_values
 * @property int $type
 * @property int $is_applied
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUpdate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUpdate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUpdate query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUpdate whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUpdate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUpdate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUpdate whereIsApplied($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUpdate whereNewValues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUpdate whereOldValues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUpdate whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUpdate whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Company $company
 */
class CompanyUpdate extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'old_values' => 'object',
        'new_values' => 'object'
    ];


    public function company(){
        return $this->belongsTo(Company::class);
    }
}
