<?php

namespace Chess\Models\Figures\Behaviors;

/**
 * Class ForwardByStep
 * @package models\Figures\Behaviors
 */
class ForwardByStep implements DirectionInterface
{
    /**
     * @var int
     */
    protected $firstMoveOffset = 1;

    /**
     * @param string $positionFrom
     * @param string $positionTo
     * @return bool
     */
    public function isAchievable(string $positionFrom, string $positionTo)
    {
        $from = str_split($positionFrom);
        $to = str_split($positionTo);

        return
            $from[0] == $to[0] &&
            ((int)$to[1] - (int)$from[1] - $this->firstMoveOffset) <= 1;

    }

    public function moveDone()
    {
        $this->firstMoveOffset = 0;
    }
}