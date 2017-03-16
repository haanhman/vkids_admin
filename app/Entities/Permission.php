<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * App\Entities\Permission
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $role_id
 * @property string $permission
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Permission whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Permission whereRoleId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Permission wherePermission($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Permission whereUpdatedAt($value)
 */
class Permission extends Model implements Transformable
{
    use TransformableTrait;
    protected $table = 'permission';

    protected $fillable = ['role_id', 'permission'];

}
