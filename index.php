<?php
/**
 * wordpress2:/index.php
 *
 * @creation  2016-11-22
 * @version   1.0
 * @package   wordpress2
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
 /***********************************************/
//	.htaccess file has not been initialized.	//
global $_OP;
if(!isset($_OP)){
	include(__DIR__.'/asset/app.php');
	return;
}
//	You should leave this logic. It's for you.	//
/***********************************************/

//	Get route table.
$route = Router::Get();

//	Checking 404 page.
if( count($route['args']) ){
	//	Non existent page.
	Template::Run('404.php');
}else{
	//	Access is top page.
	//	Welcome page is sample page
	//	 --> ./asset/template/welcome.phtml
	Template::Run('welcome.phtml');
}
