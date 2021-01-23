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

//--------------------------------------------------------------------------------------

function add_shipper($shipper_name, $contact, $phone, $phone2, $address) {
	$sql = "INSERT INTO ".TB_PREF."shippers (shipper_name, contact, phone, phone2, address)
		VALUES (" . db_escape($shipper_name) . ", " .
		db_escape($contact). ", " .
		db_escape($phone). ", " .
		db_escape($phone2). ", " .
		db_escape($address) . ")";

	db_query($sql, 'The Shipping Company could not be added');
}

//--------------------------------------------------------------------------------------

function update_shipper($selected_id, $shipper_name, $contact, $phone, $phone2, $address) {
	$sql = "UPDATE ".TB_PREF."shippers SET shipper_name=" . db_escape($shipper_name). " ,
		contact =" . db_escape($contact). " ,
		phone =" . db_escape($phone). " ,
		phone2 =" . db_escape($phone2). " ,
		address =" . db_escape($address). "
		WHERE shipper_id = ".db_escape($selected_id);

	db_query($sql, 'The shipping company could not be updated');
}

//--------------------------------------------------------------------------------------

function delete_shipper($selected_id) {
	$sql="DELETE FROM ".TB_PREF."shippers WHERE shipper_id=".db_escape($selected_id);
	
	db_query($sql, 'could not delete shipper');
}

//--------------------------------------------------------------------------------------

function get_shippers($show_inactive) {
	$sql = "SELECT * FROM ".TB_PREF."shippers";
	if (!$show_inactive) $sql .= " WHERE !inactive";
	$sql .= " ORDER BY shipper_id";
	
	return db_query($sql, 'could not get shippers');
}

//--------------------------------------------------------------------------------------

function get_shipper($selected_id) {
	$sql = "SELECT * FROM ".TB_PREF."shippers WHERE shipper_id=".db_escape($selected_id);

	$result = db_query($sql, 'could not get shipper');
	return db_fetch($result);
}