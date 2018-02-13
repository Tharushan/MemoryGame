<?php

namespace Memory;

use PHPUnit\Framework\TestCase;

class GameTest extends TestCase{
    protected $map;
    protected $game;

    public function setUp() {
        $this->map = new Map(__DIR__.'/../../src/Public/images/game/');
        $this->map->generate();

        $this->game = new Game($this->map);
    }

    public function testIsWon() {
        $this->assertFalse($this->game->isWon());
    }

    public function testIsLost() {
        $this->assertFalse($this->game->isLost());
    }

    public function testTryCombination() {
        // test try combination ok
        $this->assertEquals(Game::MAX_ATTEMPTS, $this->game->getRemaningAttempts());
        $this->game->tryCombination(array(0, 0));
        $this->assertEquals(Game::MAX_ATTEMPTS, $this->game->getRemaningAttempts());

        // test try combination not ok
        $mapList = $this->map->getConfig();
        $failIndex = 0;
        $count = 0;
        while($this->game->getRemaningAttempts() > 0) {
            while ($mapList['map'][0]['src'] === $mapList['map'][$failIndex]['src']) {
                $failIndex++;
            }
            $this->game->tryCombination(array(0, $failIndex));
            $count++;
            $this->assertEquals(Game::MAX_ATTEMPTS - $count, $this->game->getRemaningAttempts());           
        }
        $this->assertEquals(0, $this->game->getRemaningAttempts());
        $this->assertTrue($this->game->isLost());
        
        // test tryCombination not ok - clamp remaningAttempts

        $this->game->tryCombination(array(0, $failIndex));
        $this->assertEquals(0, $this->game->getRemaningAttempts());
        $this->assertTrue($this->game->isLost());
    }
}

?>