<?php

namespace Memory;

class Game {


	const MAX_ATTEMPTS = 15;

	private $map;
	private $attempts;


	public function __construct(Map $map, $attempts=0) {
		$this->attempts = (int) $attempts;
		$this->map 		= $map;
	}

	/**
	 * [getContext description]
	 * @return [type] [description]
	 */
	public function getContext() {
		return array(
			"attempts" 	=> $this->attempts,
			"mapConfig" => $this->map->getConfig(),
		);
	}

	public function getMapDepend() {
		return $this->map;
	}


	public function getRemaningAttempts() {
		return max(self::MAX_ATTEMPTS - $this->attempts, 0);
	}

	/**
	 * [isWon description]
	 * @return boolean [description]
	 */
	public function isWon() {
		return $this->map->getnumberImages() * $this->map->getRepeatImages() === count($this->map->getFoundImages());
		
	}

	/**
	 * [isLost description]
	 * @return boolean [description]
	 */
	public function isLost() {
        return $this->getRemaningAttempts() === 0;
    }

    /**
     * [tryCombination description]
     * @param  Array  $turnedImages [description]
     * @return [type]                [description]
     */
	public function tryCombination(Array $turnedImages) {
		if($this->map->tryCombination($turnedImages) === false){
			$this->attempts++;
		}
	}
}