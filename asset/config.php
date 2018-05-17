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
OP\UNIT\NEWWORLD\Layout::Directory(__DIR__.'/layout');
OP\UNIT\NEWWORLD\Layout::Execute(true);
OP\UNIT\NEWWORLD\Layout::Name('white');

//	Template settings.
OP\UNIT\NEWWORLD\Template::Directory(__DIR__.'/template');
