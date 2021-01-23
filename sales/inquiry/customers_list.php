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
$page_security = 'SA_SALESORDER';
$path_to_root = '../..';
include_once($path_to_root . '/includes/session.inc');
include_once($path_to_root . '/includes/ui.inc');
include_once($path_to_root . '/sales/includes/db/customers_db.inc');

$mode = get_company_pref('no_customer_list');
if ($mode != 0)
	$js = get_js_set_combo_item();
else
	$js = get_js_select_combo_item();

page(_($help_context = 'Customers'), true, false, '', $js);

if(get_post('search'))
	$Ajax->activate('customer_tbl');

start_form(false, false, $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);

start_table(TABLESTYLE_NOBORDER);

start_row();

text_cells(_('Customer'), 'customer');
submit_cells('search', _('Search'), '', _('Search customers'), 'default');

end_row();

end_table();

end_form();

div_start('customer_tbl');

start_table(TABLESTYLE);

$th = array('', _('Customer'), _('Short Name'), _('Address'), _('Tax ID'), _('Phone'), _('Phone 2'));

table_header($th);

$k = 0;
$name = $_GET['client_id'];
$result = get_customers_search(get_post('customer'));
while ($myrow = db_fetch_assoc($result)) {
	alt_table_row_color($k);
	$value = $myrow['debtor_no'];
	if ($mode != 0) {
		$text = $myrow['name'];
		ahref_cell(_('Select'), 'javascript:void(0)', '', 'setComboItem(window.opener.document, "'.$name.'",  "'.$value.'", "'.$text.'")');
	}
	else
		ahref_cell(_('Select'), 'javascript:void(0)', '', 'selectComboItem(window.opener.document, "'.$name.'", "'.$value.'")');
	
	label_cell($myrow['name']);
	label_cell($myrow['debtor_ref']);
	label_cell($myrow['address']);
	label_cell($myrow['tax_id']);
	label_cell($myrow['phone']);
	label_cell($myrow['phone2']);
	end_row();
}

end_table(1);

div_end();

end_page(true);