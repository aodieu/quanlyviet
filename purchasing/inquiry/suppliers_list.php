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

$page_security = 'SA_PURCHASEORDER';
$path_to_root = '../..';
include_once($path_to_root.'/includes/session.inc');
include_once($path_to_root.'/includes/ui.inc');
include_once($path_to_root.'/purchasing/includes/db/suppliers_db.inc');

$mode = get_company_pref('no_supplier_list');
if ($mode != 0)
	$js = get_js_set_combo_item();
else
	$js = get_js_select_combo_item();

page(_($help_context = 'Suppliers'), true, false, '', $js);

if(get_post('search'))
	$Ajax->activate('supplier_tbl');

start_form(false, $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);

start_table(TABLESTYLE_NOBORDER);

start_row();

text_cells(_('Supplier'), 'supplier');
submit_cells('search', _('Search'), '', _('Search suppliers'), 'default');

end_row();

end_table();

end_form();
div_start('supplier_tbl');

start_table(TABLESTYLE);

$th = array('', _('Supplier'), _('Short Name'), _('Address'), _('Tax ID'), _('Phone'), _('Phone 2'));

table_header($th);

$k = 0;
$result = get_suppliers_search(get_post('supplier'));
while ($myrow = db_fetch_assoc($result)) {
	alt_table_row_color($k);
	
	if ($mode != 0)
		ahref_cell(_('Select'), 'javascript:void(0)', '', 'setComboItem(window.opener.document, "'.$_GET['client_id'].'",  "'.$myrow['supplier_id'].'", "'.$myrow['supp_name'].'")');
	else
		ahref_cell(_('Select'), 'javascript:void(0)', '', 'selectComboItem(window.opener.document, "'.$_GET['client_id'].'", "'.$myrow['supplier_id'].'")');
	
	label_cell($myrow['supp_name']);
	label_cell($myrow['supp_ref']);
	label_cell($myrow['address']);
	label_cell($myrow['gst_no']);
	label_cell($myrow['phone']);
	label_cell($myrow['phone2']);
	end_row();
}

end_table(1);
div_end();
end_page(true);
