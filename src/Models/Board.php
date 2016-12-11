<?php

namespace Chess\Models;

use Chess\Models\Figures\FigurePrototype;
use Chess\Services\Persistable;

/**
 * Class Board
 * @package models
 */
class Board implements \SplSubject
{
    use Persistable;

    /**
     * @var \SplObjectStorage
     */
    private $listenersStorage;

    /**
     * @var array
     */
    protected $positions = [];

    /**
     * @var FigurePrototype
     */
    protected $lastFigure;

    /**
     * @var string
     */
    protected $lastAction;

    /**
     * Board constructor.
     */
    public function __construct()
    {
        $this->listenersStorage = new \SplObjectStorage();
    }

    /**
     * @param string $position
     * @param FigurePrototype $figure
     * @return $this
     * @throws \Exception
     */
    public function setFigure(string $position, FigurePrototype $figure, $notify = true)
    {
        if (!$this->isFree($position)) {
            throw new \Exception("Position is occupied");
        }

        $this->lastAction = 'set';
        $this->lastFigure = $figure;

        $this->positions[$position] = $figure;

        if ($notify) {
            $this->notify();
        }

        return $this;
    }

    /**
     * @param string $position
     * @return FigurePrototype
     * @throws \Exception
     */
    public function getFigure(string $position, $notify = true): FigurePrototype
    {
        if ($this->isFree($position)) {
            throw new \Exception("Position is free");
        }

        $this->lastAction = 'get';
        $this->lastFigure = $this->positions[$position];

        if ($notify) {
            $this->notify();
        }

        return $this->positions[$position];
    }

    /**
     * @param string $position
     * @return $this
     * @throws \Exception
     */
    public function removeFigure(string $position, $notify = true)
    {
        if ($this->isFree($position)) {
            throw new \Exception("Position is free already");
        }

        $this->lastAction = 'remove';
        $this->lastFigure = $this->positions[$position];

        unset($this->positions[$position]);

        if ($notify) {
            $this->notify();
        }

        return $this;
    }

    /**
     * @param string $positionFrom
     * @param string $positionTo
     * @return $this
     * @throws \Exception
     */
    public function moveFigure(string $positionFrom, string $positionTo)
    {
        if ($this->isFree($positionFrom)) {
            throw new \Exception("Position from is occupied");
        }

        if (!$this->isFree($positionTo)) {
            throw new \Exception("Position to is occupied");
        }

        $figure = $this->getFigure($positionFrom);
        if (!$figure->canMove($positionFrom, $positionTo)) {
            throw new \Exception(
                "Figure with type {$figure->getType()} can't move from $positionFrom to $positionTo"
            );
        }

        $this->setFigure($positionTo, $this->getFigure($positionFrom), false);
        $this->removeFigure($positionFrom, false);

        return $this;
    }

    /**
     * @param string $position
     * @return bool
     */
    public function isFree(string $position): bool
    {
        return empty($this->positions[$position]);
    }

    /**
     * @return array
     */
    public function getState(): array
    {
        return $this->positions;
    }

    /**
     * @param array $state
     * @return $this
     */
    public function setState(array $state)
    {
        $this->positions = $state;

        return $this;
    }

    /**
     * @return $this
     */
    public function resetState()
    {
        $this->positions = [];

        return $this;
    }

    /**
     * @param \SplObserver $observer
     */
    public function attach(\SplObserver $observer)
    {
        $this->listenersStorage->attach($observer);
    }

    /**
     * @param \SplObserver $observer
     */
    public function detach(\SplObserver $observer)
    {
        $this->listenersStorage->detach($observer);
    }

    public function notify()
    {
        foreach($this->listenersStorage as $obj) {
            $obj->update($this);
        }
    }

    /**
     * @return FigurePrototype
     */
    public function getLastFigure(): FigurePrototype
    {
        return $this->lastFigure;
    }

    /**
     * @return string
     */
    public function getLastAction(): string
    {
        return $this->lastAction;
    }
}