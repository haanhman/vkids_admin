<?php

namespace App\Http\Controllers;

use App\Entities\User;
use App\Repositories\RoleRepositoryEloquent;
use App\Repositories\UserRepositoryEloquent;
use App\Repositories\UserRoleRepositoryEloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class UserController extends MyController
{
    const USER_FULL_ACCESS = 9999;
    /**
     * @var RoleRepositoryEloquent
     */
    private $_role;
    /**
     * @var UserRepositoryEloquent
     */
    private $_user;
    /**
     * @var UserRoleRepositoryEloquent
     */
    private $_userRole;

    function __construct(Router $router, UserRepositoryEloquent $user, RoleRepositoryEloquent $role, UserRoleRepositoryEloquent $userRole)
    {
        parent::__construct($router);
        $this->_role = $role;
        $this->_user = $user;
        $this->_userRole = $userRole;
    }

    public function index()
    {
        $this->_header = trans('users.header.index');
        $data = array();
        $data['listUser'] = $this->_user->with('roles')->withTrashed()->orderBy('id', 'DESC')->all();
        if (!empty($data['listUser'])) {
            $listRole = $this->_role->all();
            if (!empty($listRole)) {
                foreach ($listRole as $item) {
                    $data['listRole'][$item->id] = $item->name;
                }
            }
        }
        return $this->view('admin.users.index', $data);
    }

    public function create()
    {
        $this->_header = trans('users.header.create');
        $data = array();
        $data['role'] = $this->_role->all();
        $data['fullAccess'] = self::USER_FULL_ACCESS;
        return $this->view('admin.users.form', $data);
    }

    public function store()
    {
        $data = $this->getAllParams();
        $validator = \Validator::make($data, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            're_password' => 'required|same:password'
        ]);
        if ($validator->fails()) {
            return $this->submitError($validator->errors());
        }
        $attributes = array(
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => \Hash::make($data['password']),
            'lang' => $data['lang']
        );
        if (isset($data['role']) && $data['role'][0] == self::USER_FULL_ACCESS) {
            $attributes['system_admin'] = 1;
        } else {
            $attributes['system_admin'] = 0;
        }
        $newUser = $this->_user->create($attributes);
        if (!isset($data['role'])) {
            $data['role'] = array();
        } elseif ($data['role'][0] == self::USER_FULL_ACCESS) {
            $data['role'] = array();
        }
        if (!empty($data['role'])) {
            $this->_userRole->assignUserRole($newUser->id, $data['role'], true);
        }
        $this->_logger->debug('create new user [' . $data['email'] . '] success');
        return redirect()->route('users.index')->with(['message' => trans('users.message.create')]);
    }

    public function edit($id)
    {
        $this->_header = trans('users.header.edit');
        $data = array();
        $data['role'] = $this->_role->all();
        $data['user'] = $this->_user->find($id);
        if (!empty($data['user']->roles)) {
            foreach ($data['user']->roles as $item) {
                $data['userRole'][] = $item->role_id;
            }
        }
        $data['fullAccess'] = self::USER_FULL_ACCESS;
        return $this->view('admin.users.form', $data);
    }

    public function update($id)
    {
        $data = $this->getAllParams();
        $validator = \Validator::make($data, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id . ',id',
            'password' => 'min:6',
            're_password' => 'same:password'
        ]);
        if ($validator->fails()) {
            return $this->submitError($validator->errors());
        }

        $attributes = array(
            'name' => $data['name'],
            'email' => $data['email'],
            'lang' => $data['lang']
        );
        if (!empty($data['password'])) {
            $attributes['password'] = \Hash::make($data['password']);
        }
        if (isset($data['role']) && $data['role'][0] == self::USER_FULL_ACCESS) {
            $attributes['system_admin'] = 1;
        } else {
            $attributes['system_admin'] = 0;
        }
        $this->_user->update($attributes, $id);

        if (!isset($data['role'])) {
            $data['role'] = array();
        } elseif ($data['role'][0] == self::USER_FULL_ACCESS) {
            $data['role'] = array();
        }
        $this->_userRole->assignUserRole($id, $data['role']);

        if (\Auth::user() != null && $id == \Auth::user()->id) {
            setcookie('user_lang', $data['lang'], time() + 24 * 60 * 30, '/');
            \App::setLocale($data['lang']);
        }
        $this->_logger->debug('update user [' . $data['email'] . '] success');
        return redirect()->route('users.index')->with(['message' => trans('users.message.edit')]);
    }

    public function destroy($id)
    {
        $user = $this->_user->withTrashed()->find($id);
        if (!empty($user->deleted_at)) {
            $this->_user->restore($user);
            $message = trans('users.message.restore');
        } else {
            $this->_user->delete($id);
            $message = trans('users.message.delete');
        }
        $this->_logger->debug($message . '[' . $user->email . ']');
        return redirect()->route('users.index')->with(['message' => $message]);
    }

    public function changeProfile()
    {
        $this->_header = trans('users.header.profile');
        return $this->view('admin.users.profile');
    }

    public function storeProfile()
    {
        \Validator::extend('check_old_password', function ($attribute, $value, $parameters, $validator) {
            return \Hash::check($value, \Auth::user()->password);
        });

        $data = $this->getAllParams();
        $validator = \Validator::make($data, [
            'name' => 'required',
            'lang' => 'required',
            'old_password' => 'check_old_password',
            'password' => 'min:6|different:old_password',
            're_password' => 'same:password'
        ]);
        if ($validator->fails()) {
            return $this->submitError($validator->errors());
        }
        $values = array();
        $values['name'] = $data['name'];
        $values['lang'] = $data['lang'];
        if (!empty($data['password'])) {
            $values['password'] = \Hash::make($data['password']);
        }
        \App::setLocale($data['lang']);
        if (\App::environment() != 'testing') {
            setcookie('user_lang', $data['lang'], time() + 24 * 60 * 30, '/');
        }
        $this->_user->update($values, \Auth::user()->id);

        $this->_logger->debug('Change profile success');
        return redirect()->route('users.profile.ignore')->with(['message' => trans('users.message.profile')]);
    }


}
