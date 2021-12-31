<?php

namespace App\Http\Controllers\Api;

use App\Game;
use App\Cell;
use App\Http\Requests\CreateGameRequest;
use App\Exceptions\InvalidStateTransitionException;
use App\Exceptions\InvalidFlagSymbolException;
use App\Http\Controllers\Controller;

class GameController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\CreateGameRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateGameRequest $request)
    {
        $game = new Game($request->all());

        $request->user()->games()->save($game);

        $game->generateBoard();

        return response()->json($game->load('cells'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        return response()->json($game->load('cells'));
    }

    /**
     * Uncover a cell of the game.
     *
     * @param  App\Game $game
     * @param  App\Cell $cell
     * @return \Illuminate\Http\Response
     */
    public function uncover(Game $game, Cell $cell)
    {
        try {
            $game->uncoverCell($cell);

            return response()->json($game->load('cells'));
        } catch (InvalidStateTransitionException $e) {
            return response()->json([
                'message' => 'Uncovered cells cannot be uncovered'
            ], 403);
        }
    }

    /**
     * Flag a cell of the game.
     *
     * @param  App\Game $game
     * @param  App\Cell $cell
     * @return \Illuminate\Http\Response
     */
    public function flag(Game $game, Cell $cell)
    {
        try {
            $cell->flag(request()->get('symbol'));

            return response()->json($game->load('cells'));
        } catch (InvalidStateTransitionException $e) {
            return response()->json([
                'message' => 'Uncovered cells cannot be flagged'
            ], 403);
        } catch (InvalidFlagSymbolException $e) {
            return response()->json([
                'message' => 'Invalid symbol when trying to flag cell'
            ], 403);
        }
    }
}
