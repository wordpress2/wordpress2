<?php
/**
 * Time.class.php
 *
 * This class is part of the "New World".
 * We call "ICE AGE".
 * "ICE AGE" is very simple, but very powerful.
 *
 * @creation  2016-11-17
 * @version   1.0
 * @package   core
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/**
 * Time
 *
 * @creation  2016-11-17
 * @version   1.0
 * @package   core
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Time
{
	/** trait.
	 *
	 */
	use OP_CORE;

	/** Frozen time.
	 *
	 * @var integer
	 */
	static $_time;

	/** Format
	 *
	 * @param  string $formt 'Y-m-d H:i:s'
	 * @param  string $calc  '1 month'
	 * @return string
	 */
	static function _Format($format, $calc=null)
	{
		//	...
		$time = self::Get();

		//	...
		if( $calc ){
			if(!$time = strtotime($calc, $time)){
				Notice::Set("strtotime was failed. ($calc)");
			}
		}

		//	...
		return date($format, $time);
	}

	/** Initialize
	 *
	 */
	static function Init($timezone)
	{
		//	Timezone - System
		ini_set('date.timezone', $timezone);

		//	Timezone - user land
		if( function_exists('geoip_db_avail') and geoip_db_avail(GEOIP_COUNTRY_EDITION) ){
			$host = Env::isLocalhost() ? 'yahoo.co.jp': $_SERVER['REMOTE_ADDR'];
			$temp = geoip_record_by_name($host);
			$timezone = geoip_time_zone_by_country_and_region($temp['country_code'], $temp['region']);
		}

		//	Timezone - user land
		Time::Timezone($timezone);
	}

	/** Return date. (not include time)
	 *
	 * @return string
	 */
	static function Date($calc=null)
	{
		return self::_Format('Y-m-d', $calc);
	}

	/** Return date and time.
	 *
	 * @return string
	 */
	static function Datetime($calc=null)
	{
		return self::_Format('Y-m-d H:i:s', $calc);
	}

	/** Get/Set Timezone.
	 *
	 * @param  null|string $timezone
	 * @return null|string
	 */
	static function Timezone($timezone=null)
	{
		//	...
		static $_timezone;

		//	...
		if( $timezone ){
			if( $_timezone ){
				Notice::Set("Timezone is already set.");
				return false;
			}

			//	...
			$_timezone = $timezone;

			//	...
			return date_default_timezone_set($timezone);
		}else{
			return date_default_timezone_get();
		}
	}

	/** Get GMT date time.
	 *
	 * @param string $format
	 */
	static function GMT($format='Y-m-d H:i:s')
	{
		$time = self::Get(true);
		return date($format, $time);
	}

	/** Get frozen time.
	 *
	 * <pre>
	 * $time = Time::Get();
	 * $gmt  = Time::Get(true);
	 * </pre>
	 *
	 * @param  boolean $gmt True is return GMT
	 * @return integer
	 */
	static function Get($gmt=false)
	{
		//	...
		if(!self::$_time){
			self::$_time = strtotime( gmdate('Y-m-d H:i:s') );
			if(!$gmt ){
				self::$_time += date('Z');
			}
		}

		//	...
		return self::$_time;
	}

	/** Set frozen time.
	 *
	 * <pre>
	 * Time::Set('2020-01-01'); // 2020-01-01 00:00:00
	 * </pre>
	 *
	 * @param null|integer|string $time
	 */
	static function Set($time)
	{
		if( self::$_time ){
			Notice::Set("Frozen time is already setted.");
		}else{
			//	...
			if(!is_numeric($time)){ // $time --> 2020-01-01 00:00:00
				if(!$time = strtotime($time)){
					Notice::Set("strtotime was failed.");
				}else{
					$time = strtotime(gmdate('Y-m-d H:i:s'));
				}
			}
			self::$_time = $time;
		}
	}
}