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
$page_security = 'SA_WORKORDERANALYTIC';
$path_to_root = '../..';
include($path_to_root.'/includes/db_pager.inc');
include($path_to_root.'/includes/session.inc');

page(_($help_context = 'Inventory Item Where Used Inquiry'));

include($path_to_root.'/includes/ui.inc');

check_db_has_stock_items(_('There are no items defined in the system.'));

start_form(false, true);

if (!isset($_POST['stock_id']))
	$_POST['stock_id'] = get_global_stock_item();

echo '<center>'._('Select an item to display its parent item(s).').'&nbsp;';
echo stock_items_list('stock_id', $_POST['stock_id'], false, true);
echo '<hr></center>';

set_global_stock_item($_POST['stock_id']);

//-----------------------------------------------------------------------------

function select_link($row) {
	return  pager_link( $row['parent'].' - '.$row['description'], '/manufacturing/manage/bom_edit.php?stock_id='.$row['parent']);
}

$sql = get_sql_for_where_used(get_post('stock_id'));

   $cols = array(
	_('Parent Item') => array('fun'=>'select_link'), 
	_('Work Centre'), 
	_('Location'), 
	_('Quantity Required')
	);

$table =& new_db_pager('usage_table', $sql, $cols);

$table->width = '80%';
display_db_pager($table);

end_form();
end_page();