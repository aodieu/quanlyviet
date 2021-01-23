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

$page_security = 'SA_GLACCOUNT';
$path_to_root = '../..';
include_once($path_to_root.'/includes/session.inc');
include_once($path_to_root.'/includes/ui.inc');
include_once($path_to_root.'/gl/includes/db/gl_db_accounts.inc');

$js = get_js_select_combo_item();

page(_($help_context = 'GL Accounts'), true, false, '', $js);

if(get_post('search'))
	$Ajax->activate('account_tbl');

// Filter form. Use query string so the client_id will not disappear
// after ajax form post.
start_form(false, false, $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);

start_table(TABLESTYLE_NOBORDER);

start_row();

text_cells(_('Description'), 'description');
submit_cells('search', _('Search'), '', _('Search GL accounts'), 'default');

end_row();

end_table();

end_form();

div_start('account_tbl');

start_table(TABLESTYLE);

$th = array('', _('Account Code'), _('Description'), _('Category'));

table_header($th);

$k = 0;
$name = $_GET['client_id'];
$skip = $_GET['skip'];

$result = get_chart_accounts_search(get_post('description'), $skip);
while ($myrow = db_fetch_assoc($result)) {
	alt_table_row_color($k);
	$value = $myrow['account_code'];
	ahref_cell(_('Select'), 'javascript:void(0)', '', 'selectComboItem(window.opener.document, "'.$name.'", "'.$value.'")');
	label_cell($myrow['account_code']);
	label_cell($myrow['account_name']);
	label_cell($myrow['name']);
	end_row();
}

end_table(1);

div_end();
end_page(true);