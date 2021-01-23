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

function get_fixed_asset_classes() {
	$sql="SELECT * FROM ".TB_PREF."stock_fa_class";

	$result = db_query($sql, 'fixed asset classes could not be retrieved');

	return $result;
}

function get_fixed_asset_classname($class) {
	$sql="SELECT description FROM ".TB_PREF."stock_fa_class WHERE fa_class_id = ".db_escape($class);

	$result = db_query($sql, 'fixed asset class name could not be retrieved');

	$row = db_fetch_row($result);
	
	return $row[0];
}

function update_fixed_asset_class($fa_class_id, $parent_id, $description, $long_description, $depreciation_rate) {
	$sql = "UPDATE ".TB_PREF."stock_fa_class SET
		parent_id=".db_escape($parent_id).",
		description=".db_escape($description).",
		long_description=".db_escape($long_description).",
	depreciation_rate=".db_escape($depreciation_rate)."
	WHERE fa_class_id=".db_escape($fa_class_id);

	db_query($sql, 'The fixed asset class could not be updated');
}

function add_fixed_asset_class($fa_class_id, $parent_id, $description, $long_description, $depreciation_rate) {
	$sql = "INSERT INTO ".TB_PREF."stock_fa_class (fa_class_id, parent_id, description, long_description,
	depreciation_rate) VALUES ("
	.db_escape($fa_class_id).", ".db_escape($parent_id).", "
	.db_escape($description).", ".db_escape($long_description).", "
	.db_escape($depreciation_rate).")";

	db_query($sql, 'The fixed asset class could not be added');
}

function delete_fixed_asset_class($fa_class_id) {
	$sql = "DELETE FROM ".TB_PREF."stock_fa_class WHERE fa_class_id=".db_escape($fa_class_id);

	db_query($sql, 'The fixed asset class could not be deleted');
}
