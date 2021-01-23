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
//----------------------------------------------------------------------------------------

function get_customer_trans_details($debtor_trans_type, $debtor_trans_no) {
	if (!is_array($debtor_trans_no))
		$debtor_trans_no = array( 0=>$debtor_trans_no );

	$sql = "SELECT line.*,
		line.unit_price+line.unit_tax AS FullUnitPrice,
		line.unit_price AS UnitPriceExTax,  
		line.description AS StockDescription,
		item.long_description AS StockLongDescription,
		item.units, item.mb_flag
		FROM "
			.TB_PREF."debtor_trans_details line,"
			.TB_PREF."stock_master item
		WHERE (";

	$tr=array();
	foreach ($debtor_trans_no as $trans_no)
		$tr[] = 'debtor_trans_no='.db_escape($trans_no);

	$sql .= implode(' OR ', $tr);


	$sql.=	") AND debtor_trans_type=".db_escape($debtor_trans_type)."
		AND item.stock_id=line.stock_id
		ORDER BY id";
	return db_query($sql, 'The debtor transaction detail could not be queried');
}

//----------------------------------------------------------------------------------------

function void_customer_trans_details($type, $type_no) {
	$sql = "UPDATE ".TB_PREF."debtor_trans_details SET quantity=0, unit_price=0,
		unit_tax=0, discount_percent=0, standard_cost=0, src_id=0
		WHERE debtor_trans_no=".db_escape($type_no)."
		AND debtor_trans_type=".db_escape($type);

	db_query($sql, 'The debtor transaction details could not be voided');

	// clear the stock move items
	void_stock_move($type, $type_no);
}

//----------------------------------------------------------------------------------------

function write_customer_trans_detail_item($debtor_trans_type, $debtor_trans_no, $stock_id, $description, $quantity, $unit_price, $unit_tax, $discount_percent, $std_cost, $src_id, $line_id=0) {
	if ($line_id!=0)
		$sql = "UPDATE ".TB_PREF."debtor_trans_details SET
			stock_id=".db_escape($stock_id).",
			description=".db_escape($description).",
			quantity=$quantity,
			unit_price=$unit_price,
			unit_tax=$unit_tax,
			discount_percent=$discount_percent,
			standard_cost=$std_cost,
			src_id=".db_escape($src_id)." WHERE
			id=".db_escape($line_id);
	else
		$sql = "INSERT INTO ".TB_PREF."debtor_trans_details (debtor_trans_no,
			debtor_trans_type, stock_id, description, quantity, unit_price,
			unit_tax, discount_percent, standard_cost, src_id)
			VALUES (".db_escape($debtor_trans_no).", ".db_escape($debtor_trans_type).", ".db_escape($stock_id).
			", ".db_escape($description).",
			$quantity, $unit_price, $unit_tax, 
			$discount_percent, $std_cost,".db_escape($src_id).")";

	db_query($sql, 'The debtor transaction detail could not be written');
}