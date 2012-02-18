<?php

namespace FuelMandango;

use \Mandango\Mandango as BaseMandango;

class Mandango extends BaseMandango {

	public static $instance;

	public static function instance()
	{
		if (!isset(static::$instance)) {

			\Config::load('mongo', true);
			$config = \Config::get('mongo.default');

			$metadataFactory = new \metadatafactory();

			$cache = new \Mandango\Cache\ArrayCache(APPPATH."cache/mandango");

			if (\Config::get('mongo.profiling')) {
				$logger = function($query) {

					$query_string = $query['database'];
					$query_string .= ".";

					if (!empty($query['collection'])) {
						$query_string .= $query['collection'];
						$query_string .= ".";
					}

					$query_string .= $query['type'];
					$query_string .= "(";
					if (!empty($query['query'])) {
						$query_string .= str_replace(",",", ",json_encode($query['query']));
					}
					if (!empty($query['data'])) {
						$query_string .= str_replace(",",", ",json_encode($query['data']));
					}
					$query_string .= ")";

					if (!empty($query['sort'])) {
						$query_string .= ".sort";
						$query_string .= "(";
						$query_string .= json_encode($query['sort']);
						$query_string .= ")";
					}

					if (!empty($query['limit'])) {
						$query_string .= ".limit";
						$query_string .= "(";
						$query_string .= json_encode($query['limit']);
						$query_string .= ")";
					}

					if (!empty($query['skip'])) {
						$query_string .= ".skip";
						$query_string .= "(";
						$query_string .= json_encode($query['skip']);
						$query_string .= ")";
					}

					\Profiler::query($query_string,$query['time']/1000);
				};
			} else {
				$logger = null;
			}

			static::$instance = new static($metadataFactory, $cache,$logger);

			$server = "mongodb://";
			if (!empty($config['username']) && !empty($config['password'])) {
				$server .= "{$config['username']}:{$config['password']}@";
			}
			$server .= "{$config['hostname']}:{$config['port']}/{$config['database']}";

			$connection = new \Mandango\Connection($server, $config['database'], array(
				'persistent' => true,
			));

			static::$instance->setConnection('default', $connection);
			static::$instance->setDefaultConnectionName('default');

		}
		return static::$instance;
	}

	public function command($command,$options=array())
	{
		$this->getDefaultConnection()->getMongoDB()->command($command,$options);
	}


}