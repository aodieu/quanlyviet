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

include_once($path_to_root.'/purchasing/includes/purchasing_db.inc');
include_once($path_to_root.'/includes/ui/items_cart.inc');
include_once($path_to_root.'/includes/session.inc');

include_once($path_to_root.'/purchasing/includes/purchasing_ui.inc');

$js = '';
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 500);
page(_($help_context = 'View Supplier Invoice'), true, false, '', $js);

if (isset($_GET['trans_no']))
	$trans_no = $_GET['trans_no'];
elseif (isset($_POST['trans_no']))
	$trans_no = $_POST['trans_no'];

$supp_trans = new supp_trans(ST_SUPPINVOICE);

read_supp_invoice($trans_no, ST_SUPPINVOICE, $supp_trans);

$supplier_curr_code = get_supplier_currency($supp_trans->supplier_id);

display_heading(_('SUPPLIER INVOICE').' # '.$trans_no);
echo '<br>';

start_table(TABLESTYLE, "width='95%'");
start_row();
label_cells(_('Supplier'), $supp_trans->supplier_name, "class='tableheader2'");
label_cells(_('Reference'), $supp_trans->reference, "class='tableheader2'");
label_cells(_("Supplier's Reference"), $supp_trans->supp_reference, "class='tableheader2'");
end_row();
start_row();
label_cells(_('Invoice Date'), $supp_trans->tran_date, "class='tableheader2'");
label_cells(_('Due Date'), $supp_trans->due_date, "class='tableheader2'");
if (!is_company_currency($supplier_curr_code))
	label_cells(_('Currency'), $supplier_curr_code, "class='tableheader2'");
end_row();
comments_display_row(ST_SUPPINVOICE, $trans_no);

end_table(1);

$total_gl = display_gl_items($supp_trans, 2);
$total_grn = display_grn_items($supp_trans, 2);

$display_sub_tot = number_format2($total_gl+$total_grn,user_price_dec());

start_table(TABLESTYLE, "width='95%'");
label_row(_('Sub Total'), $display_sub_tot, 'align=right', "nowrap align=right width='15%'");

$tax_items = get_trans_tax_details(ST_SUPPINVOICE, $trans_no);
display_supp_trans_tax_details($tax_items, 1);

$display_total = number_format2($supp_trans->ov_amount + $supp_trans->ov_gst,user_price_dec());

label_row(_('TOTAL INVOICE').' ('.$supplier_curr_code.')', $display_total, 'colspan=1 align=right', 'nowrap align=right');

end_table(1);

$voided = is_voided_display(ST_SUPPINVOICE, $trans_no, _('This invoice has been voided.'));

if (!$voided)
	display_allocations_to(PT_SUPPLIER, $supp_trans->supplier_id, ST_SUPPINVOICE, $trans_no, ($supp_trans->ov_amount + $supp_trans->ov_gst));

end_page(true, false, false, ST_SUPPINVOICE, $trans_no);