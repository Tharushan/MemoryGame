<?php

namespace Memory;

use Memory\Storage\StorageInterface;


class GameContext {

	private $storage;

	public function __construct(StorageInterface $storage) {
		$this->storage = $storage;
	}

	/**
	 * newGame : Lancement d'une nouvelle partie
	 * 
	 * @param  Map $map : Object Map
	 * @return Game
	 */
	public function newGame(Map $map) {
		return new Game($map); 
	}


	/**
	 * saveGame : Enregistrement du l'état du jeu a un instant T 
	 * 
	 * @param  Game $game 
	 * @return void
	 */
	public function saveGame(Game $game) {
		$this->storage->write("memory", $game->getContext());
	}

	/**
	 * loadGame : 
	 * 
	 * @return [type] [description]
	 */
	public function loadGame() {

		$context = $this->storage->read("memory");
		
		if(empty($context)){
			return false;
		}

		$map = new Map();
		$map->restore($context['mapConfig']);

		return new Game($map, $context['attempts']);
	}
	
	/**
	 * reset : Supprime le status stocké
	 * 
	 * @return void
	 */
	public function reset() {
		$this->storage->write('memory', array());
	}
}