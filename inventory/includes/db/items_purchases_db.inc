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

function add_item_purchasing_data($supplier_id, $stock_id, $price, $suppliers_uom, $conversion_factor, $supplier_description) {
	$supp_desc = db_escape($supplier_description);
	if (strlen($supp_desc) > 50) // to be fixed and removed in 2.5
		$supp_desc = substr($supp_desc, 0, 50)."'";
	$sql = "INSERT INTO ".TB_PREF."purch_data (supplier_id, stock_id, price, suppliers_uom,
		conversion_factor, supplier_description) VALUES (";
	$sql .= db_escape($supplier_id).", ".db_escape($stock_id). ", "
		.$price . ", ".db_escape($suppliers_uom ). ", "
		.$conversion_factor . ", "
		.$supp_desc . ")";

	db_query($sql, 'The supplier purchasing details could not be added');
}

function update_item_purchasing_data($selected_id, $stock_id, $price, $suppliers_uom, $conversion_factor, $supplier_description) {
	$supp_desc = db_escape($supplier_description);
	if (strlen($supp_desc) > 50) // to be fixed and removed in 2.5
		$supp_desc = substr($supp_desc, 0, 50) ."'";
	$sql = "UPDATE ".TB_PREF."purch_data SET price=" . $price . ",
		suppliers_uom=".db_escape($suppliers_uom) . ",
		conversion_factor=" . $conversion_factor . ",
		supplier_description=" . $supp_desc . "
		WHERE stock_id=".db_escape($stock_id) . " AND
		supplier_id=".db_escape($selected_id);
	db_query($sql, 'The supplier purchasing details could not be updated');
}

function delete_item_purchasing_data($selected_id, $stock_id) {
	$sql = "DELETE FROM ".TB_PREF."purch_data WHERE supplier_id=".db_escape($selected_id)."
		AND stock_id=".db_escape($stock_id);
	db_query($sql,'could not delete purchasing data');
}

function get_items_purchasing_data($stock_id) {
	$sql = "SELECT pdata.*, supplier.supp_name, supplier.curr_code
		FROM ".TB_PREF."purch_data pdata
			INNER JOIN ".TB_PREF."suppliers supplier ON pdata.supplier_id=supplier.supplier_id
		WHERE stock_id = ".db_escape($stock_id);

	return db_query($sql, 'The supplier purchasing details for the selected part could not be retrieved');
}

function get_item_purchasing_data($selected_id, $stock_id) {
	$sql = "SELECT pdata.*, supplier.supp_name
		FROM ".TB_PREF."purch_data pdata
			INNER JOIN ".TB_PREF."suppliers supplier ON pdata.supplier_id=supplier.supplier_id
		WHERE pdata.supplier_id=".db_escape($selected_id)."
			AND pdata.stock_id=".db_escape($stock_id);

	$result = db_query($sql, 'The supplier purchasing details for the selected supplier and item could not be retrieved');

	return db_fetch($result);
}