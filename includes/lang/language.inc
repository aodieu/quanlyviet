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
// Prevent register_globals vulnerability
if (isset($_GET['path_to_root']) || isset($_POST['path_to_root']))
	die("Restricted access");
@include_once($path_to_root . "/lang/installed_languages.inc");
include_once($path_to_root . "/includes/lang/gettext.inc");

class language 
{
	var $name;
	var $code;			// eg. ar_EG, en_GB
	var $encoding;		// eg. UTF-8, CP1256, ISO8859-1
	var	$dir;			// Currently support for Left-to-Right (ltr) and
						// Right-To-Left (rtl)
	var $version; // lang package version
	var $is_locale_file;
	
	function __construct($name, $code, $encoding, $dir = 'ltr') 
	{
		global $dflt_lang;
		
		$this->name = $name;
		$this->code = $code ? $code : ($dflt_lang ? $dflt_lang : 'C');
		$this->encoding = $encoding;
		$this->dir = $dir;
	}

	function get_language_dir() 
	{
		return "lang/" . $this->code;
	}

	function get_current_language_dir() 
	{
		$lang = $_SESSION['language'];
		return "lang/" . $lang->code;
	}

	function set_language($code) 
	{
	    global $path_to_root, $installed_languages, $GetText;

		$lang = array_search_value($code, $installed_languages, 'code');
		$changed = $this->code != $code || $this->version != @$lang['version'];

		if ($lang && $changed)
		{
			$this->name = $lang['name'];
			$this->code = $lang['code'];
			$this->encoding = $lang['encoding'];
			$this->version = @$lang['version'];
			$this->dir = (isset($lang['rtl']) && $lang['rtl'] === true) ? 'rtl' : 'ltr';
			$locale = $path_to_root . "/lang/" . $this->code . "/locale.inc";
			$this->is_locale_file = file_exists($locale);
		}

		$GetText->set_language($this->code, $this->encoding);
		$GetText->add_domain($this->code, $path_to_root . "/lang", $this->version);

		// Necessary for ajax calls. Due to bug in php 4.3.10 for this 
		// version set globally in php.ini
		ini_set('default_charset', $this->encoding);

		if (isset($_SESSION['wa_current_user']) && $_SESSION['wa_current_user']->logged_in() && isset($_SESSION['App']) && $changed)
			$_SESSION['App']->init(); // refresh menu
	}
}

if (!function_exists("_")) 
{
	function _($text) 
	{
		global $GetText;
		if (!isset($GetText)) // Don't allow using gettext if not is net.
			return $text;

		$retVal = $GetText->gettext($text);
		if ($retVal == "")
			return $text;
		return $retVal;
	}
}
