<?php
/**
 * Template.class.php
 *
 * @creation  2017-05-09
 * @version   1.0
 * @package   unit-newworld
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** Template
 *
 * @creation  2017-02-09
 * @version   1.0
 * @package   unit-newworld
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Template
{
	/** trait.
	 */
	use OP_CORE;

	/** Search to this template directory.
	 *
	 * @var string
	 */
	const _DIRECTORY_ = 'template-dir';

	/** Return real file path from meta path.
	 *
	 * @param  string
	 * @return string
	 */
	static function _GetTemplateFilePath($path)
	{
		//	Convert to real path from meta path.
		if( strpos($path, ':') ){
			$path = ConvertPath($path);
		}

		//	File was found.
		if( file_exists($path) ){
			return $path;
		}

		//	Search to in template directory.
		if( $dir  = Env::Get(self::_DIRECTORY_) ){
			$path = rtrim(ConvertPath($dir), '/').'/'.$path;
			if( file_exists($path) ){
				//	File was found.
				return $path;
			}
		}

		//	...
		Notice::Set("Does not exists this file path. ($path)");

		//	...
		return '';
	}

	/** Return executed file content.
	 *
	 * @param  string $file_path
	 * @param  array  $args
	 * @return string
	 */
	static function Get($file_path, $args=null)
	{
		//	...
		if(!ob_start()){
			Notice::Set("ob_start was failed.");
			return;
		}

		//	...
		self::Run($file_path, $args);

		//	...
		return ob_get_clean();
	}

	/** Catch fatal error and Exception.
	 *
	 * @param  string $file_path
	 * @param  array  $args
	 */
	static function Run($file_path, $args=null)
	{
		try {
			//	...
			if(!$file_path){
				Notice::Set("Has not been set file path. ($file_path)");
				return;
			}

			//	...
			if(!file_exists($file_path) ){
				//	...
				if(!$file_path = self::_GetTemplateFilePath($file_path)){
					return;
				}
			}

			//	...
			if( $args ){
				if(!$count = extract($args, null, null)){
					$message = "Passed arguments is not an assoc array. (count=$count)";
					Notice::Set($message, debug_backtrace());
				}
			}

			//	...
			$save = getcwd();

			//	...
			chdir( dirname($file_path) );

			//	...
			$io = include( basename($file_path) );

			//	...
			chdir($save);

		} catch (Throwable $e) {
			$trace = $e->getTrace();
			$temp  = [];
			$temp['file'] = $e->getFile();
			$temp['line'] = $e->getLine();
			array_unshift($trace, $temp);
			Notice::Set($e->getMessage(), $trace);
		}

		//	...
		return ifset($io) === false ? false: null;
	}
}
