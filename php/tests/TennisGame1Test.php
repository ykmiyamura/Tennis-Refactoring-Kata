<?php

declare(strict_types=1);

namespace Tests;

use TennisGame\TennisGame1;

class TennisGame1Test extends \PHPUnit\Framework\TestCase
{
    public function provideSetPlayerName(): array
    {
        return [
            ['player1', 'player2'],
            ['Alice', 'Bob'],
        ];
    }

    /**
     * @dataProvider provideSetPlayerName
     */
    public function testSetPlayerName(string $p1, string $p2): void
    {
        $game = new TennisGame1($p1, $p2);
        $game->wonPoint($p1);
        $this->assertSame('Fifteen-Love', $game->getScore());

        $game = new TennisGame1($p1, $p2);
        $game->wonPoint($p2);
        $this->assertSame('Love-Fifteen', $game->getScore());
    }

    public function testWonPoint(): void
    {
        $p1 = 'Alice';
        $p2 = 'Bob';
        $game = new TennisGame1($p1, $p2);
        $game->wonPoint($p1);
        $this->assertSame('Fifteen-Love', $game->getScore());

        $game = new TennisGame1($p1, $p2);
        $game->wonPoint($p2);
        $this->assertSame('Love-Fifteen', $game->getScore());

        $this->expectException(LogicException::class);
        $game = new TennisGame1($p1, $p2);
        $game->wonPoint('Chris');
    }


    public function testGetScoreIfPlayer1Love(): void
    {
        $p1 = 'player1';
        $p2 = 'p2';
        $game = new TennisGame1($p1, $p2);
        $this->assertSame('Love-All', $game->getScore());
        $game->wonPoint($p2);
        $this->assertSame('Love-Fifteen', $game->getScore());
        $game->wonPoint($p2);
        $this->assertSame('Love-Thirty', $game->getScore());
        $game->wonPoint($p2);
        $this->assertSame('Love-Forty', $game->getScore());
        $game->wonPoint($p2);
        $this->assertSame('Win for p2', $game->getScore());
    }

    public function testGetScoreIfPlayer1Fifteen(): void
    {
        $p1 = 'player1';
        $p2 = 'p2';
        $game = new TennisGame1($p1, $p2);
        $game->wonPoint($p1); // fifteen
        $this->assertSame('Fifteen-Love', $game->getScore());
        $game->wonPoint($p2);
        $this->assertSame('Fifteen-All', $game->getScore());
        $game->wonPoint($p2);
        $this->assertSame('Fifteen-Thirty', $game->getScore());
        $game->wonPoint($p2);
        $this->assertSame('Fifteen-Forty', $game->getScore());
        $game->wonPoint($p2);
        $this->assertSame('Win for p2', $game->getScore());
    }

    public function testGetScoreIfPlayer1Thirty(): void
    {
        $p1 = 'player1';
        $p2 = 'p2';
        $game = new TennisGame1($p1, $p2);
        $game->wonPoint($p1); // fifteen
        $game->wonPoint($p1); // thirty
        $this->assertSame('Thirty-Love', $game->getScore());
        $game->wonPoint($p2);
        $this->assertSame('Thirty-Fifteen', $game->getScore());
        $game->wonPoint($p2);
        $this->assertSame('Thirty-All', $game->getScore());
        $game->wonPoint($p2);
        $this->assertSame('Thirty-Forty', $game->getScore());
        $game->wonPoint($p2);
        $this->assertSame('Win for p2', $game->getScore());
    }

    public function testGetScoreIfPlayer1Forty(): void
    {
        $p1 = 'player1';
        $p2 = 'p2';
        $game = new TennisGame1($p1, $p2);
        $game->wonPoint($p1); // fifteen
        $game->wonPoint($p1); // thirty
        $game->wonPoint($p1); // forty
        $this->assertSame('Forty-Love', $game->getScore());
        $game->wonPoint($p2);
        $this->assertSame('Forty-Fifteen', $game->getScore());
        $game->wonPoint($p2);
        $this->assertSame('Forty-Thirty', $game->getScore());
        $game->wonPoint($p2);
        $this->assertSame('Deuce', $game->getScore());
        $game->wonPoint($p2);
        $this->assertSame('Advantage p2', $game->getScore());
        $game->wonPoint($p2);
        $this->assertSame('Win for p2', $game->getScore());
    }

    public function testGetScoreIfPlayer1Win(): void
    {
        $p1 = 'player1';
        $p2 = 'p2';
        $game = new TennisGame1($p1, $p2);
        $game->wonPoint($p1); // fifteen
        $game->wonPoint($p1); // thirty
        $game->wonPoint($p1); // forty
        $game->wonPoint($p1); // win
        $this->assertSame('Win for player1', $game->getScore());
    }

    public function testGetScoreFromDeuce(): void
    {
        $p1 = 'Alice';
        $p2 = 'p2';
        $game = new TennisGame1($p1, $p2);
        $game->wonPoint($p1);
        $game->wonPoint($p1);
        $game->wonPoint($p1);
        $game->wonPoint($p1);
        $game->wonPoint($p2);
        $game->wonPoint($p2);
        $game->wonPoint($p2);
        $game->wonPoint($p2);
        $this->assertSame('Deuce', $game->getScore());

        $game->wonPoint($p1);
        $this->assertSame('Advantage ' . $p1, $game->getScore());
        $game->wonPoint($p2);
        $this->assertSame('Deuce', $game->getScore());
        $game->wonPoint($p2);
        $this->assertSame('Advantage ' . $p2, $game->getScore());
        $game->wonPoint($p1);
        $this->assertSame('Deuce', $game->getScore());
        $game->wonPoint($p1);
        $this->assertSame('Advantage ' . $p1, $game->getScore());
        $game->wonPoint($p1);
        $this->assertSame('Win for ' . $p1, $game->getScore());
    }
}