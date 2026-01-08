<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
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
 */
	class Ad extends \Eloquent {}
}

namespace App\Models{
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
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ImageItem[] $images
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
 */
	class Company extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CompanyPhone
 *
 * @property int $id
 * @property int $company_id
 * @property string $number
 * @property int $is_whatsapp
 * @property int $is_call
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CompanyPhoneFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone whereIsCall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone whereIsWhatsapp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone whereUpdatedAt($value)
 */
	class CompanyPhone extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CompanyTag
 *
 * @property int $id
 * @property int $company_id
 * @property int $tag_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CompanyTagFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyTag query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyTag whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyTag whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyTag whereUpdatedAt($value)
 */
	class CompanyTag extends \Eloquent {}
}

namespace App\Models{
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
 */
	class CompanyUpdate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ContactUs
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string $phone
 * @property string $message
 * @property string|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ContactUs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactUs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactUs query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactUs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactUs whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactUs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactUs whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactUs whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactUs wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactUs whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactUs whereUpdatedAt($value)
 */
	class ContactUs extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ExtendedDatabaseNotification
 *
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $notifiable
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection|static[] all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection|static[] get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|ExtendedDatabaseNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExtendedDatabaseNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExtendedDatabaseNotification query()
 * @method static \Illuminate\Database\Eloquent\Builder|DatabaseNotification read()
 * @method static \Illuminate\Database\Eloquent\Builder|DatabaseNotification unread()
 * @method static \Illuminate\Database\Eloquent\Builder|ExtendedDatabaseNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtendedDatabaseNotification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtendedDatabaseNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtendedDatabaseNotification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtendedDatabaseNotification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtendedDatabaseNotification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtendedDatabaseNotification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtendedDatabaseNotification whereUpdatedAt($value)
 */
	class ExtendedDatabaseNotification extends \Eloquent {}
}

namespace App\Models{
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
 */
	class Favorite extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Image
 *
 * @property int $id
 * @property int $related_id
 * @property string $related_type
 * @property string $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $imageable
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $related
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
 */
	class Image extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Rate
 *
 * @property int $id
 * @property int $company_id
 * @property int $user_id
 * @property int $rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Rate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rate query()
 * @method static \Illuminate\Database\Eloquent\Builder|Rate whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rate whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rate whereUserId($value)
 */
	class Rate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Tag
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Company[] $companies
 * @property-read int|null $companies_count
 * @method static \Database\Factories\TagFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereUpdatedAt($value)
 */
	class Tag extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $api_token
 * @property int $is_active
 * @property int $is_admin
 * @property string|null $fcm
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Company[] $companies
 * @property-read int|null $companies_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Favorite[] $favorites
 * @property-read int|null $favorites_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\App\Models\ExtendedDatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Company[] $ratedCompanies
 * @property-read int|null $rated_companies_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFcm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
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
 */
	class WorkHour extends \Eloquent {}
}

