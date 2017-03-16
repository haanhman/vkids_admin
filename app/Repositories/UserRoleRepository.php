<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface UserRoleRepository
 * @package namespace App\Repositories;
 */
interface UserRoleRepository extends RepositoryInterface
{
    /**
     * @param $userId
     * @param array $roles
     * @param bool $created
     * @return mixed
     */
    public function assignUserRole($userId, $roles = [], $created = false);
}
