<?php

namespace Chess\Models\Figures\Behaviors;

/**
 * Interface DirectionInterface
 * @package models\Figures\Behaviors
 */
interface DirectionInterface
{
    public function isAchievable(string $positionFrom, string $positionTo);
    public function moveDone();
}