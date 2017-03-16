<?php

namespace App\Criteria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class PlayerCriteria
 * @package namespace App\Criteria;
 */
class PlayerCriteria extends MyCriteria
{
    /**
     * @param Model $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if (isset($this->_params['q']) && !empty($this->_params['q'])) {
            $model->where(function ($query) {
                $query->orWhere('id', '=', $this->_params['q']);
                $query->orWhere('nickname', 'LIKE', '%' . addslashes($this->_params['q']) . '%');
            });
        }
        if (isset($this->_params['block']) && $this->_params['block'] == 1) {
            $model->onlyTrashed();
        }
        return $model;
    }
}
