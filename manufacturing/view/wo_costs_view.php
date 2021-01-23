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
$page_security = 'SA_MANUFTRANSVIEW';
$path_to_root = '../..';

include_once($path_to_root.'/includes/session.inc');

$js = '';
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 500);
page(_($help_context = 'View Work Order Costs'), true, false, '', $js);

include_once($path_to_root.'/includes/date_functions.inc');
include_once($path_to_root.'/includes/data_checks.inc');

include_once($path_to_root.'/manufacturing/includes/manufacturing_db.inc');
include_once($path_to_root.'/manufacturing/includes/manufacturing_ui.inc');

//-------------------------------------------------------------------------------------------------

if ($_GET['trans_no'] != '')
	$wo_id = $_GET['trans_no'];

//-------------------------------------------------------------------------------------------------
function print_gl_rows($result, $title) {
	global $systypes_array;

	if (db_num_rows($result)) {
		table_section_title($title, 7);
		while($myrow = db_fetch($result)) {
			start_row();
			label_cell(sql2date($myrow['tran_date']));
			label_cell(get_trans_view_str($myrow['type'],$myrow['type_no'], $systypes_array[$myrow['type']]. ' '.$myrow['type_no']));
			label_cell($myrow['account']);
			label_cell($myrow['account_name']);
			display_debit_or_credit_cells($myrow['amount']);
			label_cell($myrow['memo_']);
			end_row();
		}
	}
}
function display_wo_costs($prod_id) {
	br(1);
	start_table(TABLESTYLE);

	$th = array(_('Date'), _('Transaction'), _('Account Code'), _('Account Name'), _('Debit'), _('Credit'), _('Memo'));

	table_header($th);

	$productions = get_gl_wo_productions($prod_id, true);
	print_gl_rows($productions, _('Finished Product Requirements'));

	$issues = get_gl_wo_issue_trans($prod_id, -1, true);
	print_gl_rows($issues, _('Additional Material Issues'));

	$costs = get_gl_wo_cost_trans($prod_id, -1, true);
	print_gl_rows($costs, _('Additional Costs'));

	$wo = get_gl_trans(ST_WORKORDER, $prod_id);
	print_gl_rows($wo, _('Finished Product Receival'));
	end_table(1);
}

//-------------------------------------------------------------------------------------------------

display_heading(sprintf(_('Production Costs for Work Order # %d'), $wo_id));

display_wo_details($wo_id, true);

display_wo_costs($wo_id);

br(2);

end_page(true, false, false, ST_WORKORDER, $wo_id);