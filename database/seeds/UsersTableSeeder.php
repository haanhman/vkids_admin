<?php

use Illuminate\Database\Seeder;
use App\Repositories\UserRepositoryEloquent;

class UsersTableSeeder extends Seeder
{
    /**
     * @var UserRepositoryEloquent
     */
    private $_user;

    function __construct(UserRepositoryEloquent $user)
    {
        $this->_user = $user;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->_user->create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123123'),
            'system_admin' => 1
        ]);
    }
}
