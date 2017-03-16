<?php

namespace App\Repositories\Api;

use App\Entities\Api\PlayerDevice;
use App\Repositories\MyRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class UserDeviceRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PlayerDeviceRepositoryEloquent extends MyRepository implements PlayerDeviceRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PlayerDevice::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getListOs()
    {
        return [1 => 'iOS', 2 => 'Android'];
    }


}
