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
include_once($path_to_root.'/includes/db_pager.inc');
include_once($path_to_root.'/includes/session.inc');

include_once($path_to_root.'/purchasing/includes/purchasing_ui.inc');
include_once($path_to_root.'/reporting/includes/reporting.inc');

$js = '';
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 500);
if (user_use_date_picker())
	$js .= get_js_date_picker();
page(_($help_context = 'Search Purchase Orders'), false, false, '', $js);

//---------------------------------------------------------------------------------------------

function trans_view($trans) {
	return get_trans_view_str(ST_PURCHORDER, $trans['order_no']);
}

function edit_link($row) {
	global $page_nested;

	return $page_nested || !$row['isopen'] ? '' :
		trans_editor_link(ST_PURCHORDER, $row['order_no']);
}

function receive_link($row) {
	global $page_nested;
	
	return $page_nested || !$row['OverDue'] ? '' :
		pager_link( _('Receive'),
		'/purchasing/po_receive_items.php?PONumber='.$row['order_no'], ICON_RECEIVE);
}

function prt_link($row) {
	return print_document_link($row['order_no'], _('Print'), true, ST_PURCHORDER, ICON_PRINT);
}

if (isset($_GET['order_number'])) {
	$_POST['order_number'] = $_GET['order_number'];
}

//-----------------------------------------------------------------------------------
// Ajax updates
//
if (get_post('SearchOrders')) {
	$Ajax->activate('orders_tbl');
}
elseif (get_post('_order_number_changed')) {
	$disable = get_post('order_number') !== '';

	$Ajax->addDisable(true, 'OrdersAfterDate', $disable);
	$Ajax->addDisable(true, 'OrdersToDate', $disable);
	$Ajax->addDisable(true, 'StockLocation', $disable);
	$Ajax->addDisable(true, '_SelectStockFromList_edit', $disable);
	$Ajax->addDisable(true, 'SelectStockFromList', $disable);

	if ($disable)
		$Ajax->addFocus(true, 'order_number');
	else
		$Ajax->addFocus(true, 'OrdersAfterDate');

	$Ajax->activate('orders_tbl');
}

//---------------------------------------------------------------------------------------------

start_form();

start_table(TABLESTYLE_NOBORDER);
start_row();
ref_cells(_('#:'), 'order_number', '',null, '', true);

date_cells(_('from:'), 'OrdersAfterDate', '', null, -user_transaction_days());
date_cells(_('to:'), 'OrdersToDate');

locations_list_cells(_('into location:'), 'StockLocation', null, true);
end_row();
end_table();

start_table(TABLESTYLE_NOBORDER);
start_row();

stock_items_list_cells(_('for item:'), 'SelectStockFromList', null, true);

if (!$page_nested)
	supplier_list_cells(_('Select a supplier: '), 'supplier_id', null, true, true);

check_cells(_('Also closed:'), 'also_closed', check_value('also_closed'));

submit_cells('SearchOrders', _('Search'),'',_('Select documents'), 'default');
end_row();
end_table(1);

//---------------------------------------------------------------------------------------------

$sql = get_sql_for_po_search_completed(get_post('OrdersAfterDate'), get_post('OrdersToDate'),
	get_post('supplier_id'), get_post('StockLocation'), get_post('order_number'),
	get_post('SelectStockFromList'), get_post('also_closed'));

$cols = array(
		_('#') => array('fun'=>'trans_view', 'ord'=>'', 'align'=>'right'), 
		_('Reference'), 
		_('Supplier') => array('ord'=>''),
		_('Location'),
		_("Supplier's Reference"), 
		_('Order Date') => array('name'=>'ord_date', 'type'=>'date', 'ord'=>'desc'),
		_('Currency') => array('align'=>'center'), 
		_('Order Total') => 'amount',
		array('insert'=>true, 'fun'=>'edit_link'),
		array('insert'=>true, 'fun'=>'receive_link'),
		array('insert'=>true, 'fun'=>'prt_link')
);

if (get_post('StockLocation') != ALL_TEXT)
	$cols[_('Location')] = 'skip';

//---------------------------------------------------------------------------------------------------

$table =& new_db_pager('orders_tbl', $sql, $cols);

$table->width = '80%';

display_db_pager($table);

end_form();
end_page();