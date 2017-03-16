<?php

namespace App\Repositories;

use Carbon\Carbon;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Entities\Permission;

/**
 * Class PermissionRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PermissionRepositoryEloquent extends MyRepository implements PermissionRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Permission::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getPermission($roleId)
    {
        return $this->findWhere(['role_id' => $roleId])->first();
    }

    public function updatePermission($roleId, $jsonPermission)
    {
        $now = Carbon::now() . '';
        $this->replaceORM([
            'role_id' => $roleId,
            'permission' => $jsonPermission,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
