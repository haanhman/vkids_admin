<?php

class RoleTest extends TestCase
{
    public function test_index_show_all_role()
    {

        $mockRole = Mockery::mock(\App\Repositories\RoleRepositoryEloquent::class);
        $mockRole->shouldReceive('orderBy')->once()->andReturnSelf();
        $mockRole->shouldReceive('all')->once()->andReturnUsing(function () {
            return new \Illuminate\Support\Collection();
        });
        $this->installInstance($mockRole);

        $this->get(route('role.index'))
            ->seeStatusCode(200);
    }

    public function test_create_visit()
    {
        $this->get(route('role.create'))
            ->seeStatusCode(200);
    }

    public function test_store_empty_data_and_has_already()
    {

        Validator::shouldReceive('make')->twice()->andReturnSelf();
        Validator::shouldReceive('fails')->twice()->andReturnValues([true, true]);
        Validator::shouldReceive('errors')->twice()->andReturnUsing(function () {
            return new \Illuminate\Support\MessageBag();
        });
        $this->post(route('role.store'))->seeStatusCode(301);
        $this->post(route('role.store'), ['name' => 'Administrator'])->seeStatusCode(301);
    }

    public function test_store_done()
    {
        $params = array(
            'name' => 'role - ' . time()
        );

        Validator::shouldReceive('make')->once()->andReturnSelf();
        Validator::shouldReceive('fails')->once()->andReturnValues([false]);

        $roleMock = Mockery::mock(\App\Repositories\RoleRepositoryEloquent::class);
        $roleMock->shouldReceive('create')->once()->andReturnUsing(function () {
            return new \App\Entities\Role();
        });
        $this->installInstance($roleMock);

        $this->post(route('role.store'), $params)->seeStatusCode(302);

    }


    public function test_edit_fail_when_role_id_not_found()
    {
        $roleMock = Mockery::mock(\App\Repositories\RoleRepositoryEloquent::class);
        $roleMock->shouldReceive('find')->once()->andThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->installInstance($roleMock);

        $this->assertTrue($this->seeExceptionThrown(
            \Illuminate\Database\Eloquent\ModelNotFoundException::class,
            function () {
                $this->get(route('role.edit', ['id' => 1]));
            }
        ));

    }

    public function test_edit_visit_success()
    {
        $roleMock = Mockery::mock(\App\Repositories\RoleRepositoryEloquent::class);
        $roleMock->shouldReceive('find')->once()->andReturnUsing(function () {
            return new \App\Entities\Role();
        });
        $this->installInstance($roleMock);

        $this->get(route('role.edit', ['id' => 1]))->seeStatusCode(200);
    }

    public function test_update_fail_validate_empty_data_and_already_role()
    {
        Validator::shouldReceive('make')->twice()->andReturnSelf();
        Validator::shouldReceive('fails')->twice()->andReturnValues([true, true]);
        Validator::shouldReceive('errors')->twice()->andReturnUsing(function () {
            return new \Illuminate\Support\MessageBag();
        });
        $this->put(route('role.update', ['id' => 1]))->seeStatusCode(301);
        $this->put(route('role.update', ['id' => 1]), ['name' => 'Administrator'])->seeStatusCode(301);
    }

    public function test_update_success()
    {
        Validator::shouldReceive('make')->once()->andReturnSelf();
        Validator::shouldReceive('fails')->once()->andReturnValues([false]);

        $mockRole = Mockery::mock(\App\Repositories\RoleRepositoryEloquent::class);
        $mockRole->shouldReceive('update')->once()->andReturnUsing(function () {
            return new \App\Entities\Role();
        });
        $this->installInstance($mockRole);
        $this->put(route('role.update', ['id' => 1]), ['name' => 'Administrator'])->seeStatusCode(302);
    }

    public function test_permission_visit_not_found()
    {
        $mockRole = Mockery::mock(\App\Repositories\RoleRepositoryEloquent::class);
        $mockRole->shouldReceive('find')->once()->andThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->installInstance($mockRole);

        $this->assertTrue(
            $this->seeExceptionThrown(
                \Illuminate\Database\Eloquent\ModelNotFoundException::class,
                function () {
                    $this->get(route('role.permission', ['id' => 1]));
                }
            )
        );
    }

    public function test_permission_visit_with_null_permission_data()
    {
        $mockRole = Mockery::mock(\App\Repositories\RoleRepositoryEloquent::class);
        $mockRole->shouldReceive('find')->once()->andReturn(new \App\Entities\Role());
        $this->installInstance($mockRole);

        $mockPermission = Mockery::mock(\App\Repositories\PermissionRepositoryEloquent::class);
        $mockPermission->shouldReceive('getPermission')->once()->andReturn(null);
        $this->installInstance($mockPermission);

        $this->get(route('role.permission', ['id' => 1]))->seeStatusCode(200);
    }

    public function test_permission_visit_success()
    {
        $mockRole = Mockery::mock(\App\Repositories\RoleRepositoryEloquent::class);
        $mockRole->shouldReceive('find')->once()->andReturn(new \App\Entities\Role());
        $this->installInstance($mockRole);

        $mockPermission = Mockery::mock(\App\Repositories\PermissionRepositoryEloquent::class);
        $mockPermission->shouldReceive('getPermission')->once()->andReturn(new \App\Entities\Permission());
        $this->installInstance($mockPermission);

        $this->get(route('role.permission', ['id' => 1]))->seeStatusCode(200);
    }

    public function test_set_permission_with_empty_permission_selected() {
        $mockPermission = Mockery::mock(\App\Repositories\PermissionRepositoryEloquent::class);
        $mockPermission->shouldReceive('updatePermission')->once()->andReturn();
        $this->installInstance($mockPermission);
        $this->post(route('role.set_permission', ['id' => 1]))->seeStatusCode(302);
    }

    public function test_set_permission_with_permission_selected() {
        $mockPermission = Mockery::mock(\App\Repositories\PermissionRepositoryEloquent::class);
        $mockPermission->shouldReceive('updatePermission')->once()->andReturn();
        $this->installInstance($mockPermission);
        $this->post(route('role.set_permission', ['id' => 1]), ['role' => ['name']])->seeStatusCode(302);
    }

}
