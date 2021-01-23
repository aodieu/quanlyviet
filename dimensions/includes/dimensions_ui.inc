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
include_once($path_to_root . '/includes/ui.inc');

//--------------------------------------------------------------------------------------

function display_dimension_balance($id, $from, $to) {
	global $path_to_root;

	$result = get_dimension_balance_all($id, $from, $to);

	if (db_num_rows($result) == 0)
		display_note(_('There are no transactions for this dimension for the selected period.'));
	else {
		display_heading2(_('Balance for this Dimension'));
		br();
		start_table(TABLESTYLE);
		$th = array(_('Account'), _('Debit'), _('Credit'));
		table_header($th);

		$total = $k = 0;
		while ($myrow = db_fetch($result)) {
			alt_table_row_color($k);

			label_cell($myrow['account'].' '.$myrow['account_name']);
			display_debit_or_credit_cells($myrow['amt']);
			$total += $myrow['amt'];
			end_row();
		}
		start_row();
		label_cell('<b>'._('Balance').'</b>');
		if ($total >= 0) {
			amount_cell($total, true);
			label_cell('');
		}
		else {
			label_cell('');
			amount_cell(abs($total), true);
		}
		end_row();

		end_table();
	}
}


