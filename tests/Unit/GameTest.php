<?php

namespace Tests\Unit;

use App\Game;
use App\Cell;
use Tests\TestCase;
use App\Exceptions\InvalidCellException;
use App\Exceptions\InvalidActionException;

class GameTest extends TestCase
{

    protected Game $cell;

    public function setUp(): void
    {
        parent::setUp();
        $this->game = factory(Game::class)->make();
    }

    /** @test */
    public function it_can_be_created()
    {
        $this->assertInstanceOf(Game::class, $this->game);
    }

    /** @test */
    public function it_can_get_ended_attribute()
    {
        $this->assertEquals(false, $this->game->ended);
    }

    /** @test */
    public function it_can_get_duration_attribute()
    {
        $endedGame = factory(Game::class)->make([
            'created_at' => '12/12/2021 05:00:00',
            'ended_at' => '12/12/2021 06:30:00',
        ]);

        $this->assertEquals(90, $endedGame->duration);
    }

    /** @test */
    public function it_can_uncover_cell_without_mine()
    {
        $game = factory(Game::class)->create();
        $cell = factory(Cell::class)->make([
            'game_id' => $game->id,
            'mine' => false,
            'state' => 'covered'
        ]);

        $game->uncoverCell($cell);

        $this->assertNull($game->ended_at);
    }

    /** @test */
    public function it_can_uncover_cell_with_mine()
    {
        $game = factory(Game::class)->create();
        $cell = factory(Cell::class)->make([
            'game_id' => $game->id,
            'mine' => true,
            'state' => 'covered'
        ]);

        $game->uncoverCell($cell);

        $this->assertNotNull($game->ended_at);
    }
}
