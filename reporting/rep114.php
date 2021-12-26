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
$page_security = 'SA_TAXREP';
$path_to_root = '..';

include_once($path_to_root.'/includes/session.inc');
include_once($path_to_root.'/includes/date_functions.inc');
include_once($path_to_root.'/includes/data_checks.inc');
include_once($path_to_root.'/gl/includes/gl_db.inc');

//------------------------------------------------------------------

print_sales_summary_report();

function getTaxTransactions($from, $to, $tax_id) {
	$fromdate = date2sql($from);
	$todate = date2sql($to);

	$sql = "SELECT d.debtor_no, d.name AS cust_name, d.tax_id, dt.type, dt.trans_no,  
			CASE WHEN dt.type=".ST_CUSTCREDIT." THEN (ov_amount+ov_freight+ov_discount)*-1 
			ELSE (ov_amount+ov_freight+ov_discount) END *dt.rate AS total
		FROM ".TB_PREF."debtor_trans dt
			LEFT JOIN ".TB_PREF."debtors_master d ON d.debtor_no=dt.debtor_no
		WHERE (dt.type=".ST_SALESINVOICE." OR dt.type=".ST_CUSTCREDIT.") ";
	if ($tax_id)
		$sql .= "AND tax_id<>'' ";
	$sql .= "AND dt.tran_date >=".db_escape($fromdate)." AND dt.tran_date<=".db_escape($todate)."
		ORDER BY d.debtor_no"; 
	return db_query($sql, 'No transactions were returned');
}

function getTaxes($type, $trans_no) {
	$sql = "SELECT included_in_price, SUM(CASE WHEN trans_type=".ST_CUSTCREDIT." THEN -amount ELSE amount END * ex_rate) AS tax
		FROM ".TB_PREF."trans_tax_details WHERE trans_type=$type AND trans_no=$trans_no GROUP BY included_in_price";

	$result = db_query($sql, 'No transactions were returned');

	if ($result !== false)
		return db_fetch($result);
	
	return null;
}    	

//----------------------------------------------------------------------------------------------------

function print_sales_summary_report() {
	global $path_to_root;
	
	$from = $_POST['PARAM_0'];
	$to = $_POST['PARAM_1'];
	$tax_id = $_POST['PARAM_2'];
	$comments = $_POST['PARAM_3'];
	$orientation = $_POST['PARAM_4'];
	$destination = $_POST['PARAM_5'];

	$tid = $tax_id == 0 ? _('No') : _('Yes');

	if ($destination)
		include_once($path_to_root.'/reporting/includes/excel_report.inc');
	else
		include_once($path_to_root.'/reporting/includes/pdf_report.inc');
	$orientation = $orientation ? 'L' : 'P';

	$dec = user_price_dec();

	$rep = new FrontReport(_('Sales Summary Report'), 'SalesSummaryReport', user_pagesize(), 9, $orientation);

	$params =   array( 	0 => $comments,
						1 => array('text' => _('Period'), 'from' => $from, 'to' => $to),
						2 => array(  'text' => _('Tax Id Only'),'from' => $tid,'to' => ''));

	$cols = array(0, 300, 360, 440, 515);

	$headers = array(_('Customer'), _('Tax Id'), _('Total ex. Tax'), _('Tax'));
	$aligns = array('left', 'left', 'right', 'right');
	if ($orientation == 'L')
		recalculate_cols($cols);

	$rep->Font();
	$rep->Info($params, $cols, $headers, $aligns);
	$rep->NewPage();
	
	$totalnet = 0.0;
	$totaltax = 0.0;
	$transactions = getTaxTransactions($from, $to, $tax_id);

	$rep->TextCol(0, 4, _('Balances in Home Currency'));
	$rep->NewLine(2);
	
	$custno = 0;
	$tax = $total = 0;
	$custname = '';
	$tax_id = '';
	while ($trans=db_fetch($transactions)) {
		if ($custno != $trans['debtor_no']) {
			if ($custno != 0) {
				$rep->TextCol(0, 1, $custname);
				$rep->TextCol(1, 2,	$tax_id);
				$rep->AmountCol(2, 3, $total, $dec);
				$rep->AmountCol(3, 4, $tax, $dec);
				$totalnet += $total;
				$totaltax += $tax;
				$total = $tax = 0;
				$rep->NewLine();

				if ($rep->row < $rep->bottomMargin + $rep->lineHeight) {
					$rep->Line($rep->row - 2);
					$rep->NewPage();
				}
			}
			$custno = $trans['debtor_no'];
			$custname = $trans['cust_name'];
			$tax_id = $trans['tax_id'];
		}	
		$taxes = getTaxes($trans['type'], $trans['trans_no']);
		if ($taxes != null) {
			if ($taxes['included_in_price'])
				$trans['total'] -= $taxes['tax'];
			$tax += $taxes['tax'];
		}	
		$total += $trans['total']; 
	}
	if ($custno != 0) {
		$rep->TextCol(0, 1, $custname);
		$rep->TextCol(1, 2,	$tax_id);
		$rep->AmountCol(2, 3, $total, $dec);
		$rep->AmountCol(3, 4, $tax, $dec);
		$totalnet += $total;
		$totaltax += $tax;
		$rep->NewLine();
	}
	$rep->Font('bold');
	$rep->NewLine();
	$rep->Line($rep->row + $rep->lineHeight);
	$rep->TextCol(0, 2,	_('Total'));
	$rep->AmountCol(2, 3, $totalnet, $dec);
	$rep->AmountCol(3, 4, $totaltax, $dec);
	$rep->Line($rep->row - 5);
	$rep->Font();

	$rep->End();
}
