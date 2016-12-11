<?php

namespace Chess\Models\Figures;

use Chess\Models\Figures\Behaviors\AnyDirection;
use Chess\Models\Figures\Behaviors\Diagonally;
use Chess\Models\Figures\Behaviors\ForwardByStep;

/**
 * Class FiguresFabric
 * @package models\Figures
 */
class FiguresFabric implements FiguresFabricInterface {

    protected static $figuresBehaviorMap = [
        'queen' => AnyDirection::class,
        'pawn' => ForwardByStep::class,
        'bishop' => Diagonally::class,
    ];

    /**
     * @param $color
     * @param $alias
     * @return FigurePrototype
     */
    public static function getFigure($color, $alias): FigurePrototype
    {
        if (isset(self::$figuresBehaviorMap[$alias])) {
            return new FigurePrototype($color, $alias, new self::$figuresBehaviorMap[$alias]);
        }

        return null;
    }
}