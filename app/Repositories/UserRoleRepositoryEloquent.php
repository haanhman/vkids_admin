<?php

namespace App\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Entities\UserRole;

/**
 * Class UserRoleRepositoryEloquent
 * @package namespace App\Repositories;
 */
class UserRoleRepositoryEloquent extends MyRepository implements UserRoleRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return UserRole::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function assignUserRole($userId, $roles = [], $created = false)
    {
        if(!$created) {
            $this->deleteWhere(['user_id' => $userId]);
        }
        if (!empty($roles)) {
            foreach ($roles as $roleId) {
                $this->create([
                    'user_id' => $userId,
                    'role_id' => $roleId
                ]);
            }
        }
    }
}
