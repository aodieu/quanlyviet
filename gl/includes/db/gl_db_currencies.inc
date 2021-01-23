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
//---------------------------------------------------------------------------------------------

function update_currency($curr_abrev, $symbol, $currency, $country, $hundreds_name, $auto_update) {
	$sql = "UPDATE ".TB_PREF."currencies SET currency=".db_escape($currency)
		.", curr_symbol=".db_escape($symbol).",	country=".db_escape($country)
		.", hundreds_name=".db_escape($hundreds_name)
		.",auto_update = ".db_escape($auto_update)
		." WHERE curr_abrev = ".db_escape($curr_abrev);

	db_query($sql, "could not update currency for $curr_abrev");
}

//---------------------------------------------------------------------------------------------

function add_currency($curr_abrev, $symbol, $currency, $country, $hundreds_name, $auto_update) {
	$sql = "INSERT INTO ".TB_PREF."currencies (curr_abrev, curr_symbol, currency, 
		country, hundreds_name, auto_update)
		VALUES (".db_escape($curr_abrev).", ".db_escape($symbol).", "
		.db_escape($currency).", ".db_escape($country).", "
		.db_escape($hundreds_name).",".db_escape($auto_update).")";

	db_query($sql, "could not add currency for $curr_abrev");
}

//---------------------------------------------------------------------------------------------

function delete_currency($curr_code) {
	$sql="DELETE FROM ".TB_PREF."currencies WHERE curr_abrev=".db_escape($curr_code);
	db_query($sql, "could not delete currency $curr_code");

	$sql="DELETE FROM ".TB_PREF."exchange_rates WHERE curr_code='$curr_code'";
	db_query($sql, "could not delete exchange rates for currency $curr_code");
}

//---------------------------------------------------------------------------------------------

function get_currency($curr_code) {
	$sql = "SELECT * FROM ".TB_PREF."currencies WHERE curr_abrev=".db_escape($curr_code);
	$result = db_query($sql, "could not get currency $curr_code");

	$row = db_fetch($result);
	return $row;
}

//---------------------------------------------------------------------------------------------

function get_currencies($all=false) {
	$sql = "SELECT * FROM ".TB_PREF."currencies";
	if (!$all) $sql .= " WHERE !inactive";
	return db_query($sql, 'could not get currencies');
}