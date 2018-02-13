<?php
	
require __DIR__.'/../autoload.php';

use Memory\Game;
use Memory\GameContext;
use Memory\Map;
use Memory\Storage\SessionStorage;


$context = new GameContext(new SessionStorage('memory'));


if(isset($_GET['restart'])){
	$context->reset();
}



if(!$game = $context->loadGame()){
	
	// New Map
	$map = new Map(__DIR__.'/images/game/');
	$map->generate();

	// New Game
	$game = $context->newGame($map);

} else {

	// Get restored map
	$map = $game->getMapDepend();
}



if(isset($_GET['key'])) {
	$map->turnImage($_GET['key']);

	if(count($map->getTurnedImages()) === $map->getRepeatImages()){
		$game->tryCombination($map->getTurnedImages());
	}

	if(count($map->getTurnedImages())>$map->getRepeatImages()){
		$map->cleanTurnImage();
		$map->turnImage($_GET['key']);
	}
}


$context->saveGame($game);

?>
<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<div class="wrapper">
		<?php 
			if($game->isWon()) {
				
				echo "<p>You Win !</p><p><a href='index.php?restart'>Restart</a></p>";

			} else if($game->isLost()) {

				echo "<p>You Lose !</p><p><a href='index.php?restart'>Restart</a></p>";

			} else {

				echo "<p>" . $game->getRemaningAttempts() . "</p>";

				$mapConfig = $map->getConfig();
				foreach ($mapConfig['map'] as $key => $value) {

					if((array_key_exists('turned', $value) && $value['turned']) || (array_key_exists('found', $value) && $value['found'])) {
						echo "<img src='images/game/{$value['src']}' />";
						continue;
					}

					echo "<a href='index.php?key=$key'><img src='images/back.svg' /></a>";
				} 
			}
		?>
		</div>
	</body>
</html>