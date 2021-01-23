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
function add_work_centre($name, $description) {
	$sql = "INSERT INTO ".TB_PREF."workcentres (name, description)
		VALUES (".db_escape($name).",".db_escape($description).")";

	db_query($sql, 'could not add work centre');
}

function update_work_centre($type_id, $name, $description) {
	$sql = "UPDATE ".TB_PREF."workcentres SET name=".db_escape($name).", description=".db_escape($description)."
		WHERE id=".db_escape($type_id);

	db_query($sql, 'could not update work centre');
}

function get_all_work_centres($all=false) {
	$sql = "SELECT * FROM ".TB_PREF."workcentres";
	if (!$all) $sql .= " WHERE !inactive";

	return db_query($sql, 'could not get all work centres');
}

function get_work_centre($type_id) {
	$sql = "SELECT * FROM ".TB_PREF."workcentres WHERE id=".db_escape($type_id);

	$result = db_query($sql, 'could not get work centre');

	return db_fetch($result);
}

function delete_work_centre($type_id) {
	$sql="DELETE FROM ".TB_PREF."workcentres WHERE id=".db_escape($type_id);

	db_query($sql, 'could not delete work centre');
}