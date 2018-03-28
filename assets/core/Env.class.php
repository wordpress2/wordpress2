<?php
/**
 * Env.class.php
 *
 * @creation  2016-06-09
 * @version   1.0
 * @package   core
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/**
 * Env
 *
 * @creation  2016-06-09
 * @version   1.0
 * @package   core
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Env
{
	/** trait.
	 *
	 */
	use OP_CORE;

	/** Constant.
	 *
	 * @var string
	 */
	const _ADMIN_IP_	 = 'admin-ip';
	const _ADMIN_MAIL_	 = 'admin-mail';
	const _MAIL_FROM_	 = 'mail-from';

	/** Private static values.
	 *
	 * @var array
	 */
	static $_env;
	static $_is_admin;
	static $_is_localhost;

	/** Is Admin
	 *
	 * @return boolean
	 */
	static function isAdmin()
	{
		if( self::$_is_admin === null ){
			if( self::isLocalhost() ){
				self::$_is_admin = true;
			}else{
				self::$_is_admin = ifset(self::$_env[self::_ADMIN_IP_]) === $_SERVER['REMOTE_ADDR'] ? true: false;
			}
		}
		return self::$_is_admin;
	}

	/** Is localhost
	 *
	 * @return boolean
	 */
	static function isLocalhost()
	{
		if(!self::$_is_localhost){
			self::$_is_localhost = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' or $_SERVER['REMOTE_ADDR'] === '::1') ? true : false;
		}
		return self::$_is_localhost;
	}

	/** Get environment value.
	 *
	 * @param  string $key
	 * @param  string|integer|boolean|array|object $default
	 * @return string|integer|boolean|array|object
	 */
	static function Get($key, $default=null)
	{
		return ifset(self::$_env[$key], $default);
	}

	/** Get transmitted MIME.
	 *
	 * @return string
	 */
	static function Mime()
	{
		$headers = headers_list();
		foreach($headers as $header){
			if( strpos($header, 'Content-type') === 0 ){
				$pos = strpos($header, ';');
				return substr($header, 14, $pos - 14);
			}
		}
	}

	/** Set environment value.
	 *
	 * @param string $key
	 * @param string|integer|boolean|array|object $var
	 */
	static function Set($key, $var)
	{
		//	...
		$result = isset(self::$_env[$key]) ? 1: 0;

		//	...
		if( $key === self::_ADMIN_IP_ ){
			self::$_is_admin = null;
		}

		//	...
		self::$_env[$key] = $var;

		//	...
		return $result +1;
	}
}