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
//-------------------------------------------------------------------------------------------------------------

function add_supp_invoice_item($supp_trans_type, $supp_trans_no, $stock_id, $description, $gl_code, $unit_price, $unit_tax, $quantity, $grn_item_id, $po_detail_item_id, $memo_, $dim_id=0, $dim2_id=0) {
	$sql = "INSERT INTO ".TB_PREF."supp_invoice_items (supp_trans_type, supp_trans_no, stock_id, description, gl_code, unit_price, unit_tax, quantity,
		grn_item_id, po_detail_item_id, memo_, dimension_id, dimension2_id) ";
	$sql .= "VALUES (".db_escape($supp_trans_type).", ".db_escape($supp_trans_no).", "
		.db_escape($stock_id).
	", ".db_escape($description).", ".db_escape($gl_code).", ".db_escape($unit_price)
	.", ".db_escape($unit_tax).", ".db_escape($quantity).", ".db_escape($grn_item_id)
	.", ".db_escape($po_detail_item_id).", ".db_escape($memo_)
	.", ".db_escape($dim_id).", ".db_escape($dim2_id).")";

	db_query($sql, 'Cannot insert a supplier transaction detail record');

	return db_insert_id();
}

//-------------------------------------------------------------------------------------------------------------

function add_supp_invoice_gl_item($supp_trans_type, $supp_trans_no, $gl_code, $amount, $memo_, $dim_id=0, $dim2_id=0) {
	return add_supp_invoice_item($supp_trans_type, $supp_trans_no,  "", "", $gl_code, $amount, 0, 0, /*$grn_item_id*/-1, /*$po_detail_item_id*/0, $memo_, $dim_id, $dim2_id);
}


function get_supp_invoice_items($supp_trans_type, $supp_trans_no) {
	$sql = "SELECT inv.*, grn.*, unit_price AS FullUnitPrice, 
		stock.units,
		tax_type.exempt,
		tax_type.name as tax_type_name
		FROM "
			.TB_PREF."supp_invoice_items inv LEFT JOIN ".TB_PREF."grn_items grn ON grn.id =inv.grn_item_id
				LEFT JOIN ".TB_PREF."stock_master stock ON stock.stock_id=inv.stock_id
				LEFT JOIN ".TB_PREF."item_tax_types tax_type ON stock.tax_type_id=tax_type.id
		WHERE supp_trans_type = ".db_escape($supp_trans_type)."
			AND supp_trans_no = ".db_escape($supp_trans_no)
			." ORDER BY inv.id";
	return db_query($sql, 'Cannot retreive supplier transaction detail records');
}

function void_supp_invoice_items($type, $type_no) {
	$sql = "UPDATE ".TB_PREF."supp_invoice_items SET quantity=0, unit_price=0
		WHERE supp_trans_type = ".db_escape($type)." AND supp_trans_no=".db_escape($type_no);
	db_query($sql, 'could not void supptrans details');
}