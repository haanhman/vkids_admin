<?php

class UserTest extends TestCase
{

    public function test_index_empty_list_user()
    {
        $mockUser = Mockery::mock(\App\Repositories\UserRepositoryEloquent::class);
        $mockUser->shouldReceive('with')->once()->andReturnSelf()
            ->shouldReceive('withTrashed')->once()->andReturnSelf()
            ->shouldReceive('orderBy')->once()->andReturnSelf()
            ->shouldReceive('all')->once()->andReturn(null);
        $this->installInstance($mockUser);
        $this->get(route('users.index'))->seeStatusCode(200);
    }

    public function test_index_show_all_user()
    {
        $mockUser = Mockery::mock(\App\Repositories\UserRepositoryEloquent::class);
        $mockUser->shouldReceive('with')->once()->andReturnSelf();
        $mockUser->shouldReceive('withTrashed')->once()->andReturnSelf();
        $mockUser->shouldReceive('orderBy')->once()->andReturnSelf();
        $mockUser->shouldReceive('all')->once()->andReturn(new \Illuminate\Database\Eloquent\Collection());
        $this->installInstance($mockUser);

        $mockRole = Mockery::mock(\App\Repositories\RoleRepositoryEloquent::class);
        $mockRole->shouldReceive('all')->once()->andReturn(new \Illuminate\Database\Eloquent\Collection());
        $this->installInstance($mockRole);
        $this->get(route('users.index'))->seeStatusCode(200);
    }


    public function test_create_visit()
    {
        $mockRole = Mockery::mock(\App\Repositories\RoleRepositoryEloquent::class);
        $mockRole->shouldReceive('all')->once()->andReturn(new \Illuminate\Database\Eloquent\Collection());
        $this->installInstance($mockRole);
        $this->get(route('users.create'))->seeStatusCode(200);
    }


    public function data_store_params()
    {
        return [
            [[
                'name' => '',
                'email' => '',
                'password' => '',
                're_password' => '',
                'lang' => 'en'
            ]],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhm',
                'password' => '123',
                're_password' => '1444'],
                'lang' => 'en'
            ],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhmantk@gmail.com',
                'password' => '123123',
                're_password' => '432123'],
                'lang' => 'en'
            ],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhm',
                'password' => '123123',
                're_password' => '123123'],
                'lang' => 'en'
            ],
        ];
    }

    /**
     * @dataProvider data_store_params
     * @param $params
     */
    public function test_store_validate($params)
    {
        Validator::shouldReceive('make')->once()->andReturnSelf();
        Validator::shouldReceive('fails')->once()->andReturnValues([true]);
        Validator::shouldReceive('errors')->once()->andReturn(new \Illuminate\Support\MessageBag());
        $this->post(route('users.store'), $params)->seeStatusCode(301);
    }


    public function data_store_success_params()
    {
        return [
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhmantk@gmail.com',
                'password' => '123123',
                're_password' => '123123',
                'role' => [1, 2],
                'lang' => 'en'
            ]],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhmantk@gmail.com',
                'password' => '123123',
                're_password' => '123123',
                'lang' => 'en'
            ]],
        ];
    }

    /**
     * @dataProvider data_store_success_params
     * @param $params
     */
    public function test_store_success($params)
    {
        Validator::shouldReceive('make')->once()->andReturnSelf();
        Validator::shouldReceive('fails')->once()->andReturnValues([false]);

        $mockUser = Mockery::mock(\App\Repositories\UserRepositoryEloquent::class);
        $mockUser->shouldReceive('create')->once()->andReturn(new \App\Entities\User());
        $this->installInstance($mockUser);

        if (!empty($params['role'])) {
            $mockUserRole = Mockery::mock(\App\Repositories\UserRoleRepositoryEloquent::class);
            $mockUserRole->shouldReceive('assignUserRole')->once()->andReturn();
            $this->installInstance($mockUserRole);
        }

        $this->post(route('users.store'), $params)->seeStatusCode(302);
    }

    public function test_edit_id_not_found()
    {
        $mockRole = Mockery::mock(\App\Repositories\RoleRepositoryEloquent::class);
        $mockRole->shouldReceive('all')->once()->andReturn(new \Illuminate\Database\Eloquent\Collection());
        $this->installInstance($mockRole);

        $mockUser = Mockery::mock(\App\Repositories\UserRepositoryEloquent::class);
        $mockUser->shouldReceive('find')->once()->andThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->installInstance($mockUser);

        $this->assertTrue(
            $this->seeExceptionThrown(
                \Illuminate\Database\Eloquent\ModelNotFoundException::class,
                function () {
                    $this->get(route('users.edit', ['id' => 1]));
                }
            )
        );

    }

    public function test_edit_success()
    {
        $mockRole = Mockery::mock(\App\Repositories\RoleRepositoryEloquent::class);
        $mockRole->shouldReceive('all')->once()->andReturn(new \Illuminate\Database\Eloquent\Collection());
        $this->installInstance($mockRole);

        $mockUser = Mockery::mock(\App\Repositories\UserRepositoryEloquent::class);
        $mockUser->shouldReceive('find')->once()->andReturn(new \App\Entities\User());
        $this->installInstance($mockUser);

        $this->get(route('users.edit', ['id' => 1]))->seeStatusCode(200);

    }


    public function data_update_params()
    {
        return [
            [[
                'name' => '',
                'email' => '',
                'password' => '',
                're_password' => ''],
                'lang' => 'en'
            ],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhm',
                'password' => '123',
                're_password' => '1444'],
                'lang' => 'en'
            ],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhmantk@gmail.com',
                'password' => '123123',
                're_password' => '432123'],
                'lang' => 'en'
            ],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhm',
                'password' => '123123',
                're_password' => '123123'],
                'lang' => 'en'
            ],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhmantk@gmail.com',
                'password' => '123123',
                're_password' => '123123',
                'lang' => 'en'
            ]]
        ];
    }

    /**
     * @dataProvider data_update_params
     * @param $params
     */
    public function test_update_validate($params)
    {
        Validator::shouldReceive('make')->once()->andReturnSelf();
        Validator::shouldReceive('fails')->once()->andReturnValues([true]);
        Validator::shouldReceive('errors')->once()->andReturn(new \Illuminate\Support\MessageBag());
        $this->post(route('users.store'), $params)->seeStatusCode(301);
    }

    public function data_update_success_params()
    {
        return [
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhmantk@gmail.com',
                'password' => '123123',
                're_password' => '123123',
                'role' => [1, 2],
                'lang' => 'en'
            ]],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhmantk@gmail.com',
                'password' => '123123',
                're_password' => '123123',
                'lang' => 'en'
            ]],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhmantk@gmail.com',
                'lang' => 'en'
            ]]
        ];
    }


    /**
     * @dataProvider data_update_success_params
     * @param $params
     */
    public function test_update_success($params)
    {
        Validator::shouldReceive('make')->once()->andReturnSelf();
        Validator::shouldReceive('fails')->once()->andReturnValues([false]);

        $mockUser = Mockery::mock(\App\Repositories\UserRepositoryEloquent::class);
        $mockUser->shouldReceive('update')->once()->andReturn(new \App\Entities\User());
        $this->installInstance($mockUser);

        if (!empty($params['role'])) {
            $mockUserRole = Mockery::mock(\App\Repositories\UserRoleRepositoryEloquent::class);
            $mockUserRole->shouldReceive('assignUserRole')->once()->andReturn();
            $this->installInstance($mockUserRole);
        }

        $this->put(route('users.update', ['id' => 1]), $params)->seeStatusCode(302);
    }

    public function test_destroy_block_user()
    {
        $mockUser = Mockery::mock(\App\Repositories\UserRepositoryEloquent::class);
        $mockUser->shouldReceive('withTrashed')->once()->andReturnSelf();
        $mockUser->shouldReceive('find')->once()->andReturn(new \App\Entities\User());
        $mockUser->shouldReceive('delete')->once()->andReturn();
        $this->installInstance($mockUser);
        $this->delete(route('users.destroy', ['id' => 1]))->seeStatusCode(302);
    }

    public function test_destroy_restore_user()
    {
        $mockUser = Mockery::mock(\App\Repositories\UserRepositoryEloquent::class);
        $mockUser->shouldReceive('withTrashed')->once()->andReturnSelf();
        $user = factory(\App\Entities\User::class)->times(1)->make();
        $user->deleted_at = \Carbon\Carbon::now() . '';
        $mockUser->shouldReceive('find')->once()->andReturn($user);
        $mockUser->shouldReceive('restore')->once()->andReturn();
        $this->installInstance($mockUser);
        $this->delete(route('users.destroy', ['id' => 1]))->seeStatusCode(302);
    }

    public function test_visit_change_profile_file_success()
    {
        $this->get(route('users.profile.ignore'))->seeStatusCode(200);
    }

    public function change_user_profile_data_fail()
    {
        return [
            [[
                'name' => '',
                'lang' => '',
                'old_password' => '',
                'password' => '',
                're_password' => ''
            ]],
            [[
                'name' => 'Ha Anh Man',
                'lang' => 'en',
                'old_password' => '123123',
                'password' => '123123',
                're_password' => '123123'
            ]],
            [[
                'name' => 'Ha Anh Man',
                'lang' => 'en',
                'old_password' => '123123',
                'password' => '123123',
                're_password' => '123456'
            ]]
        ];
    }

    /**
     * @dataProvider change_user_profile_data_fail
     */
    public function test_user_change_profile_validate($params)
    {
        $this->withAuth();

        Validator::shouldReceive('extend')->once()->andReturnSelf();
        Validator::shouldReceive('make')->once()->andReturnSelf();
        Validator::shouldReceive('fails')->once()->andReturnValues([true]);
        Validator::shouldReceive('errors')->once()->andReturn(new \Illuminate\Support\MessageBag());
        $this->put(route('users.store_profile.ignore'), $params)->seeStatusCode(301);
    }

    public function change_user_profile_data_success()
    {
        return [
            [[
                'name' => 'Ha Anh Man',
                'lang' => 'en'
            ]],
            [[
                'name' => 'Ha Anh Man',
                'lang' => 'en',
                'old_password' => '123123',
                'password' => 'anhmantk',
                're_password' => 'anhmantk'
            ]]
        ];
    }

    /**
     * @dataProvider change_user_profile_data_success
     */
    public function test_user_change_profile_success($params)
    {
        $this->withAuth();

        Validator::shouldReceive('extend')->once()->andReturnSelf();
        Validator::shouldReceive('make')->once()->andReturnSelf();
        Validator::shouldReceive('fails')->once()->andReturnValues([false]);

        $mockUser = Mockery::mock(\App\Repositories\UserRepositoryEloquent::class);
        $mockUser->shouldReceive('update')->once()->andReturn(new \App\Entities\User());
        $this->installInstance($mockUser);

        $this->put(route('users.store_profile.ignore'), $params)->seeStatusCode(302);
    }

}
