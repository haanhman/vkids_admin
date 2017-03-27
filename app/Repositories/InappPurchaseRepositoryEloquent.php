<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\InappPurchaseRepository;
use App\Entities\InappPurchase;
use App\Validators\InappPurchaseValidator;

/**
 * Class InappPurchaseRepositoryEloquent
 * @package namespace App\Repositories;
 */
class InappPurchaseRepositoryEloquent extends BaseRepository implements InappPurchaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return InappPurchase::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
