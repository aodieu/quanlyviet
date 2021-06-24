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
/*
	If no context is set current page/menu screen is selected.
*/
function help_url($context=null) {

	global $SysPrefs, $help_context;

	$country = $_SESSION['language']->code;
	$clean = 0;
	
	if ($context!=null)
		$help_page_url = $context;
	elseif (isset($help_context))
		$help_page_url = $help_context;
	else {// main menu
		$app = $_SESSION['sel_app'];
		$help_page_url = $_SESSION['App']->applications[$app]->help_context;
		$clean = 1;
	}

	if (@$SysPrefs->old_style_help)
		$help_page_url = _($help_page_url);

	if ($clean)
		$help_page_url = access_string($help_page_url, true);

	return html_specials_encode(
		$SysPrefs->help_base_url
		.urlencode(strtr(ucwords($help_page_url), array(' '=>'', '/'=>'', '&'=>'And')))
		.'&ctxhelp=1&lang='.$country);
}

function send_css($css = '') {
	global $css_files;

	init_css();

	foreach ($css_files as $css_file) {
		echo "<link href='".$css_file."' rel='stylesheet' type='text/css'> \n";
	}
	if ($css)
		echo "<link href='".$css."' rel='stylesheet' type='text/css'> \n";
}

function send_scripts() {
	global $SysPrefs, $path_to_root, $js_static, $js_path, $js_userlib;

	$js ='';
	foreach($js_static as $jsfile) {
		$basename = basename($jsfile);
		$fpath = user_js_cache().'/'.$basename;
		// compress also static files
		if (!file_exists($fpath) || $SysPrefs->go_debug) {
			// don't need to compress minified files
			if(strpos($basename, '.min.js' ) !== false)
				copy($js_path.$jsfile, $fpath);
			else
				cache_js_file($fpath, file_get_contents($js_path.$jsfile));
			
			$js .= '<script src="'.$fpath.'"></script>';
		}
	}
	foreach($js_userlib as $jsfile) {
		$js .= '<script src="'.$jsfile.'"></script>';
	}

	echo $js;
}

function page_header($title, $no_menu=false, $is_index=false, $onload='', $js='', $css='') {
	// titles and screen header
	global $path_to_root, $SysPrefs, $db_connections;

	if (in_ajax() || isset($_POST['REP_ID']))
		return; // just for speed up

	$theme = user_theme();

	if ($SysPrefs->help_base_url != null && $SysPrefs->use_popup_windows && $js == '') {
		if (!function_exists('get_js_open_window'))
			include_once($path_to_root.'/includes/ui/ui_view.inc');
		
		add_js_source(get_js_open_window(900, 500));
	}
	if ($js != '')
		add_js_source($js);

	if (!isset($no_menu))
		$no_menu = false;
	
	if (isset($_SESSION['App']) && is_object($_SESSION['App']) && isset($_SESSION['App']->selected_application) && $_SESSION['App']->selected_application != '')
		$sel_app = $_SESSION['App']->selected_application;
	elseif (isset($_SESSION['sel_app']) && $_SESSION['sel_app'] != '')
		$sel_app = $_SESSION['sel_app'];
	else
		$sel_app = user_startup_tab();
	
	$_SESSION['sel_app'] = $sel_app;

	// When startup tab for current user was set to already 
	// removed/inactivated extension module select Sales tab as default.
	if (isset($_SESSION['App']) && is_object($_SESSION['App']))
		$_SESSION['App']->selected_application = isset($_SESSION['App']->applications[$sel_app]) ? $sel_app : 'orders';

	$page_header_args = func_get_args();
	if(isset($db_connections))	// skip before installation
		hook_invoke_all('pre_header', $page_header_args);

	$encoding = $_SESSION['language']->encoding;

	if (!headers_sent())
		header("Content-type: text/html; charset=$encoding");
	
	echo "<!DOCTYPE html>\n";
	echo "<html dir='" . $_SESSION['language']->dir . "' >\n";
	echo "<head profile=\"http://www.w3.org/2005/10/profile\"><title>".$title."</title>";
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') != -1) // IE 11 doesn't work with input file type in form.
		echo "<meta http-equiv='X-UA-Compatible' content='IE=10'>\n";
	echo "<meta charset='".$encoding."'>";
	echo "<meta name='viewport' content='width=device-width,initial-scale=1'>";
	echo "<link href='".$path_to_root."/themes/default/images/favicon.ico' rel='icon' type='image/x-icon'> \n";

	send_css($css);

	send_scripts();

	echo "</head> \n";
	if ($onload == '')
		echo '<body>';
	else
		echo "<body onload=\"$onload\">";

	include_once($path_to_root.'/themes/'.$theme.'/renderer.php');
	$rend = new renderer();
	$rend->menu_header($title, $no_menu, $is_index);
	error_box();
}