<?php

namespace App\Models;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\Image
 *
 * @property int $id
 * @property int $related_id
 * @property string $related_type
 * @property string $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $imageable
 * @property-read Model|\Eloquent $related
 * @property-write mixed $image
 * @method static \Database\Factories\ImageFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageItem wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageItem whereRelatedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageItem whereRelatedType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageItem whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ImageItem withDomainPath()
 */
class ImageItem extends Model
{

    use HasFactory;
    use HasImage;

    protected $fillable = [
        'path', 'related_id', 'related_type'
    ];

    protected $image_attribute_name = 'path';
    protected $ratio = 4 / 3;
    protected $has_thumbs = false;
    protected $destination_path = 'uploads/';
    protected $table = 'images';
    protected $hidden = ['related_type', 'related_id'];

    public static function withDestination($destination, $ratio)
    {
        $instance = new self();
        $instance->destination_path = "uploads/$destination";
        $instance->ratio = $ratio;
        return $instance;
    }

    public function related()
    {
        return $this->morphTo();
    }


    public function setPathAttribute($value)
    {
        $this->setImageAttribute($value);
    }

    public function scopeWithDomainPath($query)
    {
        return $query->selectRaw('*,concat(?,path) as path', [config('kuwait.storage_link')]);
    }

    protected static function boot()
    {
        parent::boot();
        self::deleted(function ($image) {
            $disk = Storage::disk('public');
            if ($image->path != null) {
                if ($disk->exists($image->path)) {
                    $disk->delete($image->path);
                }
                if ($disk->exists(substr($image->path, 0, -3) . "thumb.large.jpg")) {
                    $disk->delete(substr($image->path, 0, -3) . "thumb.large.jpg");
                }
                if ($disk->exists(substr($image->path, 0, -3) . "thumb.small.jpg")) {
                    $disk->delete(substr($image->path, 0, -3) . "thumb.small.jpg");
                }
            }
        });
    }
}
