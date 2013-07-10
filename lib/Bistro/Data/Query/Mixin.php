<?php

namespace Bistro\Data\Query;

/**
 * An interface for the classes that can be used as a mixin.
 */
interface Mixin
{
	/**
	 * Gets all of the methods that should be passed as "mixin" methods.
	 *
	 * @return array
	 */
	public function getMethods();
}
