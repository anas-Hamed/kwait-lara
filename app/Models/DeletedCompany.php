<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DeletedCompany extends Model
{
    use CrudTrait;
    use HasFactory;

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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workTimes()
    {
        return $this->hasMany(DeletedWorkTime::class);
    }
    public function images()
    {
        return $this->morphMany(ImageItem::class,'related');
    }

    protected static function boot()
    {
        parent::boot();
        self::deleted(function ($company) {
            $disk = Storage::disk('public');
            if ($company->image != null) {
                if ($disk->exists($company->image)) {
                    $disk->delete($company->image);
                }
                if ($disk->exists(substr($company->image, 0, -3) . "thumb.large.jpg")) {
                    $disk->delete(substr($company->image, 0, -3) . "thumb.large.jpg");
                }
                if ($disk->exists(substr($company->image, 0, -3) . "thumb.small.jpg")) {
                    $disk->delete(substr($company->image, 0, -3) . "thumb.small.jpg");
                }
            }
        });

        self::creating(function ($company) {
            $disk = Storage::disk('public');
            if ($company->image != null && $disk->exists($company->image)) {
                $disk->move($company->image, 'deleted/' . $company->image);
                $company->image = 'deleted/' . $company->image;
            }
        });
    }
}
