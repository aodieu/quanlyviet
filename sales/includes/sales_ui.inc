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
include_once($path_to_root . '/includes/date_functions.inc');

include_once($path_to_root . '/includes/ui.inc');
include_once($path_to_root . '/includes/banking.inc');

//----------------------------------------------------------------------------
// helper functions for script execution control
//
function processing_start() {
	page_processing(false);
	processing_end();
	$_SESSION['Processing'] = $_SERVER['PHP_SELF'];
}

function processing_end() {
	page_processing(true);
	unset($_SESSION['Processing']);
	if ( isset($_SESSION['Items']) ) {
		unset($_SESSION['Items']->line_items);
		unset($_SESSION['Items']);
	}
}

function processing_active() {
	return (isset($_SESSION['Processing']) && $_SESSION['Processing']==$_SERVER['PHP_SELF']);
}
/*
	Check if the cart was not destroyed during opening the edition page in
	another browser tab.
*/
function check_edit_conflicts($cart_id, $cartname='Items') {
	global $Ajax, $SysPrefs;

	if ((!isset($SysPrefs->no_check_edit_conflicts) || $SysPrefs->no_check_edit_conflicts==0) && $cart_id && isset($_SESSION[$cartname]) && $cart_id != $_SESSION[$cartname]->cart_id) {
		display_error(_('This edit session has been abandoned by opening sales document in another browser tab. You cannot edit more than one sales document at once.'));
		$Ajax->activate('_page_body');
		display_footer_exit();
	}
}