<?php

namespace App\Entities\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * App\Entities\Api\Player
 *
 * @property int $id
 * @property string $nickname
 * @property string $avatar
 * @property int $coin
 * @property int $diamond
 * @property int $score
 * @property string $age_range
 * @property int $dollar_per_month
 * @property string $social_id
 * @property bool $social_type
 * @property string $social_info
 * @property string $social_accept
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Api\UserDevice[] $devices
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\Player whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\Player whereNickname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\Player whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\Player whereCoin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\Player whereDiamond($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\Player whereScore($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\Player whereAgeRange($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\Player whereDollarPerMonth($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\Player whereSocialId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\Player whereSocialType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\Player whereSocialInfo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\Player whereSocialAccept($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\Player whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\Player whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Api\Player whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Player extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;
    protected $connection = 'mysql_api';
    protected $table = 'users';
    protected $fillable = ['nickname', 'avatar', 'coin', 'diamond', 'score', 'age_range', 'dollar_per_month', 'social_id', 'social_type', 'social_info', 'social_accept'];

    public function devices()
    {
        return $this->hasMany(PlayerDevice::class, 'user_id', 'id');
    }

}