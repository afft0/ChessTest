<?php
namespace {
    require_once __DIR__."/../vendor/autoload.php";
}

namespace Chess {

    use Chess\Listeners\BoardListener;
    use Chess\Models\Board;
    use Chess\Services\Storages\FileStorage;
    use Chess\Models\Figures\FiguresFabric;
    use Chess\Services\Storages\StorageInterface;

    /**
     * Class BoardTest
     */
    class BoardTest extends \PHPUnit\Framework\TestCase
    {
        const STORAGE_FILE_NAME = 'test.txt';

        /**
         * @var StorageInterface
         */
        protected $storage;

        /**
         * @covers Board::setFigure()
         * @covers Board::isFree()
         * @covers Board::getFigure()
         * @covers Board::moveFigure()
         */
        public function testMoving()
        {
            $board = (new Board())
                ->setStorage($this->storage);

            $initialState = $board->getState();

            $whitePawn = FiguresFabric::getFigure('white', 'pawn');

            $position1 = 'e1';
            $position2 = 'e3';
            $position3 = 'f3';

            $this->assertTrue($board->isFree($position1));

            $board->setFigure($position1, $whitePawn);

            $this->assertFalse($board->isFree($position1));
            $this->assertEquals($whitePawn, $board->getFigure($position1));

            $this->assertTrue($board->isFree($position2));
            $this->assertTrue($whitePawn->canMove($position1, $position2));

            $board->moveFigure($position1, $position2);

            $this->assertTrue($board->isFree($position1));
            $this->assertFalse($board->isFree($position2));
            $this->assertEquals($whitePawn, $board->getFigure($position2));

            $this->expectException(\Exception::class);
            $board->moveFigure($position2, $position3);

            $this->assertFalse($board->isFree($position2));
            $this->assertTrue($board->isFree($position3));
            $this->assertEquals($whitePawn, $board->getFigure($position2));

            $board->removeFigure($position2);

            $this->assertTrue($board->isFree($position1));
            $this->assertTrue($board->isFree($position2));
            $this->assertEquals($initialState, $board->getState());
        }

        /**
         * @covers Board::getState()
         * @covers Board::setState()
         * @covers Board::resetState()
         * @covers Board::save()
         * @covers Board::load()
         */
        public function testStates()
        {
            $board = (new Board())
                ->setStorage($this->storage);

            $initialState = $board->getState();

            $this->assertEmpty($initialState);

            $position1 = 'e1';
            $position2 = 'e3';
            $position3 = 'f3';
            $position4 = 'g3';
            $position5 = 'g4';

            $pawn1 = FiguresFabric::getFigure('white', 'pawn');
            $queen = FiguresFabric::getFigure('white', 'queen');
            $bishop = FiguresFabric::getFigure('white', 'bishop');
            $pawn2 = FiguresFabric::getFigure('white', 'pawn');

            $board->setFigure($position1, $pawn1);
            $board->setFigure($position2, $queen);
            $board->setFigure($position3, $bishop);
            $board->setFigure($position4, $pawn2);

            $this->assertNotEquals($initialState, $board->getState());

            $this->assertEquals($pawn1, $board->getFigure($position1));
            $this->assertEquals($queen, $board->getFigure($position2));
            $this->assertEquals($bishop, $board->getFigure($position3));
            $this->assertEquals($pawn2, $board->getFigure($position4));
            $this->assertNotEquals($initialState, $board->getState());

            $board->moveFigure($position4, $position5);

            $this->assertEquals($pawn2, $board->getFigure($position5));
            $this->assertTrue($board->isFree($position4));

            $state = $board->getState();
            $stamp = $board->save();

            $this->assertNotEmpty($state);
            $this->assertNotEmpty($stamp);

            $this->assertEmpty($board->resetState()->getState());

            $this->assertEquals($initialState, $board->getState());
            $this->assertEquals($state, $board->load($stamp)->getState());
        }

        public function testListeners()
        {
            $board = (new Board())
                ->setStorage($this->storage);
            $board
                ->attach(new BoardListener());


            $whitePawn = FiguresFabric::getFigure('white', 'pawn');
            $blackPawn = FiguresFabric::getFigure('black', 'pawn');
            $whiteQueen = FiguresFabric::getFigure('white', 'queen');

            $position1 = 'e1';
            $position12 = 'e2';
            $position2 = 'b3';
            $position21 = 'b4';
            $position3 = 'a1';
            $position31 = 'f6';

            $this->expectOutputString(
                "Pawn added\n" .
                "Pawn added\n" .
                "Figure added\n"
            );

            $board->setFigure($position1, $whitePawn);
            $board->moveFigure($position1, $position12);

            $board->setFigure($position2, $blackPawn);
            $board->moveFigure($position2, $position21);

            $board->setFigure($position3, $whiteQueen);
            $board->moveFigure($position3, $position31);
        }

        protected function setUp()
        {
            parent::setUp();

            if (file_exists(self::STORAGE_FILE_NAME)) {
                unlink(self::STORAGE_FILE_NAME);
            }

            $this->storage = new FileStorage(self::STORAGE_FILE_NAME);
        }
    }
}