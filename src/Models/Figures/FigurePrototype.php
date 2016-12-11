<?php

namespace Chess\Models\Figures;

use \Chess\Models\Figures\Behaviors\DirectionInterface;

/**
 * Class FigurePrototype
 * @package models\Figures
 */
class FigurePrototype
{
    protected $type;
    protected $color;
    protected $directionBehavior;

    public function __construct($color, $type, DirectionInterface $directionBehavior)
    {
        $this->color = $color;
        $this->type = $type;
        $this->directionBehavior = $directionBehavior;
    }

    public function canMove($from, $to): bool
    {
        return $this->directionBehavior->isAchievable($from, $to);
    }

    public function getType()
    {
        return $this->type;
    }

    public function getColor()
    {
        return $this->color;
    }
}