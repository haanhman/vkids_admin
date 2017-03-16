<?php

namespace App\Entities\Api;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * App\Entities\Api\UserDevice
 *
 * @property int $id
 * @property int $user_id
 * @property string $device_id
 * @property string $name
 * @property bool $os
 * @property string $version
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\UserDevice whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\UserDevice whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\UserDevice whereDeviceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\UserDevice whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\UserDevice whereOs($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\UserDevice whereVersion($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\UserDevice whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\UserDevice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\UserDevice whereDeletedAt($value)
 * @mixin \Eloquent
 */
class PlayerDevice extends Model implements Transformable
{
    use TransformableTrait;
    protected $connection = 'mysql_api';
    protected $table = 'user_device';
    protected $fillable = ['user_id', 'device_id', 'name', 'os', 'version'];
}
