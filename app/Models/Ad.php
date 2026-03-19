<?php

namespace App\Models;

use App\Traits\HasImage;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\Ad
 *
 * @property int $id
 * @property string|null $text
 * @property string $image
 * @property string $start_date
 * @property string|null $end_date
 * @property int $view_count
 * @property int $order
 * @property int $is_active
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @method static \Illuminate\Database\Eloquent\Builder|Ad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ad newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ad query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ad whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ad whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ad whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ad whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ad whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ad whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ad whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ad whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ad whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ad whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ad whereViewCount($value)
 * @mixin \Eloquent
 * @property string $title
 * @method static \Illuminate\Database\Eloquent\Builder|Ad whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ad withDomainImage()
 */
class Ad extends Model
{

    use CrudTrait, HasFactory, HasImage;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $ratio = 10 / 4;
    protected $destination_path = 'uploads/ads';

    protected $table = 'ads';

    protected $fillable = ['title', 'image'];


    public function scopeWithDomainImage($query)
    {
        return $query->selectRaw('*,concat(?,image) as image', [config('kuwait.storage_link')]);
    }
    protected static function boot()
    {
        parent::boot();
        self::deleted(function ($ad) {
            $disk = Storage::disk('public');
            if ($ad->image != null) {
                if ($disk->exists($ad->image)) {
                    $disk->delete($ad->image);
                }
                if ($disk->exists(substr($ad->image, 0, -3) . "thumb.large.jpg")) {
                    $disk->delete(substr($ad->image, 0, -3) . "thumb.large.jpg");
                }
                if ($disk->exists(substr($ad->image, 0, -3) . "thumb.small.jpg")) {
                    $disk->delete(substr($ad->image, 0, -3) . "thumb.small.jpg");
                }
            }
        });
    }

}
