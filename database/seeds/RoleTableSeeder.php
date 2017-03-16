<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Repositories\RoleRepositoryEloquent;
class RoleTableSeeder extends Seeder
{
    private $_role;
    function __construct(RoleRepositoryEloquent $role)
    {
        $this->_role = $role;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = array('Admin', 'Developer', 'Tester', 'Creator');
        foreach ($roles as $role) {
            $this->_role->create(['name' => $role]);
        }
    }
}
