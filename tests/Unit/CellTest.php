<?php

namespace Tests\Unit;

use App\Cell;
use Tests\TestCase;
use App\Exceptions\InvalidStateTransitionException;
use App\Exceptions\InvalidFlagSymbolException;

class CellTest extends TestCase
{

    protected Cell $cell;

    public function setUp(): void
    {
        parent::setUp();
        $this->cell = factory(Cell::class)->make([
            'state' => 'uncovered'
        ]);
    }

    /** @test */
    public function it_can_be_created()
    {
        $this->assertInstanceOf(Cell::class, $this->cell);
    }

    /** @test */
    public function it_can_get_uncovered_attribute()
    {
        $this->assertEquals(true, $this->cell->uncovered);
    }

    /** @test */
    public function it_can_get_covered_attribute()
    {
        $this->assertEquals(false, $this->cell->covered);
    }

    public function it_can_uncover_valid_cell()
    {
        // Uncover method still needs refactor
    }

    /** @test */
    public function it_can_flag_valid_cell()
    {
        $validCell = factory(Cell::class)->make([
            'state' => Cell::COVERED,
            'flagged' => null,
        ]);

        $flaggedCell = $validCell->flag('flag');

        $this->assertEquals('flag', $flaggedCell->flagged);
    }

    /** @test */
    public function it_can_handle_invalid_state_transition_exception_on_invalid_cell()
    {
        $this->expectException(InvalidStateTransitionException::class);

        $invalidCell = factory(Cell::class)->make([
            'state' => Cell::UNCOVERED,
            'flagged' => null,
        ]);

        $invalidCell->flag('flag');
    }

    /** @test */
    public function it_can_handle_invalid_flag_symbol_exception_on_invalid_cell()
    {
        $this->expectException(InvalidFlagSymbolException::class);

        $invalidCell = factory(Cell::class)->make([
            'state' => Cell::COVERED,
            'flagged' => null,
        ]);

        $invalidCell->flag('error');
    }
}
