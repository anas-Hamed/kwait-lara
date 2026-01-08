<?php
namespace App\Models;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Notifications\DatabaseNotification;

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
 * @method static Builder|DatabaseNotification read()
 * @method static Builder|DatabaseNotification unread()
 * @method static \Illuminate\Database\Eloquent\Builder|ExtendedDatabaseNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtendedDatabaseNotification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtendedDatabaseNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtendedDatabaseNotification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtendedDatabaseNotification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtendedDatabaseNotification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtendedDatabaseNotification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtendedDatabaseNotification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ExtendedDatabaseNotification extends DatabaseNotification{
    use CrudTrait ;
    public function getView()
    {
        return view('notification_template',['notification' => $this]);
    }
    public function getUrl()
    {

        return backpack_url($this->data['url'] ?? '/');
    }
}
