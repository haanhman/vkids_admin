<?php

namespace App\Repositories\Api;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface UserDeviceRepository
 * @package namespace App\Repositories;
 */
interface PlayerDeviceRepository extends RepositoryInterface
{
    public function getListOs();
}
