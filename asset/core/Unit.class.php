<?php
/**
 * Unit.class.php
 *
 * @creation  2016-11-28
 * @version   1.0
 * @package   core
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/**
 * Unit
 *
 * <pre>
 * //	Set unit directory.
 * Env::Set(Unit::_DIRECTORY_, '/www/op/7/unit/');
 *
 * //	Factory
 * $obj = Unit::Factory('UnitName');
 * </pre>
 *
 * @creation  2016-11-28
 * @version   1.0
 * @package   core
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Unit
{
	/** trait.
	 *
	 */
	use OP_CORE;

	/** Search directory.
	 *
	 * @var string
	 */
	const _DIRECTORY_ = 'unit-dir';

	/** Repository
	 *
	 * @var string
	 */
	const _REPOSITORY_ = 'https://github.com/onepiece-framework/';

	/** Pooling of object. (singleton)
	 *
	 * @var array
	 */
	static private $_pool;

	/** Get/Set unit directory.
	 *
	 * @param  string|null         $dir
	 * @return string|null|boolean $dir
	 */
	static function Directory($dir=null)
	{
		if( $dir ){
			if( strpos($dir, ':') ){
				$dir = rtrim(ConvertPath($dir), '/').'/';
			}

			//	...
			if(!file_exists($dir)){
				$message = "Does not exists unit directory. ($dir)";
				Notice::Set($message, debug_backtrace());
				return false;
			}

			//	...
			Env::Set(self::_DIRECTORY_, $dir);
		}

		//	...
		return $dir ? null: Env::Get(self::_DIRECTORY_);
	}

	/** Return instance. (singleton)
	 *
	 * @param  string $name
	 * @return boolean|object
	 */
	static function Factory($name)
	{
		//	...
		if( isset( self::$_pool[$name] ) ){
			return self::$_pool[$name];
		}

		//	...
		if(!self::Load($name)){
			return false;
		}

		//	Instantiate.
		self::$_pool[$name] = new $name();
		return self::$_pool[$name];
	}

	/** Fetch git repository from github.
	 *
	 * @param  string $name
	 * @return boolean
	 */
	static function Fetch($name)
	{
		//	...
		$save_dir = getcwd();

		//	...
		if(!$unit_dir = Env::Get(self::_DIRECTORY_)){
			return false;
		}

		//	...
		$unit_dir = ConvertPath($unit_dir);

		//	...
		chdir($unit_dir);

		//	...
		$command = 'git clone '. self::_REPOSITORY_ ."unit-{$name}.git $name";
		$return = exec($command, $output, $status);

		//	...
		chdir($save_dir);

		//	...
		switch( ifset($status, 0) ){
			case 0: // successful
				break;

			case 128:
				$status = 'Permission denied';
				break;

			default:
				Notice::Set("Command execution has failed. ($status, $command)");
		}

		//	...
		return $status === 0 ? true: false;
	}

	/** Load of unit controller.
	 *
	 * @param string $name
	 */
	static function Load($name)
	{
		//	...
		if(!$dir = Env::Get(self::_DIRECTORY_)){
			$message = "Has not been set unit directory.\n".' Example: Env::Set(Unit::_DIRECTORY_, "/www/op/unit");';
			Notice::Set($message, debug_backtrace());
			return false;
		}

		//	...
		$dir = ConvertPath($dir);
		$dir = realpath($dir);

		//	...
		if(!file_exists($dir)){
			$message = "Does not exists unit directory. ($dir)";
			Notice::Set($message, debug_backtrace());
			return false;
		}

		//	...
		if(!file_exists("{$dir}/{$name}")){
			//	...
			if(!self::Fetch($name) ){
				$message = "Does not exists this unit. ($dir/$name)";
				Notice::Set($message, debug_backtrace());
				return false;
			}
		}

		//	...
		$path = "{$dir}/{$name}/index.php";

		//	...
		if(!file_exists($path)){
			$message = "Does not exists unit controller. ({$name}/index.php)";
			Notice::Set($message, debug_backtrace());
			return false;
		}

		//	...
		return include($path);
	}
}
