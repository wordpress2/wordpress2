<?php
/**
 * wordpress2:/app.php
 *
 * @creation  2018-03-27
 * @version   1.0
 * @package   wordpress2
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
//	...
call_user_func(function(){
	try {
		//	Bootstrap the onepiece-framework's core.
		require(__DIR__.'/core/Bootstrap.php');

		//	Checking rewrite setting.
		if( 'app.php' !== basename($_SERVER['SCRIPT_FILENAME']) ){
			//	Has not been setting rewrite.
			include(__DIR__.'/bootstrap/op/rewrite.phtml');
		}

		//	Check mbstring installed.
		if(!function_exists('mb_language') ){
			include(__DIR__.'/bootstrap/php/mbstring.phtml');
		}

	} catch ( Throwable $e ){
		$file    = $e->getFile();
		$line    = $e->getLine();
		$message = $e->getMessage();
		exit("$file #$line, $message");
	}
});
