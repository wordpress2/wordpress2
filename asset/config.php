<?php
/**
 * wordpress2:/config.php
 *
 * @creation  2018-03-27
 * @version   1.0
 * @package   wordpress2
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
//	Time
Time::Timezone('Asia/Tokyo');

//	Layout settigs.
Layout::Directory(__DIR__.'/layout');
Layout::Execute(true);
Layout::Name('white');

//	Template settings.
Template::Directory(__DIR__.'/template');
