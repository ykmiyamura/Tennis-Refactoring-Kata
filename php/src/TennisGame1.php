<?php

namespace TennisGame;

class TennisGame1 implements TennisGame
{
    private Player $player1;
    private Player $player2;

    public function __construct(string $player1Name, string $player2Name)
    {
        $this->player1 = new Player($player1Name);
        $this->player2 = new Player($player2Name);
    }

    public function wonPoint($playerName): void
    {
        if ($this->player1->getName() === $playerName) {
            $this->player1->wonPoint();
        } elseif ($this->player2->getName() === $playerName) {
            $this->player2->wonPoint();
        } else {
            throw new \LogicException();
        }
    }

    public function getScore(): string
    {
        return Referee::callScore($this->player1, $this->player2);
    }
}

class Referee
{
    public static function callScore(Player $player1, Player $player2): string
    {
        if ($player1->getPoint()->equal($player2->getPoint())) {
            if ($player1->getPoint()->isUnderDeuce()) {
                return sprintf('%s-All', $player1->getPoint()->toString());
            }
            return 'Deuce';
        }

        if (self::isAfterDeuce($player1->getPoint(), $player2->getPoint())) {
            if (abs($player1->getPoint()->getValue() - $player2->getPoint()->getValue()) === 1) {
                $score = 'Advantage ';
            }  else {
                $score = 'Win for ';
            }
            if ($player1->getPoint()->getValue() > $player2->getPoint()->getValue()) {
                $score .= $player1->getName();
            } else {
                $score .= $player2->getName();
            }
            return $score;
        }

        return sprintf(
            '%s-%s',
            $player1->getPoint()->toString(),
            $player2->getPoint()->toString(),
        );
    }

    private static function isAfterDeuce(Point $point1, Point $point2): bool
    {
        return $point1->getValue() >= 4 || $point2->getValue() >= 4;
    }
}

class Point
{
    public function __construct(private int $value)
    {
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    public function increment(): void
    {
        $this->value++;
    }

    public function equal(self $that): bool
    {
        return $this->getValue() === $that->getValue();
    }

    public function toString(): string
    {
        $map = [
            0 => 'Love',
            1 => 'Fifteen',
            2 => 'Thirty',
            3 => 'Forty',
        ];
        return $map[$this->value];
    }

    public function isUnderDeuce(): bool
    {
        return $this->value < 3;
    }
}

class Player
{
    private Point $point;

    public function __construct(private string $name)
    {
        $this->point = new Point(0);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPoint(): Point
    {
        return $this->point;
    }

    public function wonPoint(): void
    {
        $this->point->increment();
    }

    public function equal(self $that): bool
    {
        return $this->getName() === $that->getName();
    }
}