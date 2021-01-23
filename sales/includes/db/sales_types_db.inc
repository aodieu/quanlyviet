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
function add_sales_type($name, $tax_included, $factor) {
	$sql = "INSERT INTO ".TB_PREF."sales_types (sales_type,tax_included,factor) VALUES (".db_escape($name).","
		.db_escape($tax_included).",".db_escape($factor).")";
	db_query($sql, 'could not add sales type');		
}

function update_sales_type($id, $name, $tax_included, $factor) {

	$sql = "UPDATE ".TB_PREF."sales_types SET sales_type = ".db_escape($name).",
	tax_included =".db_escape($tax_included).", factor=".db_escape($factor)." WHERE id = ".db_escape($id);
	
	db_query($sql, 'could not update sales type');			
}

function get_all_sales_types($all=false) {
	$sql = "SELECT * FROM ".TB_PREF."sales_types";
	if (!$all)
		$sql .= " WHERE !inactive";
	
	return db_query($sql, 'could not get all sales types');
} 

function get_sales_type($id) {
	$sql = "SELECT * FROM ".TB_PREF."sales_types WHERE id=".db_escape($id);
	
	$result = db_query($sql, 'could not get sales type');
	
	return db_fetch($result);
}

function get_sales_type_name($id) {
	$sql = "SELECT sales_type FROM ".TB_PREF."sales_types WHERE id=".db_escape($id);
	
	$result = db_query($sql, 'could not get sales type');
	
	$row = db_fetch_row($result);
	return $row[0];
}

function delete_sales_type($id) {
	$sql="DELETE FROM ".TB_PREF."sales_types WHERE id=".db_escape($id);
	db_query($sql,"The Sales type record could not be deleted");

	$sql ="DELETE FROM ".TB_PREF."prices WHERE sales_type_id=".db_escape($id);
	db_query($sql, 'The Sales type prices could not be deleted');
}