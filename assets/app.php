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
try {
	//	Bootstrap - Initialize onepiece-framework application.
	require(__DIR__.'/bootstrap.php');

	//	Include configuration file.
	require(__DIR__.'/config.php');

	//	Include private configuration file.
	if( file_exists(__DIR__.'/_config.php') ){
		require(__DIR__.'/_config.php');
	}

	//	Set unit directory.
	Unit::Directory(__DIR__.'/unit');

	//	Load of the NewWorld.
	Unit::Load('newworld');

	//	Launch application.
	App::Run();

} catch ( Throwable $e ){
	$file    = $e->getFile();
	$line    = $e->getLine();
	$message = $e->getMessage();
	exit("$file #$line, $message");
}
