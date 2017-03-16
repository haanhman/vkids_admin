<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * App\Entities\Role
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Role whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Role whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends Model implements Transformable
{
    use TransformableTrait;
    protected $table = 'role';
    protected $fillable = ['name'];

}
