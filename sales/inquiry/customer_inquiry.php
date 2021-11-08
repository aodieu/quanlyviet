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
$page_security = 'SA_SALESTRANSVIEW';
$path_to_root = '../..';
include_once($path_to_root.'/includes/db_pager.inc');
include_once($path_to_root.'/includes/session.inc');

include_once($path_to_root.'/sales/includes/sales_ui.inc');
include_once($path_to_root.'/sales/includes/sales_db.inc');
include_once($path_to_root.'/reporting/includes/reporting.inc');

$js = '';
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 500);
if (user_use_date_picker())
	$js .= get_js_date_picker();

page(_($help_context = 'Customer Transactions'), isset($_GET['customer_id']), false, '', $js);

//------------------------------------------------------------------------------------------------

function systype_name($dummy, $type) {
	global $systypes_array;

	return $systypes_array[$type];
}

function order_view($row) {
	return $row['order_'] > 0 ? get_customer_trans_view_str(ST_SALESORDER, $row['order_']) : '';
}

function trans_view($trans) {
	return get_trans_view_str($trans['type'], $trans['trans_no']);
}

function due_date($row) {
	return	$row['type'] == ST_SALESINVOICE	? $row['due_date'] : '';
}

function gl_view($row) {
	return get_gl_view_str($row['type'], $row['trans_no']);
}

function fmt_amount($row) {
	$value = $row['type']==ST_CUSTCREDIT || $row['type']==ST_CUSTPAYMENT || $row['type']==ST_BANKDEPOSIT || $row['type']==ST_JOURNAL ? -$row['TotalAmount'] : $row['TotalAmount'];
	return price_format($value);
}

function credit_link($row) {
	global $page_nested;

	if ($page_nested)
		return '';
	if ($row['Outstanding'] > 0) {
		if ($row['type'] == ST_CUSTDELIVERY)
			return pager_link(_('Invoice'), '/sales/customer_invoice.php?DeliveryNumber='.$row['trans_no'], ICON_DOC);
		else if ($row['type'] == ST_SALESINVOICE)
			return pager_link(_('Credit This'), '/sales/customer_credit_invoice.php?InvoiceNumber='.$row['trans_no'], ICON_CREDIT);
	}	
}

function edit_link($row) {
	global $page_nested;

	if ($page_nested)
		return '';

	return $row['type'] == ST_CUSTCREDIT && $row['order_'] ? '' : 	// allow  only free hand credit notes edition
			trans_editor_link($row['type'], $row['trans_no']);
}

function prt_link($row) {
	if ($row['type'] == ST_CUSTPAYMENT || $row['type'] == ST_BANKDEPOSIT) 
		return print_document_link($row['trans_no'].'-'.$row['type'], _('Print Receipt'), ST_CUSTPAYMENT, ICON_PRINT);
	elseif ($row['type'] == ST_BANKPAYMENT) // bank payment printout not defined yet.
		return '';
	else
		return print_document_link($row['trans_no'].'-'.$row['type'], _('Print'), $row['type'], ICON_PRINT);
}

function check_overdue($row) {
	return $row['OverDue'] == 1 && floatcmp($row['TotalAmount'], $row['Allocated']) != 0;
}

//------------------------------------------------------------------------------------------------

function display_customer_summary($customer_record) {
	$past1 = get_company_pref('past_due_days');
	$past2 = 2 * $past1;
	if ($customer_record['dissallow_invoices'] != 0)
		echo '<center><font color=red size=4><b>'._('CUSTOMER ACCOUNT IS ON HOLD').'</font></b></center>';

	$nowdue = '1-'.$past1.' '._('Days');
	$pastdue1 = $past1 + 1.'-'.$past2.' '._('Days');
	$pastdue2 = _('Over').' '.$past2.' '._('Days');

	$th = array(_('Currency'), _('Terms'), _('Current'), $nowdue, $pastdue1, $pastdue2, _('Total Balance'));
	start_table(TABLESTYLE, "width='80%'");
	table_header($th);

	start_row();
	label_cell($customer_record['curr_code']);
	label_cell($customer_record['terms']);
	amount_cell($customer_record['Balance'] - $customer_record['Due']);
	amount_cell($customer_record['Due'] - $customer_record['Overdue1']);
	amount_cell($customer_record['Overdue1'] - $customer_record['Overdue2']);
	amount_cell($customer_record['Overdue2']);
	amount_cell($customer_record['Balance']);
	end_row();

	end_table();
}

if (isset($_GET['customer_id']))
	$_POST['customer_id'] = $_GET['customer_id'];

//------------------------------------------------------------------------------------------------

start_form();

if (!isset($_POST['customer_id']))
	$_POST['customer_id'] = get_global_customer();

start_table(TABLESTYLE_NOBORDER);
start_row();

if (!$page_nested)
	customer_list_cells(_('Select a customer: '), 'customer_id', null, true, true, false, true);

cust_allocations_list_cells(null, 'filterType', null, true, true);

if ($_POST['filterType'] != '2') {
	date_cells(_('From:'), 'TransAfterDate', '', null, -user_transaction_days());
	date_cells(_('To:'), 'TransToDate', '', null);
}
check_cells(_('Zero values'), 'show_voided');

submit_cells('RefreshInquiry', _('Search'), '', _('Refresh Inquiry'), 'default');
end_row();
end_table();

set_global_customer($_POST['customer_id']);

//------------------------------------------------------------------------------------------------

div_start('totals_tbl');
if ($_POST['customer_id'] != '' && $_POST['customer_id'] != ALL_TEXT) {
	$customer_record = get_customer_details(get_post('customer_id'), get_post('TransToDate'));
	display_customer_summary($customer_record);
	echo '<br>';
}
div_end();

if (get_post('RefreshInquiry') || list_updated('filterType'))
	$Ajax->activate('_page_body');

//------------------------------------------------------------------------------------------------

$sql = get_sql_for_customer_inquiry(get_post('TransAfterDate'), get_post('TransToDate'), get_post('customer_id'), get_post('filterType'), check_value('show_voided'));

//------------------------------------------------------------------------------------------------

$cols = array(
	_('Type') => array('fun'=>'systype_name', 'ord'=>''),
	_('#') => array('fun'=>'trans_view', 'ord'=>'', 'align'=>'right'),
	_('Order') => array('fun'=>'order_view', 'align'=>'right'), 
	_('Reference'), 
	_('Date') => array('name'=>'tran_date', 'type'=>'date', 'ord'=>'desc'),
	_('Due Date') => array('type'=>'date', 'fun'=>'due_date'),
	_('Customer') => array('ord'=>''), 
	_('Branch') => array('ord'=>''), 
	_('Currency') => array('align'=>'center'),
	_('Amount') => array('align'=>'right', 'fun'=>'fmt_amount'), 
	_('Balance') => array('align'=>'right', 'type'=>'amount'),
		array('insert'=>true, 'fun'=>'gl_view'),
		array('insert'=>true, 'fun'=>'edit_link'),
		array('insert'=>true, 'fun'=>'credit_link'),
		array('insert'=>true, 'fun'=>'prt_link')
);

if ($_POST['customer_id'] != ALL_TEXT) {
	$cols[_('Customer')] = 'skip';
	$cols[_('Currency')] = 'skip';
}
if ($_POST['filterType'] != '2')
	$cols[_('Balance')] = 'skip';

$table =& new_db_pager('trans_tbl', $sql, $cols);
$table->set_marker('check_overdue', _('Marked items are overdue.'));

$table->width = '85%';

display_db_pager($table);

end_form();
end_page();
