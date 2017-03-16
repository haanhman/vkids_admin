<?php

namespace App\Repositories\Api;

use App\Repositories\MyRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Entities\Api\Player;

/**
 * Class PlayerRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PlayerRepositoryEloquent extends MyRepository implements PlayerRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Player::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getSocialType() {
        return [
            1 => 'Facebook',
            2 => 'Zalo',
            3 => 'Google'
        ];
    }
}
