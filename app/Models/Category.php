<?php

namespace App\Models;

use App\Traits\HasImage;
use App\Traits\TranslateTrait;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property int $is_active
 * @property int $order
 * @property string|null $image
 * @property int|null $parent_id
 * @property int $depth
 * @property int $lft
 * @property int $rgt
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Category[] $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Company[] $companies
 * @property-read int|null $companies_count
 * @property-read Category|null $parent
 * @method static \Database\Factories\CategoryFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Category withDomainImage($select = null)
 * @property-read array $translations
 * @method static \Illuminate\Database\Eloquent\Builder|Category canAppear()
 */
class Category extends Model
{
    use HasFactory;
    use CrudTrait;
    use HasImage;
    use TranslateTrait;


    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $ratio = 1;

    protected $has_thumbs = false;
    protected $destination_path = 'uploads/categories';
    protected $imageType = 'png';
    public $translatable = ['name'];


    protected $fillable = ['name', 'parent_id', 'is_active', 'order', 'image'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function companies()
    {
        return $this->hasMany(Company::class);
    }


    public function scopeWithDomainImage($query)
    {
        return $query->selectRaw('*,concat(?,image) as image', [config('kuwait.storage_link')]);
    }
    public function scopeCanAppear($query)
    {
        return $query->whereIsActive(true)->whereHas('parent',function ($q){
            $q->whereIsActive(true);
        });
    }

    protected static function boot()
    {
        parent::boot();
        self::deleted(function ($category) {
            $disk = Storage::disk('public');
            if ($category->image != null) {
                if ($disk->exists($category->image)) {
                    $disk->delete($category->image);
                }
                if ($disk->exists(substr($category->image, 0, -3) . "thumb.large.jpg")) {
                    $disk->delete(substr($category->image, 0, -3) . "thumb.large.jpg");
                }
                if ($disk->exists(substr($category->image, 0, -3) . "thumb.small.jpg")) {
                    $disk->delete(substr($category->image, 0, -3) . "thumb.small.jpg");
                }
            }
        });
    }




}
