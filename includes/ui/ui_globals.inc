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
//include_once($path_to_root . '/includes/reserved.inc');

function set_global_supplier($supplier_id)  {
	$_SESSION['wa_global_supplier_id'] = $supplier_id; 
}

function get_global_supplier($return_all=true)  {
	if (!isset($_SESSION['wa_global_supplier_id']) || 
		($return_all == false && $_SESSION['wa_global_supplier_id'] == ALL_TEXT))
		return '';	
	return $_SESSION['wa_global_supplier_id'];
}

function set_global_stock_item($stock_id) {
	$_SESSION['wa_global_stock_id'] = $stock_id; 
}

function get_global_stock_item($return_all=true) {
	if (!isset($_SESSION['wa_global_stock_id']) || 
		($return_all == false && $_SESSION['wa_global_stock_id'] == ALL_TEXT))
		return '';		
	return $_SESSION['wa_global_stock_id'];
}

function set_global_customer($customer_id) {
	$_SESSION['wa_global_customer_id'] = $customer_id; 
}

function get_global_customer($return_all=true) {
	if (!isset($_SESSION['wa_global_customer_id']) || 
		($return_all == false && $_SESSION['wa_global_customer_id'] == ALL_TEXT))
		return '';
	return $_SESSION['wa_global_customer_id'];
}

function set_global_curr_code($curr_code) {
	$_SESSION['wa_global_curr_code'] = $curr_code;
}

function get_global_curr_code() {
	if (!isset($_SESSION['wa_global_curr_code']))
		return '';
	return $_SESSION['wa_global_curr_code'];
}

function count_array($array) {
	return (is_array($array)) ? count($array) : (($array === NULL) ? 0 : 1);
}