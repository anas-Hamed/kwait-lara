<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function PHPUnit\Framework\throwException;

/**
 * App\Models\Favorite
 *
 * @property int $id
 * @property int $user_id
 * @property int $related_id
 * @property string $related_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite query()
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereRelatedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereRelatedType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereUserId($value)
 * @mixin \Eloquent
 * @property int $company_id
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereCompanyId($value)
 */
class Favorite extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id', 'related_id', 'related_type'
    ];

    protected $table = 'user_favorite_companies';
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
