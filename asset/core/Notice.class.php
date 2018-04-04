<?php
/**
 * Notice.class.php
 *
 * @creation  2016-11-17
 * @version   1.0
 * @package   core
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/**
 * Notice
 *
 * @creation  2016-11-17
 * @version   1.0
 * @package   core
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Notice
{
	/** trait.
	 *
	 */
	use OP_CORE, OP_SESSION;

	/** Namespace
	 *
	 * @var string
	 */
	const _NAME_SPACE_ = 'STORE';

	/** Dump of notice.
	 *
	 * @param array $notice
	 */
	static function Dump( $notice )
	{
		$mime = Env::Mime();
		switch( $mime ){
			case 'text/html':
				echo '<div class="OP_NOTICE">';
				echo json_encode($notice);
				echo '</div>'."\r\n";
				break;

			case 'text/css':
			case 'text/javascript':
				echo "/* {$notice['message']} */".PHP_EOL;
				break;

			default:
				echo PHP_EOL.$notice['message'].PHP_EOL;
		}
	}

	/** Get notice array.
	 *
	 * @return array
	 */
	static function Get()
	{
		//	Get
		$session = self::Session(self::_NAME_SPACE_);

		//	Shift
		$notice  = array_shift($session);

		//	Set
		self::Session(self::_NAME_SPACE_, $session);

		//	Return
		return $notice;
	}

	/** Set notice array.
	 *
	 * @param string $message
	 */
	static function Set($e, $backtrace=null)
	{
		//	...
		if( $e instanceof Throwable ){
			$message   = $e->getMessage();
			$backtrace = $e->getTrace();
			$file      = $e->getFile();
			$line      = $e->getLine();
			$function  = null;
			array_unshift($backtrace, ['file'=>$file, 'line'=>$line]);
		}else{
			$message   = $e;
		}

		//	...
		if(!$backtrace ){
			$backtrace = debug_backtrace();
		//	array_shift($backtrace); Do not use for app world.
		}

		//	...
		$key = Hasha1($message);
		$timestamp = gmdate('Y-m-d H:i:s', time()+date('Z'));

		//	...
		$session = self::Session(self::_NAME_SPACE_);

		//	...
		$reference = isset($session[$key]) ? $session[$key]: null;

		//	...
		if( empty($reference) ){
			$reference['count']		 = 1;
			$reference['message']	 = $message;
			$reference['backtrace']	 = $backtrace;
			$reference['created']	 = $timestamp;
		}else{
			$reference['count']		+= 1;
			$reference['updated']	 = $timestamp;
		}

		//	...
		$session[$key] = $reference;

		//	...
		self::Session(self::_NAME_SPACE_, $session);
	}

	/** Callback of app shutdown.
	 *
	 */
	static function Shutdown()
	{
		//	...
		try {
			//	...
			if(!$is_admin = Env::isAdmin()){
				//	...
				if(!$to = Env::Get(Env::_ADMIN_MAIL_)){
					echo '<p>Has not been set admin mail address.</p>'.PHP_EOL;
					return;
				}

				//	...
				if(!$from = Env::Get(Env::_MAIL_FROM_)){
					$from = $to;
				}

				//	...
				$file_path = ConvertPath('op:/Template/Notice/Sendmail.phtml');

				//	...
				if( file_exists($file_path) === false ){
					print "<p>Does not file exists. ($file_path)</p>";
					return;
				}
			}

			//	...
			$session = self::Session(self::_NAME_SPACE_);

			//	...
			self::Session(self::_NAME_SPACE_, null);

			//	...
			foreach( $session as $notice ){
				if( $is_admin ){
					self::Dump($notice);
				}else{
					if(!ob_start()){
						echo '<p>"ob_start" was failed. (Notice::Shutdown)</p>'.PHP_EOL;
						return;
					}

					//	...
					include($file_path);

					//	...
					$content = ob_get_clean();

					//	...
					$subject = $notice['message'];

					//	...
					$mail = new EMail();
					$mail->From($from);
					$mail->To($to);
					$mail->Subject($subject);
					$mail->Content($content, 'text/html');
					if(!$io = $mail->Send()){
						return;
					}
				}
			}
		} catch ( Throwable $e ) {
			echo '<p>'.$e->GetMessage().'</p>'.PHP_EOL;
		}
	}
}

/** Register shutdown function.
 *
 * Moved from Bootstrap.php
 * So far, This routine has always been called up.
 * Currently, This shutdown function is called only when there is the Notice.
 *
 * @creation  2016-11-17
 * @moved     2017-01-19
 * @version   1.0
 * @package   core
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
register_shutdown_function('Notice::Shutdown');
