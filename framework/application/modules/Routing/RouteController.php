<?php
/**
 * Created by PhpStorm.
 * User: trask
 * Date: 10/9/16
 * Time: 9:21 PM
 */

namespace Mamook\Routing;

use League\Route\RouteCollection;

class RouteController
{
	/**
	 * @var RouteCollection
	 */
	private $route;

	public function __construct(RouteCollection $route)
	{
		$this->route=$route;
	}
}