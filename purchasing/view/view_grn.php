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
$page_security = 'SA_SUPPTRANSVIEW';
$path_to_root = '../..';
include($path_to_root.'/purchasing/includes/po_class.inc');

include($path_to_root.'/includes/session.inc');

$js = '';
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 500);
page(_($help_context = 'View Purchase Order Delivery'), true, false, '', $js);

include($path_to_root.'/purchasing/includes/purchasing_ui.inc');

if (!isset($_GET['trans_no']))
	die ('<BR>'._('This page must be called with a Purchase Order Delivery number to review.'));

$purchase_order = new purch_order;
read_grn($_GET['trans_no'], $purchase_order);

display_heading(_('Purchase Order Delivery').' #'.$_GET['trans_no']);
echo '<BR>';
display_grn_summary($purchase_order);

display_heading2(_('Line Details'));

start_table(TABLESTYLE, "width='90%'");
$th = array(_('Item Code'), _('Item Description'), _('Required by'), _('Quantity'), _('Unit'), _('Price'), _('Line Total'), _('Quantity Invoiced'));

table_header($th);

$total = 0;
$k = 0;  //row colour counter
$overdue_items = false;

foreach ($purchase_order->line_items as $stock_item) {

	$line_total = $stock_item->qty_received * $stock_item->price;

	// if overdue and outstanding quantities, then highlight as so
	if (date1_greater_date2($purchase_order->orig_order_date, $stock_item->req_del_date)) {
		start_row("class='overduebg'");
		$overdue_items = true;
	}
	else
		alt_table_row_color($k);

	label_cell($stock_item->stock_id);
	label_cell($stock_item->item_description);
	label_cell($stock_item->req_del_date, 'nowrap align=right');
	$dec = get_qty_dec($stock_item->stock_id);
	qty_cell($stock_item->qty_received, false, $dec);
	label_cell($stock_item->units);
	amount_decimal_cell($stock_item->price);
	amount_cell($line_total);
	qty_cell($stock_item->qty_inv, false, $dec);
	end_row();

	$total += $line_total;
}

$display_sub_tot = number_format2($total,user_price_dec());
label_row(_('Sub Total'), $display_sub_tot, 'align=right colspan=6', 'nowrap align=right', 1);

$taxes = $purchase_order->get_taxes();
$tax_total = display_edit_tax_items($taxes, 6, $purchase_order->tax_included, 1);

$display_total = price_format(($total + $tax_total));

start_row();
label_cells(_('Amount Total'), $display_total, "colspan=6 align='right'","align='right'");
label_cell('');
end_row();

end_table(1);

if ($overdue_items)
	display_note(_('Marked items were delivered overdue.'), 0, 0, "class='overduefg'");

is_voided_display(ST_SUPPRECEIVE, $_GET['trans_no'], _('This delivery has been voided.'));

end_page(true, false, false, ST_SUPPRECEIVE, $_GET['trans_no']);