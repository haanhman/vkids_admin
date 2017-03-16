<?php

class PlayerTest extends TestCase
{
    public function test_index()
    {
        $mockPlayer = Mockery::mock(\App\Repositories\Api\PlayerRepositoryEloquent::class);
        $mockPlayer->shouldReceive('with')->once()->andReturnSelf();
        $mockPlayer->shouldReceive('withTrashed')->once()->andReturnSelf();
        $mockPlayer->shouldReceive('orderBy')->once()->andReturnSelf();
        $mockPlayer->shouldReceive('pushCriteria')->once()->andReturnSelf();
        $mockPlayer->shouldReceive('paginate')->once()->andReturn(new \Illuminate\Pagination\LengthAwarePaginator([], 100, 20));
        $mockPlayer->shouldReceive('getSocialType')->once()->andReturnValues([[1 => 'fb', 2 => 'zalo', 3 => 'google']]);
        $this->installInstance($mockPlayer);
        $this->get(route('player.index'))->seeStatusCode(200);
    }

    public function test_block_player()
    {
        $mockUser = Mockery::mock(\App\Repositories\Api\PlayerRepositoryEloquent::class);
        $mockUser->shouldReceive('withTrashed')->once()->andReturnSelf();
        $mockUser->shouldReceive('find')->once()->andReturn(new \App\Entities\User());
        $mockUser->shouldReceive('delete')->once()->andReturn();
        $this->installInstance($mockUser);
        $this->delete(route('player.lock', ['id' => 1]))->seeStatusCode(302);
    }

    public function test_destroy_restore_user()
    {
        $mockUser = Mockery::mock(\App\Repositories\Api\PlayerRepositoryEloquent::class);
        $mockUser->shouldReceive('withTrashed')->once()->andReturnSelf();
        $player = new \App\Entities\Api\Player();
        $player->id = 1;
        $player->deleted_at = \Carbon\Carbon::now();
        $mockUser->shouldReceive('find')->once()->andReturn($player);
        $mockUser->shouldReceive('restore')->once()->andReturn();
        $this->installInstance($mockUser);
        $this->delete(route('player.lock', ['id' => 1]))->seeStatusCode(302);
    }
}