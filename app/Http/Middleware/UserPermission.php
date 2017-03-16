<?php

namespace App\Http\Middleware;

use App\Entities\User;
use Closure;
use Illuminate\Routing\Router;

class UserPermission
{
    private $_currentRoute;
    private $_checkPermission = true;
    function __construct(Router $router)
    {
        $current = $router->getCurrentRoute();

        $parts = explode('.', $current->getName());
        $this->_checkPermission = array_pop($parts) != 'ignore';

        $this->_currentRoute = $current->getName() . '-' . implode('|', $current->getMethods());
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = \Auth::user();
        if ($user == null) {
            throw new \Exception('Please authentication');
        }

        if($this->_checkPermission) {
            if ($user->system_admin == 0) {
                $this->checkPermission($user);
            }
        }
        return $next($request);
    }

    private function checkPermission(User $user)
    {
        if (empty($user->roles)) {
            throw new \Exception('Permission: access define');
        }
        $routers = array();
        foreach ($user->roles as $item) {
            $json = json_decode($item->permission->permission, true);
            if (!empty($json)) {
                foreach ($json as $route) {
                    $routers[] = $route['name'] . '-' . $route['method'];
                }
            }
        }
        if(!in_array($this->_currentRoute, $routers)) {
            throw new \Exception('Permission: access define');
        }
    }
}
