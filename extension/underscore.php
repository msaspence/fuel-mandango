<?php

/*
 * This file is part of Mandango.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * This source file is subject to the MIT license that is packaged
 * with this source code in the file LICENSE.
 */

namespace FuelMandango\Extension;

use Mandango\Mondator\Extension;
use Mandango\Mondator\Definition;
use Mandango\Mondator\Output;
use  Mandango\Mondator\Definition\Method;
use Fuel\Core\Inflector;

/**
 * Mandango "Underscore" extension.
 */
class Underscore extends Extension
{
	/**
	 * {@inheritdoc}
	 */
	protected function doClassProcess()
	{
		foreach($this->definitions as $definition) {
			if (isset($definition->addUnderscoreAlias) && $definition->addUnderscoreAlias) {
				$__call = new Method('public', '__call', '$method', <<<EOF
	      return call_user_func_array(\Fuel\Core\Inflector::camelcase(\$method), func_get_args());
EOF
	      );
			}
			$definition->addMethod($__call);
		}
	}
}
