<?php

namespace App\Models;

use App\Traits\HasImage;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\Company
 *
 * @property int $id
 * @property string $ar_name
 * @property string $en_name
 * @property string|null $email
 * @property string|null $website
 * @property string|null $insta
 * @property string|null $twitter
 * @property string|null $facebook
 * @property string|null $snapchat
 * @property string|null $linkedin
 * @property string|null $about
 * @property mixed|null $location
 * @property string|null $logo
 * @property int $is_active
 * @property int $has_paid
 * @property int|null $average_rate
 * @property int $user_id
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CompanyPhone[] $company_phones
 * @property-read int|null $company_phones_count
 * @property-read mixed $array_phones
 * @property-read mixed $phones
 * @property-read \Illuminate\Database\Eloquent\Collection|ImageItem[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WorkTime[] $work_hours
 * @property-read int|null $work_hours_count
 * @method static \Database\Factories\CompanyFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereArName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereAverageRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereEnName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereHasPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereInsta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereLinkedin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereSnapchat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereWebsite($value)
 * @mixin \Eloquent
 * @property string|null $image
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereImage($value)
 * @property-read int|null $phones_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WorkTime[] $workTimes
 * @property-read int|null $work_times_count
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company withDomainImage($select = null)
 * @property string|null $instagram
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Rate[] $rates
 * @property-read int|null $rates_count
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereInstagram($value)
 * @method static Builder|Company withFavoriteStatus()
 * @property string $phone
 * @property string $whatsapp
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Favorite[] $favorites
 * @property-read int|null $favorites_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CompanyUpdate[] $updates
 * @property-read int|null $updates_count
 * @method static Builder|Company wherePhone($value)
 * @method static Builder|Company wherePhones($value)
 * @method static Builder|Company whereWhatsapp($value)
 * @method static Builder|Company canAppear()
 * @property string $slug
 * @property int $is_featured
 * @property int|null $order
 * @method static Builder|Company whereIsFeatured($value)
 * @method static Builder|Company whereOrder($value)
 * @method static Builder|Company whereSlug($value)
 */
class Company extends Model
{
    use CrudTrait;
    use HasFactory;
    use HasImage;


    protected $fillable = [
        'ar_name',
        'en_name',
        'email',
        'phone',
        'whatsapp',
        'website',
        'twitter',
        'facebook',
        'instagram',
        'linkedin',
        'snapchat',
        'about',
        'location',
        'image',
        'average_rate',
        'user_id',
        'slug',
        'category_id',
        'tags',
        'phones',
    ];
    protected $casts = [
        'location' => 'object',
        'tags' => 'array',
        'phones' => 'array',
        'about' => 'string'
    ];


    public function workTimes()
    {
        return $this->hasMany(WorkTime::class);
    }

    public function images()
    {
        return $this->morphMany(ImageItem::class, 'related');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function updateAverageRate()
    {
        $this->average_rate = (float)$this->rates()->average('rate');
        $this->save();
        return $this->average_rate;
    }

    public function scopeWithDomainImage($query, $select = null)
    {
        $str = '*,concat(?,image) as image';
        if ($select != null) {
            $str = implode(',', $select) . ',concat(?,image) as image';
        }
        return $query->selectRaw($str, [config('kuwait.storage_link')]);
    }

    public function scopeCanAppear($query)
    {

        return $query->whereIsActive(true)->whereHasPaid(true)
            ->whereHas('category', function ($q) {
                $q->canAppear();
            });
    }

    public function scopeWithFavoriteStatus(Builder $query)
    {
        if (auth('sanctum')->check()) {
            return $query
                ->addSelect(DB::raw('(SELECT count(*) FROM user_favorite_companies where user_favorite_companies.user_id = ' . auth('sanctum')->id() . ' and  user_favorite_companies.company_id = id ) > 0 as has_favorite'));
        }
        return $query->addSelect(DB::raw('0 as has_favorite'));
    }


    public function updates()
    {
        return $this->hasMany(CompanyUpdate::class);
    }

    public function trustRequest()
    {
        return $this->hasOne(CompanyTrustRequest::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function setTagsAttribute($value)
    {
        $this->attributes['tags'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
