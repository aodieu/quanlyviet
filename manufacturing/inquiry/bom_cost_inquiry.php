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
$page_security = 'SA_WORKORDERCOST';
$path_to_root = '../..';
include_once($path_to_root.'/includes/session.inc');

page(_($help_context = 'Costed Bill Of Material Inquiry'));

include_once($path_to_root.'/manufacturing/includes/manufacturing_ui.inc');
include_once($path_to_root.'/includes/ui.inc');
include_once($path_to_root.'/includes/banking.inc');
include_once($path_to_root.'/includes/data_checks.inc');

check_db_has_bom_stock_items(_('There are no manufactured or kit items defined in the system.'));

if (isset($_GET['stock_id']))
	$_POST['stock_id'] = $_GET['stock_id'];

if (list_updated('stock_id'))
	$Ajax->activate('_page_body');

start_form(false, true);
start_table(TABLESTYLE_NOBORDER);
stock_manufactured_items_list_row(_('Select a manufacturable item:'), 'stock_id', null, false, true);
end_table();
br();
display_heading(_('All Costs Are In:').' '.get_company_currency());
display_bom($_POST['stock_id']);

end_form();

end_page();