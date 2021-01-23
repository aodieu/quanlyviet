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
$page_security = 'SA_ITEMSTRANSVIEW';
$path_to_root = '../..';

include($path_to_root.'/includes/session.inc');

page(_($help_context = 'View Inventory Adjustment'), true);

include_once($path_to_root.'/includes/date_functions.inc');
include_once($path_to_root.'/includes/ui.inc');
include_once($path_to_root.'/inventory/includes/inventory_db.inc');

if (isset($_GET['trans_no']))
	$trans_no = $_GET['trans_no'];

display_heading($systypes_array[ST_INVADJUST] . " #$trans_no");

br(1);
$adjustment_items = get_stock_adjustment_items($trans_no);
$k = 0;
$header_shown = false;
while ($adjustment = db_fetch($adjustment_items)) {

	if (!$header_shown) {

		start_table(TABLESTYLE2, "width='90%'");
		start_row();
		label_cells(_('At Location'), $adjustment['location_name'], "class='tableheader2'");
		label_cells(_('Reference'), $adjustment['reference'], "class='tableheader2'", "colspan=6");
		label_cells(_('Date'), sql2date($adjustment['tran_date']), "class='tableheader2'");
		end_row();
		comments_display_row(ST_INVADJUST, $trans_no);

		end_table();
		$header_shown = true;

		echo '<br>';
		start_table(TABLESTYLE, "width='90%'");

		$th = array(_('Item Code'), _('Description'), _('Quantity'), _('Units'), _('Unit Cost'));
		table_header($th);
	}

	alt_table_row_color($k);

	label_cell($adjustment['stock_id']);
	label_cell($adjustment['description']);
	qty_cell($adjustment['qty'], false, get_qty_dec($adjustment['stock_id']));
	label_cell($adjustment['units']);
	amount_decimal_cell($adjustment['standard_cost']);
	end_row();
}

end_table(1);

is_voided_display(ST_INVADJUST, $trans_no, _('This adjustment has been voided.'));

end_page(true, false, false, ST_INVADJUST, $trans_no);