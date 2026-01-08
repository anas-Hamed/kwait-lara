<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WorkHour
 *
 * @property int $id
 * @property int $day
 * @property string $start_time
 * @property string $end_time
 * @property int $company_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\WorkHourFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkTime newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkTime newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkTime query()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkTime whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkTime whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkTime whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkTime whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkTime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkTime whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkTime whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property bool $active
 * @method static \Illuminate\Database\Eloquent\Builder|WorkTime whereActive($value)
 */
class WorkTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'start_time', 'end_time', 'day','active'
    ];
    protected $casts = [
        'active' => 'boolean'
    ];
}
