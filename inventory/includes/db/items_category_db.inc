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
function add_item_category($description, $tax_type_id, $sales_account, $cogs_account, $inventory_account, $adjustment_account, $wip_account, $units, $mb_flag, $dim1, $dim2, $no_sale, $no_purchase) {
	$sql = "INSERT INTO ".TB_PREF."stock_category (description, dflt_tax_type,
			dflt_units, dflt_mb_flag, dflt_sales_act, dflt_cogs_act, 
			dflt_inventory_act, dflt_adjustment_act, dflt_wip_act, 
			dflt_dim1, dflt_dim2, dflt_no_sale, dflt_no_purchase)
		VALUES ("
		.db_escape($description).","
		.db_escape($tax_type_id).","
		.db_escape($units).","
		.db_escape($mb_flag).","
		.db_escape($sales_account).","
		.db_escape($cogs_account).","
		.db_escape($inventory_account).","
		.db_escape($adjustment_account).","
		.db_escape($wip_account).","
		.db_escape($dim1).","
		.db_escape($dim2).","
		.db_escape($no_sale).","
		.db_escape($no_purchase).")";

	db_query($sql, 'an item category could not be added');
}

function update_item_category($id, $description, $tax_type_id, $sales_account, $cogs_account, $inventory_account, $adjustment_account, $wip_account, $units, $mb_flag, $dim1, $dim2, $no_sale, $no_purchase) {
	$sql = "UPDATE ".TB_PREF."stock_category SET "
		."description = ".db_escape($description).","
		."dflt_tax_type = ".db_escape($tax_type_id).","
		."dflt_units = ".db_escape($units).","
		."dflt_mb_flag = ".db_escape($mb_flag).","
		."dflt_sales_act = ".db_escape($sales_account).","
		."dflt_cogs_act = ".db_escape($cogs_account).","
		."dflt_inventory_act = ".db_escape($inventory_account).","
		."dflt_adjustment_act = ".db_escape($adjustment_account).","
		."dflt_wip_act = ".db_escape($wip_account).","
		."dflt_dim1 = ".db_escape($dim1).","
		."dflt_dim2 = ".db_escape($dim2).","
		."dflt_no_sale = ".db_escape($no_sale).","
		."dflt_no_purchase = ".db_escape($no_purchase)
		."WHERE category_id = ".db_escape($id);

	db_query($sql, 'an item category could not be updated');
}

function delete_item_category($id) {
	$sql="DELETE FROM ".TB_PREF."stock_category WHERE category_id=".db_escape($id);

	db_query($sql, 'an item category could not be deleted');
}

function get_item_categories($show_inactive, $fixed_asset=false) {
	$sql = "SELECT c.*, t.name as tax_name FROM ".TB_PREF."stock_category c, "
		.TB_PREF."item_tax_types t WHERE c.dflt_tax_type=t.id";
	if (!$show_inactive)
		$sql .= " AND !c.inactive";
	if ($fixed_asset)
		$sql .= " AND c.dflt_mb_flag='F'";
	else
		$sql .= " AND c.dflt_mb_flag!='F'";

	$sql .= " ORDER by description";

	return db_query($sql, 'could not get stock categories');
}

function get_item_category($id) {
	$sql="SELECT * FROM ".TB_PREF."stock_category WHERE category_id=".db_escape($id);

	$result = db_query($sql, 'an item category could not be retrieved');

	return db_fetch($result);
}

function get_category_name($id) {
	$sql = "SELECT description FROM ".TB_PREF."stock_category WHERE category_id=".db_escape($id);

	$result = db_query($sql, 'could not get sales type');

	$row = db_fetch_row($result);
	return $row[0];
}