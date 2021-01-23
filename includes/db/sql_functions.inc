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
//
//	General database functions common for all modules.
//
//-------------------------------------------------------------------
// Multilevel transaction control.
//
$transaction_level = 0;

function begin_transaction() {
	global $transaction_level;

	if (!$transaction_level) {
		db_query("BEGIN", 'could not start a transaction');
	}
	$transaction_level++;
}

function commit_transaction() {
	global $transaction_level;

	$transaction_level--;

	if (!$transaction_level)
		db_query("COMMIT", 'could not commit a transaction');
}

/*
	This function is called on end of script execution to cancel
	all aborted transactions (if any)
*/
function cancel_transaction() {
	global $transaction_level;

	if ($transaction_level)
		db_query("ROLLBACK", 'could not cancel a transaction');	
	
	$transaction_level = 0;
}

//-----------------------------------------------------------------------------
//	Update record activity status.
//
function update_record_status($id, $status, $table, $key) {
	$sql = "UPDATE ".TB_PREF.$table." SET inactive = ".((int)$status)." WHERE $key=".db_escape($id);
		
	db_query($sql, "Can't update record status");
}
//-----------------------------------------------------------------------------
//
//	Helper for sql subquery returning running totals from delta tables like stock_moves or bank_trans
//
//	$table - table name with optional WHERE clause
//  $column - delta column
//	$index  - comma delimited list of columns for total grouping and order
//  Returns running totals with respective index column
//
function running_total_sql($table, $column, $index) {

	return "SELECT daily.$index, daily.$column, (@total:=@total+daily.$column) total 
		FROM
		(SELECT $index, sum($column) $column FROM $table GROUP BY $index ORDER BY $index) daily,
		(SELECT @total:=0) total_var";
}