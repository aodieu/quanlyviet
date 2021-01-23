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
$page_security = 'SA_SALESALLOC';
$path_to_root = '../..';
include($path_to_root . '/includes/db_pager.inc');
include_once($path_to_root . '/includes/session.inc');

include_once($path_to_root . '/sales/includes/sales_ui.inc');
include_once($path_to_root . '/sales/includes/sales_db.inc');
$js = '';
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 500);
page(_($help_context = 'Customer Allocations'), false, false, '', $js);

//--------------------------------------------------------------------------------

start_form();
/* show all outstanding receipts and credits to be allocated */

if (!isset($_POST['customer_id']))
	$_POST['customer_id'] = get_global_customer();

echo '<center>' . _('Select a customer: ') . '&nbsp;&nbsp;';
echo customer_list('customer_id', $_POST['customer_id'], true, true);
echo '<br>';
check(_('Show Settled Items:'), 'ShowSettled', null, true);
echo '</center><br><br>';

set_global_customer($_POST['customer_id']);

if (isset($_POST['customer_id']) && ($_POST['customer_id'] == ALL_TEXT))
	unset($_POST['customer_id']);

$settled = false;
if (check_value('ShowSettled'))
	$settled = true;

$customer_id = null;
if (isset($_POST['customer_id']))
	$customer_id = $_POST['customer_id'];

//--------------------------------------------------------------------------------

function systype_name($dummy, $type) {
	global $systypes_array;

	return $systypes_array[$type];
}

function trans_view($trans) {
	return get_trans_view_str($trans['type'], $trans['trans_no']);
}

function alloc_link($row) {
	return pager_link(_('Allocate'),
		'/sales/allocations/customer_allocate.php?trans_no='
			.$row['trans_no'] . '&trans_type=' . $row['type']. '&debtor_no=' . $row['debtor_no'], ICON_ALLOC);
}

function amount_total($row) {
	return price_format($row['type'] == ST_JOURNAL && $row['Total'] < 0 ? -$row['Total'] : $row['Total']);
}

function amount_left($row) {
	return price_format(($row['type'] == ST_JOURNAL && $row['Total'] < 0 ? -$row['Total'] : $row['Total'])-$row['alloc']);
}

function check_settled($row) {
	return $row['settled'] == 1;
}


$sql = get_allocatable_from_cust_sql($customer_id, $settled);

$cols = array(
	_('Transaction Type') => array('fun'=>'systype_name'),
	_('#') => array('fun'=>'trans_view', 'align'=>'right'),
	_('Reference'), 
	_('Date') => array('name'=>'tran_date', 'type'=>'date', 'ord'=>'asc'),
	_('Customer') => array('ord'=>''),
	_('Currency') => array('align'=>'center'),
	_('Total') => array('align'=>'right','fun'=>'amount_total'), 
	_('Left to Allocate') => array('align'=>'right','insert'=>true, 'fun'=>'amount_left'), 
	array('insert'=>true, 'fun'=>'alloc_link')
	);

if (isset($_POST['customer_id'])) {
	$cols[_('Customer')] = 'skip';
	$cols[_('Currency')] = 'skip';
}

$table =& new_db_pager('alloc_tbl', $sql, $cols);
$table->set_marker('check_settled', _('Marked items are settled.'), 'settledbg', 'settledfg');

$table->width = '75%';

display_db_pager($table);
end_form();

end_page();