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
function add_credit_status($description, $disallow_invoicing) {
	$sql = "INSERT INTO ".TB_PREF."credit_status (reason_description, dissallow_invoices) 
		VALUES (".db_escape($description).",".db_escape($disallow_invoicing).")";

	db_query($sql, 'could not add credit status');
}

function update_credit_status($status_id, $description, $disallow_invoicing) {
	$sql = "UPDATE ".TB_PREF."credit_status SET reason_description=".db_escape($description).",
		dissallow_invoices=".db_escape($disallow_invoicing)." WHERE id=".db_escape($status_id);

	db_query($sql, 'could not update credit status');
}

function get_all_credit_status($all=false) {
	$sql = "SELECT * FROM ".TB_PREF."credit_status";
	if (!$all) $sql .= " WHERE !inactive";

	return db_query($sql, 'could not get all credit status');
}

function get_credit_status($status_id) {
	$sql = "SELECT * FROM ".TB_PREF."credit_status WHERE id=".db_escape($status_id);

	$result = db_query($sql, 'could not get credit status');

	return db_fetch($result);
}

function delete_credit_status($status_id) {
	$sql="DELETE FROM ".TB_PREF."credit_status WHERE id=".db_escape($status_id);

	db_query($sql, 'could not delete credit status');
}