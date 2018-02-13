<?php

namespace Memory;


class Map {


	private $config;


	/**
	 * getConfig
	 *
	 * @return Array
	 */
	public function getConfig() {
		return $this->config;
	}

	/**
	 * setConfig
	 *
	 * @param array $configuration : Configuration de map
	 * @return array
	 */
	public function setConfig(Array $configuration) {

		$model = array(
			'directoryImages' => null,
			'numberImages'	  => null,
			'repeatImages'	  => null
		);

		if(count(array_diff_key($model, $configuration)) !== 0 ){
			throw new \InvalidArgumentException('The configuration is not Valide.');
		}
		$this->config = $configuration;
	}



	/**
	 * getnumberImages
	 *
	 * @return integer : Nombre d'images diffèrentes dans la map
	 */
	public function getnumberImages() {
		return $this->config['numberImages'];
	}

	/**
	 * setnumberImages
	 *
	 * @param integer $value : Nombre d'images diffèrentes dans la map
	 * @return void
	 */
	public function setnumberImages($value) {

		if(preg_match('/^[2-9]$/', $value) === 0) {
       		throw new \InvalidArgumentException(sprintf('The Value "%s" is not a valid not matching [2-9].', $value));
       	}
		$this->config['numberImages'] = (int) $value;
	}



	/**
	 * getRepeatImages
	 *
	 * @return integer : Nombre de répétion d'une même image dans la map
	 */
	public function getRepeatImages() {
		return $this->config['repeatImages'];
	}

	/**
	 * setRepeatImages
	 *
	 * @param integer $value : Nombre de répétion d'une même image dans la map
	 * @return void
	 */
	public function setRepeatImages($value) {

		if(preg_match('/^[2-9]$/', $value) === 0) {
       		throw new \InvalidArgumentException(sprintf('The Value "%s" is not a valid not matching [2-9].', $value));
       	}
		$this->config['repeatImages'] = (int) $value;
	}



	/**
	 * getDirectoryImages
	 *
	 * @return string : chemin du ossier contenant les images
	 */
	public function getDirectoryImages() {
		return $this->config['directoryImages'];
	}

	/**
	 * setDirectoryImages
	 *
	 * @param string $value : chemin du ossier contenant les images
	 * @return void
	 */
	public function setDirectoryImages($value) {

		if(file_exists($value) === false) {
       		throw new \InvalidArgumentException(sprintf('The Path "%s" is not exists.', $value));
       	}
		$this->config['directoryImages'] = (string) $value;
	}









	/**
	 * __construct : Initialise l'object Map
	 *
	 * @param string  $directoryImages Dossier contenant les images
	 * @return  void
	 */
	public function __construct($directoryImages='.') {

		$this->setDirectoryImages($directoryImages);
		$this->setnumberImages(6);
		$this->setRepeatImages(2);
	}

	/**
	 * generate : Génére une nouvelle map
	 *
	 * @return array
	 */
	public function generate() {

		$filenames 	= array();
    	$iterator 	= new \DirectoryIterator($this->getDirectoryImages());
    	foreach ($iterator as $fileinfo) {
        	if ($fileinfo->isFile()) {
            	array_push($filenames, $fileinfo->getFilename());
        	}
    	}

    	$selectImage = array_rand($filenames, $this->getnumberImages());
    	for($i = 0; $i < count($selectImage); $i++) {
	    	for($r = 0; $r < $this->getRepeatImages(); $r++){
				$this->config['map'][] = array(
				  "src" => $filenames[$selectImage[$i]],
				  "found"=>false,
				  "turned"=>false
			    );
	    	}
		}

    	return shuffle($this->config['map']);
	}

	/**
	 * restore Alias de setConfig : Restaure une map, a partir d'une configuration existante
	 *
	 * @param array $oldConfiguration : Map à restaurer
	 * @return array
	 */
	public function restore(Array $oldConfiguration) {
		$this->setConfig($oldConfiguration);
	}

	/**
	 * tryCombination : Test la combinaison des images retournées
	 *
	 * - si la combinaison est valide, turned est remit sur false et found sur true
	 * - si le combinaison n'est pas valide, turned est remit sur false et found reste sur false
	 *
	 * @param  array $turnedImages : Liste de key des Images retournées
	 * @return boolean
	 */
	public function tryCombination(Array $turnedImages) {

		$result = true;
		$current = current($turnedImages);
		while (next($turnedImages)) {
		  if ($this->config['map'][$current]['src'] === $this->config['map'][current($turnedImages)]['src']) {
			  $current = current($turnedImages);
			  continue;
		  } else {
			  $result = false;
			  break;
		  }

		}

		if($result === true) {
			foreach($turnedImages as $e) {
				$this->config['map'][$e]['turned'] = false;
				$this->config['map'][$e]['found']  = true;
			}
		}
		return $result;
	}

	/**
	 * turnImage
	 *
	 * @param  integer $key : Key de l'image à retouner
	 * @return void
	 */
	public function turnImage($key) {
		if(array_key_exists($key, $this->config['map']) === true) {
			$this->config['map'][$key]['turned'] = true;
		}
	}

	/**
	 * getTurnedImages
	 *
	 * @return array : Liste de key des Images retournées
	 */
	public function getTurnedImages() {
		$turned =  array();
		foreach($this->config['map'] as $key => $element) {
			if(array_key_exists('turned', $element) && $element['turned']===true){
				array_push($turned, $key);
			}
		}
		return $turned;
	}

	/**
	 * getFoundImages
	 *
	 * @return array : Liste de key des Images retourvées
	 */
	public function getFoundImages() {
		$found =  array();
		foreach($this->config['map'] as $key => $element) {
			if(array_key_exists('found', $element) && $element['found']===true){
				array_push($found, $key);
			}
		}
		return $found;
	}

	/**
	 * cleanTurnImage : Réinitialise les images retournées et non trouvées
	 *
	 * @return void
	 */
	public function cleanTurnImage() {
		for($i=0; $i<count($this->config['map']); $i++) {
			$this->config['map'][$i]['turned'] = false;
		}
	}
}