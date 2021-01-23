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

function get_comments($type, $type_no) {
	$sql = "SELECT * FROM ".TB_PREF."comments WHERE type=".db_escape($type)." AND id=".db_escape($type_no);

	return db_query($sql, 'could not query comments transaction table');
}

//--------------------------------------------------------------------------------------------------

function add_comments($type, $type_no, $date_, $memo_) {
	if ($memo_ != null && $memo_ != '') {
		$date = date2sql($date_);
		$sql = "INSERT INTO ".TB_PREF."comments (type, id, date_, memo_) VALUES (".db_escape($type).", ".db_escape($type_no).", '$date', ".db_escape($memo_).")";

		db_query($sql, 'could not add comments transaction entry');
	}
}

//--------------------------------------------------------------------------------------------------

function update_comments($type, $id, $date_, $memo_) {
	if ($date_ == null) {
		delete_comments($type, $id);
		add_comments($type, $id, Today(), $memo_);
	}
	else {
		$date = date2sql($date_);
		$sql = "UPDATE ".TB_PREF."comments SET memo_=".db_escape($memo_)
			." WHERE type=".db_escape($type)." AND id=".db_escape($id)
			." AND date_='$date'";
		db_query($sql, 'could not update comments');
	}
}

//--------------------------------------------------------------------------------------------------

function delete_comments($type, $type_no) {
	$sql = "DELETE FROM ".TB_PREF."comments WHERE type=".db_escape($type)." AND id=".db_escape($type_no);

	db_query($sql, 'could not delete from comments transaction table');
}