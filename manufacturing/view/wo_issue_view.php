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
page(_($help_context = 'View Work Order Issue'), true, false, '', $js);

include_once($path_to_root.'/includes/date_functions.inc');
include_once($path_to_root.'/includes/data_checks.inc');

include_once($path_to_root.'/manufacturing/includes/manufacturing_db.inc');
include_once($path_to_root.'/manufacturing/includes/manufacturing_ui.inc');

//-------------------------------------------------------------------------------------------------

if ($_GET['trans_no'] != '')
	$wo_issue_no = $_GET['trans_no'];

//-------------------------------------------------------------------------------------------------

function display_wo_issue($issue_no) {
	$myrow = get_work_order_issue($issue_no);

	br(1);
	start_table(TABLESTYLE);
	$th = array(_('Issue #'), _('Reference'), _('For Work Order #'), _('Item'), _('From Location'), _('To Work Centre'), _('Date of Issue'));
	table_header($th);

	start_row();
	label_cell($myrow['issue_no']);
	label_cell($myrow['reference']);
	label_cell(get_trans_view_str(ST_WORKORDER,$myrow['workorder_id']));
	label_cell($myrow['stock_id'] . ' - ' . $myrow['description']);
	label_cell($myrow['location_name']);
	label_cell($myrow['WorkCentreName']);
	label_cell(sql2date($myrow['issue_date']));
	end_row();

	comments_display_row(ST_MANUISSUE, $issue_no);

	end_table(1);

	is_voided_display(ST_MANUISSUE, $issue_no, _('This issue has been voided.'));
}

//-------------------------------------------------------------------------------------------------

function display_wo_issue_details($issue_no) {
	$result = get_work_order_issue_details($issue_no);

	if (db_num_rows($result) == 0)
		display_note(_('There are no items for this issue.'));
	else {
		start_table(TABLESTYLE);
		$th = array(_('Component'), _('Quantity'), _('Units'), _('Unit Cost'));

		table_header($th);

		$j = 1;
		$k = 0; //row colour counter

		$total_cost = 0;

		while ($myrow = db_fetch($result)) {

			alt_table_row_color($k);

			label_cell($myrow['stock_id']  . ' - ' . $myrow['description']);
			qty_cell($myrow['qty_issued'], false, get_qty_dec($myrow['stock_id']));
			label_cell($myrow['units']);
			amount_cell($myrow['unit_cost']);
			end_row();

			$j++;
			if ($j == 12) {
				$j = 1;
				table_header($th);
			}
		}

		end_table();
	}
}

//-------------------------------------------------------------------------------------------------

display_heading($systypes_array[ST_MANUISSUE] . ' # ' . $wo_issue_no);

display_wo_issue($wo_issue_no);

display_heading2(_('Items for this Issue'));

display_wo_issue_details($wo_issue_no);

echo '<br>';

end_page(true, false, false, ST_MANUISSUE, $wo_issue_no);