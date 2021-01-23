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

define('FA_LOGOUT_PHP_FILE', '');

$page_security = 'SA_OPEN';
$path_to_root = '..';

include($path_to_root.'/includes/session.inc');
add_js_file('login.js');
include($path_to_root.'/includes/page/header.inc');

page_header(_('Logout'), true, false, '');

echo "<table width='100%' border='0'>
	<tr>
		<td align='center'><img src='".$path_to_root."/themes/default/images/quanlyviet.png' alt='Quản Lý Việt' width='250' onload='fixPNG(this)' ></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><div align='center'><font size=2>";
echo _('Thank you for using').' ';

echo '<strong>'.$SysPrefs->app_title.' '.$version.'</strong>';

echo "</font></div></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><div align='center'>";

echo "<a href='".$path_to_root."/index.php'><b>"._('Click here to Login Again.').'</b></a>';
echo "</div></td>
	</tr>
</table><br>\n";

end_page(false, true);
session_unset();
@session_destroy();