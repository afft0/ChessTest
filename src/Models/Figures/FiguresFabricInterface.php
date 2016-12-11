<?php

namespace Chess\Models\Figures;

/**
 * Interface FiguresFabricInterface
 * @package models\Figures
 */
interface FiguresFabricInterface
{
    public static function getFigure($color, $alias): FigurePrototype;
}