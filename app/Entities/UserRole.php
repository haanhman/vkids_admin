<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * App\Entities\UserRole
 *
 * @mixin \Eloquent
 * @property int $user_id
 * @property int $role_id
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\UserRole whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\UserRole whereRoleId($value)
 * @property-read \App\Entities\Permission $permission
 */
class UserRole extends Model implements Transformable
{
    use TransformableTrait;
    protected $table = 'user_role';
    protected $fillable = ['user_id', 'role_id'];
    public $timestamps = false;

    public function permission()
    {
        return $this->hasOne(Permission::class, 'role_id', 'role_id');
    }

}
