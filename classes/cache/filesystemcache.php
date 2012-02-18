<?php

namespace FuelMandango\Cache;

use \Mandango\Cache\FilesystemCache as BaseFilesystemCache;

class FilesystemCache extends BaseFilesystemCache {

	public function get($key)
	{
		echo "<pre>";
		var_dump("get");
		var_dump($key);
		var_dump(parent::get($key));
		echo "</pre>";
		return parent::get($key);
	}
	public function set($key, $value)
	{
		echo "<pre>";
		var_dump("set");
		var_dump($key);
		var_dump($value);
		echo "</pre>";
		return parent::set($key,$value);
	}
}