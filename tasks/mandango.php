<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2011 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Tasks;

use FuelMandango\Mondator\Mondator;
use Mandango\Extension\Core;

class Mandango
{

	public static function generate()
	{
		$config_classes = array();

		// Get the app config files
		if (is_dir(APPPATH."config/mandango")) {
			$app_config_classes = \File::read_dir(APPPATH."config/mandango",1);
			foreach($app_config_classes as $class) {
				 $config_class = require APPPATH."config/mandango/{$class}";
				 $config_classes[] = $config_class;
			}
		}

		// Get package config files
		foreach(\Package::loaded() as $package) {
			if (is_dir("{$package}config/mandango")) {
				$package_config_classes = \File::read_dir("{$package}config/mandango");
				foreach($package_config_classes as $class) {
					$config_class = require "{$package}config/mandango/{$class}";
					foreach($config_class as &$class) {
						$class['output'] = "{$package}model";
					}
					unset($class);
					$config_classes[] = $config_class;
				}
			}
		}

		// Get module config files
		foreach(\Config::get('module_paths') as $module_path) {
			if (is_dir($module_path)) {
				foreach (\Config::get('always_load.modules') as $module) {
					if (is_dir("{$module_path}{$module}/config/mandango")) {
						$module_config_classes = \File::read_dir("{$module_path}{$module}/config/mandango");
						foreach($module_config_classes as $class) {
							$config_class = require "{$module_path}{$module}/config/mandango/{$class}";
							foreach($config_class as &$class) {
								$class['output'] = "{$module_path}{$module}/model";
							}
							unset($class);
							$config_classes[] = $config_class;
						}
					}
				}
			}
		}

		if (count($config_classes) == 0) {
			echo "Nothing to do\nIf you were expecting something to happen check that you have created some config files in config/mandango";
			return;
		}

		$mondator = new Mondator();

		// assign extensions
		$mondator->setExtensions(array(
			new \FuelMandango\Extension\Core(array(
				'metadata_factory_class'  => 'metadatafactory',
				'metadata_factory_output' => APPPATH.'classes/mandango/mapping',
				'default_output'		  => APPPATH.'classes/mandango/model',
			)),
			new \FuelMandango\Extension\Fuel()
		));

		$config_classes = call_user_func_array('array_merge',	$config_classes);
		// var_dump($config_classes);
		// exit;
		$mondator->setConfigClasses($config_classes);
		$mondator->process();

	}

	public static function ensure_indexes()
	{
		$mandango->ensureAllIndexes();
	}

}

/* End of file tasks/robots.php */
