<?php

namespace App\Http\Controllers;

use App\Repositories\PermissionRepositoryEloquent;
use App\Repositories\RoleRepositoryEloquent;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

class RoleController extends MyController
{
    /**
     * @var RoleRepositoryEloquent
     */
    private $_role;

    /**
     * @var PermissionRepositoryEloquent
     */
    private $_permission;

    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    private $routes;

    function __construct(Router $router, RoleRepositoryEloquent $role, PermissionRepositoryEloquent $permission)
    {
        parent::__construct($router);
        $this->_role = $role;
        $this->_permission = $permission;
        $this->router = $router;
    }

    public function index()
    {
        $this->_header = trans('role.header.index');
        $data = array();
        $data['listRole'] = $this->_role->orderBy('id', 'DESC')->all();
        return $this->view('admin.role.index', ['data' => $data]);
    }

    public function create()
    {
        $this->_header = trans('role.header.create');
        return $this->view('admin.role.form');
    }

    public function store()
    {
        $data = $this->getAllParams();
        $validator = \Validator::make($data, [
            'name' => 'required|unique:role',
        ]);
        if ($validator->fails()) {
            return $this->submitError($validator->errors());
        }
        $this->_role->create(['name' => $data['name']]);

        $this->_logger->debug('create role [' . $data['name'] . '] success');

        return redirect()->route('role.index')->with(['message' => trans('role.message.create')]);
    }

    public function edit($id)
    {
        $this->_header = trans('role.header.edit');
        $data = array();
        $data['role'] = $this->_role->find($id);
        return $this->view('admin.role.form', $data);
    }

    public function update($id)
    {

        $data = $this->getAllParams();
        $validator = \Validator::make($data, [
            'name' => 'required|unique:role,name,' . $id . ',id',
        ]);
        if ($validator->fails()) {
            return $this->submitError($validator->errors());
        }
        $this->_role->update(['name' => $data['name']], $id);

        $this->_logger->debug('update role [' . $data['name'] . '] success');

        return redirect()->route('role.index')->with(['message' => trans('role.message.create')]);

    }


    public function permission($id)
    {
        $data = array();
        $data['currentPermission'] = array();
        $data['role'] = $this->_role->find($id);
        $permission = $this->_permission->getPermission($id);
        if (!empty($permission) && $permission->permission != '') {
            $json = json_decode($permission->permission, true);
            foreach ($json as $item) {
                $data['currentPermission'][] = $item['name'] . '-' . $item['method'];
            }
        }

        $this->routes = $this->router->getRoutes();
        $data['routes'] = $this->getRoutes();

        $this->_header = trans('role.header.permission', ['role' => $data['role']->name]);

        $this->_logger->debug('create role [' . $data['role']->name . '] success');

        return $this->view('admin.role.permission', $data);
    }

    public function setPermission($id)
    {
        $selectedRoute = array();
        $data = $this->getAllParams();
        if (isset($data['route']) && !empty($data['route'])) {
            $this->routes = $this->router->getRoutes();
            $listRouter = $this->getRoutes();
            foreach ($data['route'] as $name) {
                if (isset($listRouter[$name])) {
                    $selectedRoute[] = $listRouter[$name];
                }
            }
        }

        $this->_permission->updatePermission($id, json_encode($selectedRoute));
        return redirect()->route('role.index')->with(['message' => trans('role.message.permission')]);
    }


    /**
     * Compile the routes into a displayable format.
     *
     * @return array
     */
    private function getRoutes()
    {
        $results = [];

        foreach ($this->routes as $route) {
            $item = $this->getRouteInformation($route);
            $arr = explode(',', $item['middleware']);
            if (in_array('auth', $arr) && !empty($item['name'])) {
                $parts = explode('.', $item['name']);
                if (array_pop($parts) == 'ignore') {
                    continue;
                }
                $key = $item['name'] . '-' . $item['method'];
                $results[$key] = $item;
            }
        }
        return array_filter($results);
    }

    /**
     * Get the route information for a given route.
     *
     * @param  \Illuminate\Routing\Route $route
     * @return array
     */
    private function getRouteInformation(Route $route)
    {
        return [
            'host' => $route->domain(),
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'action' => $route->getActionName(),
            'middleware' => $this->getMiddlewareInfo($route),
        ];
    }

    /**
     * Get before filters.
     *
     * @param  \Illuminate\Routing\Route $route
     * @return string
     */
    private function getMiddlewareInfo($route)
    {
        return collect($route->gatherMiddleware())->map(function ($middleware) {
            return $middleware instanceof Closure ? 'Closure' : $middleware;
        })->implode(',');
    }

}
