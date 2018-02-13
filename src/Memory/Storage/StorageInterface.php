<?php

namespace Memory\Storage;


interface StorageInterface {

	public function read($key);

	public function write($key, $value);
}