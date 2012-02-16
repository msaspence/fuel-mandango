<?php

namespace FuelMandango\Mondator;

use Mandango\Mondator\Mondator as BaseMondator;
use Mandango\Mondator\Dumper;

class Mondator extends BaseMondator
{

	public static $packages;

	/**
	 * Dump containers.
	 *
	 * @param array $containers An array of containers.
	 *
	 * @api
	 */
	public function dumpContainers(array $containers)
	{
		// directories
		foreach ($containers as $container) {
			foreach ($container->getDefinitions() as $name => $definition) {
				$output = $definition->getOutput();
				$dir	= strtolower($output->getDir());

				if (!file_exists($dir) && false === @mkdir($dir, 0777, true)) {
					throw new \RuntimeException(sprintf('Unable to create the %s directory (%s).', $name, $dir));
				}

				if (!is_writable($dir)) {
					throw new \RuntimeException(sprintf('Unable to write in the %s directory (%s).', $name, $dir));
				}
			}
		}

		// output
		foreach ($containers as $container) {
			foreach ($container->getDefinitions() as $name => $definition) {
				$output = $definition->getOutput($name);
				$dir	= strtolower($output->getDir());

				$file = strtolower($dir.DIRECTORY_SEPARATOR.$definition->getClassName().'.php');

				if (!file_exists($file) || $output->getOverride()) {
					$dumper  = new Dumper($definition);
					$content = $dumper->dump();

					$tmpFile = tempnam(dirname($file), basename($file));
					if (false === @file_put_contents($tmpFile, $content) || !@rename($tmpFile, $file)) {
						throw new \RuntimeException(sprintf('Failed to write the file "%s".', $file));
					}
					chmod($file, 0644);
				}
			}
		}
	}

	public static function register_package($name,$namespace)
	{
		static::$packages[$name] = $namespace;
	}
}
