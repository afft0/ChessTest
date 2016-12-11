<?php

namespace Chess\Models\Figures\Behaviors;

/**
 * Class Diagonally
 * @package models\Figures\Behaviors
 */
class Diagonally implements DirectionInterface
{
    /**
     * @param string $positionFrom
     * @param string $positionTo
     * @return bool
     */
    public function isAchievable(string $positionFrom, string $positionTo)
    {
        $from = str_split($positionFrom);
        $to = str_split($positionTo);

        return abs(ord($from[0]) - ord($to[0])) == abs(ord($from[1]) - ord($to[1]));
    }

    public function moveDone(){}
}