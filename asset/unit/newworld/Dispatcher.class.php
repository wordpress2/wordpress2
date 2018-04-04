<?php
/**
 * Dispatcher.class.php
 *
 * @creation  2017-05-09
 * @version   1.0
 * @package   unit-newworld
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** Dispatcher
 *
 * @creation  2017-02-15
 * @version   1.0
 * @package   unit-newworld
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Dispatcher
{
	/** trait
	 *
	 */
	use OP_CORE;

	/** Execute end-point.
	 *
	 * @return string
	 */
	static function Run()
	{
		//	Execute app's end point. (app's controller)
		$route = Router::Get();

		//	Get current directory.
		$cdir = getcwd();

		//	Change current directory.
		chdir(dirname($route[Router::_END_POINT_]));

		//	Execute content.
		try{
			//	Execute end-point.
			$content = Template::Get($route[Router::_END_POINT_]);
		}catch( Exception $e ){
			Notice::Set($e->getMessage(), $e->getTrace());
		}

		//	Recovery current directory.
		chdir($cdir);

		//	...
		return $content;
	}
}
