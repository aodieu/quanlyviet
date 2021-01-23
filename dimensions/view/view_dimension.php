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
$page_security = 'SA_DIMTRANSVIEW';
$path_to_root = '../..';

include_once($path_to_root.'/includes/session.inc');

$js = '';
if (user_use_date_picker())
	$js .= get_js_date_picker();
page(_($help_context = 'View Dimension'), true, false, '', $js);

include_once($path_to_root.'/includes/date_functions.inc');
include_once($path_to_root.'/includes/data_checks.inc');

include_once($path_to_root.'/dimensions/includes/dimensions_db.inc');
include_once($path_to_root.'/dimensions/includes/dimensions_ui.inc');

//-------------------------------------------------------------------------------------------------

if (isset($_GET['trans_no']) && $_GET['trans_no'] != '')
	$id = $_GET['trans_no'];

if (isset($_POST['Show'])) {
	$id = $_POST['trans_no'];
	$Ajax->activate('_page_body');
}

display_heading($systypes_array[ST_DIMENSION] . ' # ' . $id);

br(1);
$myrow = get_dimension($id, true);

if ($myrow == false) {
	echo _('The dimension number sent is not valid.');
	exit;
}

start_table(TABLESTYLE);

$th = array(_('#'), _('Reference'), _('Name'), _('Type'), _('Date'), _('Due Date'));
table_header($th);

start_row();
label_cell($myrow['id']);
label_cell($myrow['reference']);
label_cell($myrow['name']);
label_cell($myrow['type_']);
label_cell(sql2date($myrow['date_']));
label_cell(sql2date($myrow['due_date']));
end_row();

comments_display_row(ST_DIMENSION, $id);

end_table();

if ($myrow['closed'] == true)
	display_note(_('This dimension is closed.'));

start_form();

start_table(TABLESTYLE_NOBORDER);
start_row();

if (!isset($_POST['TransFromDate']))
	$_POST['TransFromDate'] = begin_fiscalyear();
if (!isset($_POST['TransToDate']))
	$_POST['TransToDate'] = Today();
date_cells(_('from:'), 'TransFromDate');
date_cells(_('to:'), 'TransToDate');
submit_cells('Show',_('Show'), '', false, 'default');

end_row();

end_table();
hidden('trans_no', $id);
end_form();

display_dimension_balance($id, $_POST['TransFromDate'], $_POST['TransToDate']);

br(1);

end_page(true, false, false, ST_DIMENSION, $id);

