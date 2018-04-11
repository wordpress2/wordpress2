<?php
/**
 * unit-app:/App.class.php
 *
 * @creation  2018-04-04
 * @version   1.0
 * @package   unit-app
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** App
 *
 * @creation  2018-04-04
 * @version   1.0
 * @package   unit-app
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class App
{
	/** trait.
	 *
	 */
	use OP_CORE;

	/** Automatically run.
	 *
	 */
	static function Auto()
	{
		//	Execute end-point.
		$content = Dispatcher::Auto();

		//	Output to client.
		if( Env::Get( Layout::_EXECUTE_ ) ){
			//	Do layout.
			Layout::Auto($content);
		}else{
			//	Do not layout.
			echo $content;
		}
	}

	/** Get/Set title.
	 *
	 * @param  string $title
	 * @param  string $separator
	 * @return string $title
	 */
	static function Title($title=null, $separator=' | ')
	{
		static $_title;
		if( $title ){
			$_title = $title . $separator . $_title;
		}
		return $_title;
	}
}
