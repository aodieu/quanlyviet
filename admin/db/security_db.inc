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
//--------------------------------------------------------------------------------------------------

function get_security_role($id) {
	$sql = "SELECT * FROM ".TB_PREF."security_roles WHERE id=".(int)$id;
	$ret = db_query($sql, 'could not retrieve security roles');
	$row = db_fetch($ret);
	if ($row != false) {
		$row['areas'] = explode(';', $row['areas']);
		$row['sections'] = explode(';', $row['sections']);
	}
	return $row;
}

//--------------------------------------------------------------------------------------------------

function add_security_role($name, $description, $sections, $areas) {
	$sql = "INSERT INTO ".TB_PREF."security_roles (role, description, sections, areas)
	VALUES ("
	.db_escape($name).","
	.db_escape($description).","
	.db_escape(implode(';', $sections)).","
	.db_escape(implode(';', $areas)).")";

	db_query($sql, 'could not add new security role');
}

//--------------------------------------------------------------------------------------------------

function update_security_role($id, $name, $description, $sections, $areas) {
	$sql = "UPDATE ".TB_PREF."security_roles SET role=".db_escape($name).",description=".db_escape($description).",sections=".db_escape(implode(';', $sections)).",areas=".db_escape(implode(';', $areas))." WHERE id=".(int)$id;
	db_query($sql, 'could not update role');
}

//--------------------------------------------------------------------------------------------------

function delete_security_role($id) {
	$sql = "DELETE FROM ".TB_PREF."security_roles WHERE id=".(int)$id;

	db_query($sql, 'could not delete role');
}

//--------------------------------------------------------------------------------------------------

function check_role_used($id) {
	$sql = "SELECT count(*) FROM ".TB_PREF."users WHERE role_id=".(int)$id;
	$ret = db_query($sql, 'cannot check role usage');
	$row = db_fetch($ret);
	return $row[0];
}