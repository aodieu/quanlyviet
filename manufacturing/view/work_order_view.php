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

include_once($path_to_root.'/includes/date_functions.inc');
include_once($path_to_root.'/includes/data_checks.inc');

include_once($path_to_root.'/manufacturing/includes/manufacturing_db.inc');
include_once($path_to_root.'/manufacturing/includes/manufacturing_ui.inc');
$js = '';
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(800, 500);
page(_($help_context = 'View Work Order'), true, false, '', $js);

//-------------------------------------------------------------------------------------------------
$woid = 0;
if ($_GET['trans_no'] != '')
	$woid = $_GET['trans_no'];

display_heading($systypes_array[ST_WORKORDER] . ' # ' . $woid);

br(1);
$myrow = get_work_order($woid);

if ($myrow['type']  == WO_ADVANCED)
	display_wo_details($woid, true);
else
	display_wo_details_quick($woid, true);

echo '<center>';

// display the WO requirements
br(1);
if ($myrow['released'] == false) {
	display_heading2(_('BOM for item:') . ' ' . $myrow['StockItemName']);
	display_bom($myrow['stock_id']);
}
else {
	display_heading2(_('Work Order Requirements'));
	display_wo_requirements($woid, $myrow['units_reqd']);
	if ($myrow['type'] == WO_ADVANCED) {
		echo '<br><table cellspacing=7><tr valign=top><td>';
		display_heading2(_('Issues'));
		display_wo_issues($woid);
		echo '</td><td>';
		display_heading2(_('Productions'));
		display_wo_productions($woid);
		echo '</td><td>';
		display_heading2(_('Additional Costs'));
		display_wo_payments($woid);
		echo '</td></tr></table>';
	}
	else {
		echo '<br><table cellspacing=7><tr valign=top><td>';
		display_heading2(_('Additional Costs'));
		display_wo_payments($woid);
		echo '</td></tr></table>';
	}
}

echo '<br></center>';

is_voided_display(ST_WORKORDER, $woid, _('This work order has been voided.'));

end_page(true, false, false, ST_WORKORDER, $woid);