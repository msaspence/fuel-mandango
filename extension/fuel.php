<?php

namespace FuelMandango\Extension;

use Mandango\Mondator\Extension;

/**
 * Core extension.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class Fuel extends Extension
{

	protected function doClassProcess()
	{
		$this->definitions['document_base']->setParentClass('\\Model\\Document');
	}

}