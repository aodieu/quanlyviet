<?php
/************************************************************************
	Bản quyền (C) 2021 thuộc về Trần Anh Phương <http://aodieu.com>
	Chương trình được phát hành theo các điều khoản của giấy phép phần 
	mềm tự do GNU GPL được xuất bản bởi Quỹ Phần Mềm Tự Do (Free 
	Software Foundation), phiên bản thứ 3 của giấy phép, hoặc bất kỳ 
	phiên bản nào mới hơn (theo tùy chọn của bạn).
	Chương trình này được phân phối với kỳ vọng rằng nó sẽ có ích, nhưng 
	KHÔNG CÓ BẤT KỲ BẢO HÀNH NÀO, thậm chí KHÔNG CÓ BẢO ĐẢM NGỤ Ý VỀ KHẢ 
	NĂNG KHAI THÁC THƯƠNG MẠI HAY PHÙ HỢP VỚI MỤC ĐÍCH SỬ DỤNG CỤ THỂ NÀO.
	Chi tiết về giấy phép <http://www.gnu.org/licenses/gpl-3.0.html>.
*************************************************************************/
define('VARLIB_PATH', $path_to_root.'/tmp');
define('VARLOG_PATH', $path_to_root.'/tmp');

function output_html($text) {
	global $before_box, $Ajax, $messages;
	// Fatal errors are not send to error_handler,
	// so we must check the output
	if ($text && preg_match('/\bFatal error(<.*?>)?:(.*)/i', $text, $m)) {
		$Ajax->aCommands = array();  // Don't update page via ajax on errors
		$text = preg_replace('/\bFatal error(<.*?>)?:(.*)/i','', $text);
		$messages[] = array(E_ERROR, $m[2], null, null);
	}
	$Ajax->run();
	return  in_ajax() ? fmt_errors() : ($before_box.fmt_errors().$text);
}

//-----------------------------------------------------------------------------
//	Removing magic quotes from nested arrays/variables
//
function strip_quotes($data) {
	if(version_compare(phpversion(), '5.4', '<') && get_magic_quotes_gpc()) {
		if(is_array($data)) {
			foreach($data as $k => $v) {
				$data[$k] = strip_quotes($data[$k]);
			}
		}
		else
			return stripslashes($data);
	}
	return $data;
}

function html_specials_encode($str) {
	return htmlspecialchars($str, ENT_QUOTES, $_SESSION['language']->encoding=='iso-8859-2' ? 'ISO-8859-1' : $_SESSION['language']->encoding);
}

function html_cleanup(&$parms) {
	foreach($parms as $name => $value) {
		if (is_array($value))
			html_cleanup($parms[$name]);
		else
			$parms[$name] = html_specials_encode($value);
	}
	reset($parms); // needed for direct key() usage later throughout the sources
}

function check_page_security($page_security) {
}

/*
 Ensure file is re-read on next request if php caching is active
*/
function cache_invalidate($filename) {
	if (function_exists('opcache_invalidate')) // OpCode extension
		opcache_invalidate($filename);
}

//============================================================================

if (!isset($path_to_root))
	$path_to_root = '.';

// Prevent register_globals vulnerability
if (isset($_GET['path_to_root']) || isset($_POST['path_to_root']))
	die('Restricted access');

include_once($path_to_root.'/includes/errors.inc');
// collect all error msgs
set_error_handler('error_handler' /*, errtypes */);

include_once($path_to_root.'/includes/current_user.inc');
include_once($path_to_root.'/includes/lang/language.inc');
include_once($path_to_root.'/includes/ajax.inc');
include_once($path_to_root.'/includes/ui/ui_msgs.inc');
include_once($path_to_root.'/includes/prefs/sysprefs.inc');

session_name('FAinstall');
session_start();

// this is to fix the "back-do-you-want-to-refresh" issue - thanx PHPFreaks
header('Cache-control: private');

include_once($path_to_root.'/config.default.php');

$_SESSION['SysPrefs'] = new sys_prefs();
$SysPrefs = &$_SESSION['SysPrefs'];

$inst_langs = array(
	'vi_VN' => array ( 'name' => 'Tiếng Việt',	'code' => 'vi_VN',	'encoding' => 'utf-8'),
	'C' => array ( 'name' => 'English',	'code' => 'C', 'encoding' => 'utf-8'),
);

$cur_error_level = $SysPrefs->go_debug > 0 ? -1 : E_USER_WARNING|E_USER_ERROR|E_USER_NOTICE;

error_reporting($cur_error_level);
ini_set('display_errors', 'On');

$Ajax = new Ajax();

get_text_init();

$i_lang = isset($_POST['inst_lang']) ? $_POST['inst_lang'] : 
	(isset($_SESSION['inst_set']['inst_lang']) ? $_SESSION['inst_set']['inst_lang'] : 'vi_VN');

// Page Initialisation
if (!isset($_SESSION['language']) || !method_exists($_SESSION['language'], 'set_language') || $_SESSION['language']->code != $i_lang) {
	$l = array_search_value($i_lang, $inst_langs,  'code');
	$_SESSION['language'] = new language($l['name'], $l['code'], $l['encoding'], @isset($l['rtl']) ? 'rtl' : 'ltr');
}
if (!isset($installed_languages))
	$installed_languages = array();

$_SESSION['language']->set_language($_SESSION['language']->code);
$GetText->add_domain( $_SESSION['language']->code, $path_to_root.'/install/lang');

include_once($path_to_root.'/version.php');
include_once($path_to_root.'/includes/main.inc');

// js/php validation rules container
$Validate = array();
// bindings for editors
$Editors = array();
// page help. Currently help for function keys.
$Pagehelp = array();

//$Ajax = new Ajax();

// intercept all output to destroy it in case of ajax call
register_shutdown_function('end_flush');
ob_start('output_html',0);

if (!isset($_SESSION['wa_current_user']))
	$_SESSION['wa_current_user'] = new current_user();

html_cleanup($_GET);
html_cleanup($_POST);
html_cleanup($_REQUEST);
html_cleanup($_SERVER);

// POST vars cleanup needed for direct reuse.
// We quote all values later with db_escape() before db update.
$_POST = strip_quotes($_POST);
