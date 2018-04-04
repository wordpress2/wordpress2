<?php
/**
 * Layout.class.php
 *
 * @creation  2017-05-09
 * @version   1.0
 * @package   unit-newworld
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

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
	use OP_CORE;

	/** Constants
	 *
	 * @var string
	 */
	const _EXECUTE_		 = 'layout-execute';
	const _DIRECTORY_	 = 'layout-dir';
	const _NAME_		 = 'layout-name';

	/** Get layout controller.
	 *
	 * @return $string
	 */
	static private function _GetLayoutController()
	{
		//	Get layout directory.
		if(!$layout_dir  = Env::Get(Layout::_DIRECTORY_)){
			$message = "Has not been set layout directory.";
			Notice::Set($message, debug_backtrace());
			return false;
		}

		//	Get layout name.
		if(!$layout_name = Env::Get(Layout::_NAME_)){
			$message = "Has not been set layout name.";
			Notice::Set($message, debug_backtrace());
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
			Notice::Set($message, debug_backtrace());
			return false;
		}

		//	...
		return $full_path;
	}

	/** Get/Set Layout execution.
	 *
	 * @param  $io
	 */
	static function Execute($io=null)
	{
		if( $io !== null ){
			Env::Set(self::_EXECUTE_, $io);
		}
		return Env::Get(self::_EXECUTE_);
	}

	/** Get/Set Layout name.
	 *
	 * @param  $io
	 */
	static function Name($name=null)
	{
		if( $name ){
			Env::Set(self::_NAME_, $name);
		}
		return Env::Get(self::_NAME_);
	}

	/** Execute layout.
	 *
	 * @param string $content
	 */
	static function Run($content)
	{
		//	...
		Http::Mime('text/html', true);

		//	Search layout controller.
		if( $file_path = self::_GetLayoutController() ){
			//	Execute layout.
			Template::Run($file_path, ['content'=>$content]);
		}
	}
}
