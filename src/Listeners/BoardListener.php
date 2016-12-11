<?php

namespace Chess\Listeners;

/**
 * Class BoardListener
 */
class BoardListener implements \SplObserver
{
    /**
     * @param \SplSubject $subject
     */
    function update(\SplSubject $subject)
    {
        /**
         * @var $subject \Chess\Models\Board
         */
        if ($subject->getLastAction() == 'set') {
            if ($subject->getLastFigure()->getType() == 'pawn') {
                $this->sayPawnAdded();
            } else {
                $this->sayFigureAdded();
            }
        }
    }

    /**
     * @param \SplSubject $observable
     */
    function sayFigureAdded()
    {
        echo "Figure added\n";
    }

    /**
     * @param \SplSubject $observable
     */
    function sayPawnAdded()
    {
        echo "Pawn added\n";
    }
}