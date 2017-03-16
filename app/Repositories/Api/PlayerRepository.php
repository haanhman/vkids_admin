<?php

namespace App\Repositories\Api;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface PlayerRepository
 * @package namespace App\Repositories;
 */
interface PlayerRepository extends RepositoryInterface
{
    //
    public function getSocialType();
}
