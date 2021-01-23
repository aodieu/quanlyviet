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
	User authentication page popped up after login timeout during ajax call.
*/
$path_to_root = '..';
$page_security = 'SA_OPEN';

include_once($path_to_root.'/includes/session.inc');
include($path_to_root.'/access/login.php');

if (get_post('SubmitUser') && $_SESSION['wa_current_user']->logged_in()) {
	// After successfull login repeat last ajax call.
	// Login form consists all post variables from last ajax call.
	echo "<script>
		var o = opener;
		if (o) {
			o.JsHttpRequest.request(document.getElementsByName('SubmitUser')[0], o.document.forms[0]);
			close();
		}
	</script>";
}