<?php
/**
 * @project hesper
 * @author  Alex Gorbylev
 */
function error2Exception($code, $string, $file, $line, $context) {
	throw new \Hesper\Core\Exception\BaseException($string, $code);
}
error_reporting(E_ALL);
set_error_handler('error2Exception', E_ALL);
ignore_user_abort(true);
define('HESPER_VERSION', '0.1');
define('HESPER_ROOT', realpath(dirname(__FILE__)));
define('HESPER_META', HESPER_ROOT.DIRECTORY_SEPARATOR.'meta'.DIRECTORY_SEPARATOR);

if( !defined('HESPER_TEMP_PATH') )
	define('HESPER_TEMP_PATH', sys_get_temp_dir());

if (!defined('HESPER_IPC_PERMS'))
	define('HESPER_IPC_PERMS', 0660);


