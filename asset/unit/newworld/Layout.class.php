<?php
/**
 * unit-newworld:Layout.class.php
 *
 * @creation  2017-05-09
 * @version   1.0
 * @package   unit-newworld
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 * @created   2018-04-13
 */
namespace OP\UNIT\NEWWORLD;

/** Layout
 *
 * @creation  2017-02-14
 * @version   1.0
 * @package   unit-newworld
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Layout
{
	/** trait.
	 *
	 */
	use \OP_CORE;

	/** Get layout controller.
	 *
	 * @return $string
	 */
	static private function _GetLayoutController()
	{
		//	Get layout directory.
		if(!$layout_dir = self::Directory() ){
			$message = "Has not been set layout directory.";
			\Notice::Set($message, debug_backtrace());
			return false;
		}

		//	Get layout name.
		if(!$layout_name = self::Name() ){
			$message = "Has not been set layout name.";
			\Notice::Set($message, debug_backtrace());
			return false;
		}

		//	Get layout controller's file path.
		$directory = rtrim(ConvertPath($layout_dir),'/');
		$full_path = "{$directory}/{$layout_name}/index.php";

		//	Check exists layout controller.
		if(!file_exists($full_path)){
			if( $io = file_exists( dirname($full_path) ) ){
				$message = "Does not exists layout controller. ($full_path)";
			}else{
				$message = "Does not exists layout directory. ($layout_name)";
			}
			\Notice::Set($message, debug_backtrace());
			return false;
		}

		//	...
		return $full_path;
	}

	/** Get/Set Layout directory.
	 *
	 * @param  string $path
	 * @return string $path
	 */
	static function Directory($path=null)
	{
		//	...
		if( $path ){
			$path = ConvertPath($path);
			$path = rtrim($path, '/') . '/';

			//	...
			_GetRootsPath('layout', $path);
		}

		//	...
		return self::_Store(__METHOD__, $path);
	}

	/** Get/Set Layout name.
	 *
	 * @param  string $name
	 * @return string $name
	 */
	static function Name($name=null)
	{
		if( $name ){
			//	...
			$dir = self::Directory();

			//	...
			$list = _GetRootsPath('layout', $dir . $name);

		}else if( $name === false ){

		}

		//	...
		return self::_Store(__METHOD__, $name);
	}

	/** Get/Set Layout execution.
	 *
	 * @param  boolean $execute
	 * @return boolean $execute
	 */
	static function Execute($execute=null)
	{
		return self::_Store(__METHOD__, $execute);
	}

	/** Execute layout.
	 *
	 * @param string $content
	 */
	static function Auto($content)
	{
		//	Search layout controller.
		if( $file_path = self::_GetLayoutController() ){
			//	Execute layout.
			Template::Run($file_path, ['content'=>$content]);
		}
	}
}
