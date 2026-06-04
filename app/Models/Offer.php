<?php

namespace App\Models;

use App\Traits\HasImage;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Offer
 *
 * @property int $id
 * @property int $company_id
 * @property string $ar_title
 * @property string $en_title
 * @property string|null $ar_description
 * @property string|null $en_description
 * @property string|null $image
 * @property float|null $old_price
 * @property float|null $new_price
 * @property \Illuminate\Support\Carbon $starts_at
 * @property \Illuminate\Support\Carbon $ends_at
 * @property int $is_active
 * @property-read \App\Models\Company $company
 * @property-read int|null $discount_percent
 * @property-read bool $is_expired
 */
class Offer extends Model
{
    use CrudTrait;
    use HasFactory;
    use HasImage;

    protected $destination_path = 'uploads/offers';

    protected $fillable = [
        'company_id',
        'ar_title',
        'en_title',
        'ar_description',
        'en_description',
        'image',
        'old_price',
        'new_price',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'old_price' => 'decimal:3',
        'new_price' => 'decimal:3',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $appends = ['discount_percent', 'is_expired'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Discount percentage derived from old/new price (null when not applicable).
     */
    public function getDiscountPercentAttribute()
    {
        if (!$this->old_price || !$this->new_price || $this->old_price <= 0 || $this->new_price >= $this->old_price) {
            return null;
        }
        return (int)round((($this->old_price - $this->new_price) / $this->old_price) * 100);
    }

    public function getIsExpiredAttribute()
    {
        return $this->ends_at !== null && $this->ends_at->isPast();
    }

    /**
     * Active offers within their date window.
     */
    public function scopeRunning(Builder $query)
    {
        return $query->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }

    public function scopeWithDomainImage($query, $select = null)
    {
        $str = '*,concat(?,image) as image';
        if ($select != null) {
            $str = implode(',', $select) . ',concat(?,image) as image';
        }
        return $query->selectRaw($str, [config('kuwait.storage_link')]);
    }
}
