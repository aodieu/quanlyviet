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
function page_footer($no_menu=false, $is_index=false) {
	global $path_to_root, $js_lib, $Validate, $Editors, $Ajax, $sql_queries;

	$page_header_args = func_get_args();
	hook_invoke_all('pre_footer', $page_header_args);

	include_once($path_to_root.'/themes/'.user_theme().'/renderer.php');
	$rend = new renderer();
	$rend->menu_footer($no_menu, $is_index);

	$edits = 'editors = '.$Ajax->php2js($Editors).';';
	$Ajax->addScript('editors', $edits);

	echo "<script type='text/javascript'>
		_focus = '" . get_post('_focus') . "';
		_validate = " . $Ajax->php2js($Validate).";
		var $edits
	</script>";

	add_user_js_data();
	echo "\n<script type=\"text/javascript\"><!--\n";

	foreach($js_lib as $text)
		echo $text;
	echo "\n--></script>\n";

	if ($sql_queries) {
		div_start('footer_debug');
		 echo "<div class='note_msg'>".$sql_queries."</div>";
		div_end();
	}
	echo "</body></html>\n";
}