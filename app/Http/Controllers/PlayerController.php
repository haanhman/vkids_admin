<?php

namespace App\Http\Controllers;

use App\Criteria\PlayerCriteria;
use App\Repositories\Api\PlayerDeviceRepositoryEloquent;
use Illuminate\Routing\Router;
use App\Repositories\Api\PlayerRepositoryEloquent;

class PlayerController extends MyController
{
    /**
     * @var PlayerRepositoryEloquent
     */
    private $_player;


    private $_playerDevice;
    public function __construct(Router $router, PlayerRepositoryEloquent $player, PlayerDeviceRepositoryEloquent $playerDevice)
    {
        parent::__construct($router);
        $this->_player = $player;
        $this->_playerDevice = $playerDevice;
    }

    public function index()
    {
        $this->_header = trans('player.header.index');
        $data = array();
        $data['params'] = $this->getAllParams();
        $criteria = new PlayerCriteria($data['params']);

        $data['listPlayer'] = $this->_player->with('devices')
            ->withTrashed()
            ->orderBy('id', 'DESC')
            ->pushCriteria($criteria)
            ->paginate(20);

        $data['socialType'] = $this->_player->getSocialType();
        $data['deviceName'] = $this->_playerDevice->getListOs();
        return $this->view('admin.player.index', $data);
    }

    public function lock($id)
    {
        $player = $this->_player->withTrashed()->find($id);
        if (!empty($player->deleted_at)) {
            $this->_player->restore($player);
            $message = trans('player.message.unblock');
        } else {
            $this->_player->delete($id);
            $message = trans('player.message.block');
        }
        $this->_logger->debug($message . '[' . $player->id . ' - ' . $player->nickname . ']');
        return redirect()->route('player.index')->with(['message' => $message]);
    }

}
