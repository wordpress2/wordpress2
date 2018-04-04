<?php
/**
 * Developer.class.php
 *
 * This class is part of the "New World".
 *
 * @creation  2016-06-09
 * @version   1.0
 * @package   core
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/**
 * Developer
 *
 * @creation  2014-11-29
 * @version   1.0
 * @package   core
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Developer
{
	/** trait.
	 *
	 */
	use OP_CORE;

	/** Namespace
	 *
	 * @var string
	 */
	const _NAME_SPACE_ = 'DEVELOPER';

	/** Convert to json from array.
	 *
	 * @param array $obj
	 */
	static function _toJson($obj)
	{
		$json = json_encode($obj);
		$json = htmlentities($json, ENT_NOQUOTES, 'utf-8');
	//	$json = str_replace(['&lt;','&gt;'], ['＜','＞'], $json);
		$json = str_replace(['&'], ['&amp;'], $json);
		return $json;
	}

	/** Mark
	 *
	 * @param array $args
	 * @param array $trace
	 */
	static function Mark($args, $trace)
	{
		//	...
		if(!class_exists('Http', false) ){
			var_dump($args);
			return;
		}

		//	...
		switch( $mime = strtolower(Http::Mime()) ){
			case 'text/css':
				self::MarkCss($args, $trace);
				break;

			case 'text/javascript':
				self::MarkJS($args, $trace);
				break;

			case 'text/json':
			case 'text/jsonp':
				self::MarkJson($args, $trace);
				break;

			case 'text/html':
			default:
				self::MarkHtml($args, $trace);
		}
	}

	/** MarkCss
	 *
	 * @param mixed $value
	 * @param array $trace
	 */
	static function MarkCss($value, $trace)
	{
		print PHP_EOL;
		print "/* $value */".PHP_EOL;
	}

	/** MarkHtml
	 *
	 * @param mixed $value
	 * @param array $trace
	 */
	static function MarkHtml($args, $trace)
	{
		//	...
		$later = [];

		//	$mark
		$mark = [];
		$mark['file'] = CompressPath($trace['file']);
		$mark['line'] = $trace['line'];
		$mark['args'] = [];

		//	$args
		foreach( $args as $value ){
			switch( $type = gettype($value) ){
				case 'array':
					//	Stack
					$later[] = $value;
					//	Look and feel to array.
					$count   = count($value);
					$value   = $type."($count)"; // --> array(1)
					break;

				case 'object':
					$later[] = $value;
					$value   = get_class($value);
					break;
			}
			/*
			$mark['args'][] = [
				'type'  => $type,
				'value' => $value,
			];
			*/
			$mark['args'][] = $value;
		}

		//	...
		print '<div class="OP_MARK">'.self::_toJson($mark).'</div>'.PHP_EOL;

		//	...
		foreach( $later as $value ){
			print '<div class="OP_DUMP">'.self::_toJson($value).'</div>'.PHP_EOL;
		}
	}

	/** MarkJS
	 *
	 * @param mixed $value
	 * @param array $trace
	 */
	static function MarkJS($value, $trace)
	{
		print PHP_EOL;
		print "console.log($value);".PHP_EOL;
		print "console.dir($trace);".PHP_EOL;
	}

	/** MarkJson
	 *
	 * @param mixed $value
	 * @param array $trace
	 */
	static function MarkJson($value, $trace)
	{
		global $_JSON;
		$mark['message']   = $value;
		$mark['backtrace'] = $trace;
		$_JSON['admin']['mark'][] = $mark;
	}
}