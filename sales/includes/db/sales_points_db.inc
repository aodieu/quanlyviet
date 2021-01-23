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
function add_sales_point($name, $location, $account, $cash, $credit) {
	$sql = "INSERT INTO ".TB_PREF."sales_pos (pos_name, pos_location, pos_account, cash_sale, credit_sale) VALUES (".db_escape($name)
			. ",".db_escape($location).",".db_escape($account)
			. ",$cash,$credit)";
	db_query($sql, 'could not add point of sale');
}

function update_sales_point($id, $name, $location, $account, $cash, $credit) {

	$sql = "UPDATE ".TB_PREF."sales_pos SET pos_name=".db_escape($name)
				.",pos_location=".db_escape($location)
				.",pos_account=".db_escape($account)
				.",cash_sale =$cash"
				.",credit_sale =$credit"
				." WHERE id = ".db_escape($id);
	
	db_query($sql, 'could not update sales type');			
}

function get_all_sales_points($all=false) {
	$sql = "SELECT pos.*, loc.location_name, acc.bank_account_name FROM "
		.TB_PREF."sales_pos as pos
		LEFT JOIN ".TB_PREF."locations as loc on pos.pos_location=loc.loc_code
		LEFT JOIN ".TB_PREF."bank_accounts as acc on pos.pos_account=acc.id";
	if (!$all) $sql .= " WHERE !pos.inactive";
	
	return db_query($sql, 'could not get all POS definitions');
} 

function get_sales_point($id) {
	$sql = "SELECT pos.*, loc.location_name, acc.bank_account_name FROM "
		.TB_PREF."sales_pos as pos
		LEFT JOIN ".TB_PREF."locations as loc on pos.pos_location=loc.loc_code
		LEFT JOIN ".TB_PREF."bank_accounts as acc on pos.pos_account=acc.id
		WHERE pos.id=".db_escape($id);
	
	$result = db_query($sql, 'could not get POS definition');
	
	return db_fetch($result);
}

function get_sales_point_name($id) {
	$sql = "SELECT pos_name FROM ".TB_PREF."sales_pos WHERE id=".db_escape($id);
	
	$result = db_query($sql, 'could not get POS name');
	
	$row = db_fetch_row($result);
	return $row[0];
}

function delete_sales_point($id) {
	$sql="DELETE FROM ".TB_PREF."sales_pos WHERE id=".db_escape($id);
	db_query($sql, 'The point of sale record could not be deleted');
}