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
$page_security = 'SA_GLANALYTIC';
$path_to_root='..';

include_once($path_to_root . '/includes/session.inc');
include_once($path_to_root . '/includes/date_functions.inc');
include_once($path_to_root . '/includes/data_checks.inc');
include_once($path_to_root . '/gl/includes/gl_db.inc');
include_once($path_to_root . '/includes/ui/ui_view.inc');

//----------------------------------------------------------------------------------------------------

print_audit_trail();

function getTransactions($from, $to, $type, $user) {
	$fromdate = date2sql($from) . ' 00:00:00';
	$todate = date2sql($to). ' 23:59.59';

	$sql = "SELECT a.*, 
		SUM(IF(ISNULL(g.amount), NULL, IF(g.amount > 0, g.amount, 0))) AS amount,
		u.user_id,
		UNIX_TIMESTAMP(a.stamp) as unix_stamp
		FROM ".TB_PREF."audit_trail AS a JOIN ".TB_PREF."users AS u
		LEFT JOIN ".TB_PREF."gl_trans AS g ON (g.type_no=a.trans_no
			AND g.type=a.type)
		WHERE a.user = u.id ";
	if ($type != -1)
		$sql .= "AND a.type=$type ";
	if ($user != -1)	
		$sql .= "AND a.user='$user' ";
	$sql .= "AND a.stamp >= '$fromdate'
			AND a.stamp <= '$todate'
		GROUP BY a.trans_no,a.gl_seq,a.stamp	
		ORDER BY a.stamp,a.gl_seq";
	return db_query($sql, 'No transactions were returned');
}

//----------------------------------------------------------------------------------------------------

function print_audit_trail() {
	global $path_to_root, $systypes_array;

	$from = $_POST['PARAM_0'];
	$to = $_POST['PARAM_1'];
	$systype = $_POST['PARAM_2'];
	$user = $_POST['PARAM_3'];
	$comments = $_POST['PARAM_4'];
	$orientation = $_POST['PARAM_5'];
	$destination = $_POST['PARAM_6'];
	if ($destination)
		include_once($path_to_root.'/reporting/includes/excel_report.inc');
	else
		include_once($path_to_root.'/reporting/includes/pdf_report.inc');

	$orientation = ($orientation ? 'L' : 'P');
	$dec = user_price_dec();

	$cols = array(0, 60, 120, 180, 240, 340, 400, 460, 520);

	$headers = array(_('Date'), _('Time'), _('User'), _('Trans Date'), _('Type'), _('#'), _('Action'), _('Amount'));

	$aligns = array('left', 'left', 'left', 'left', 'left', 'left', 'left', 'right');

	$usr = get_user($user);
	$user_id = $usr['user_id'];
	$params =   array( 	0 => $comments,
						1 => array('text' => _('Period'), 'from' => $from,'to' => $to),
						2 => array('text' => _('Type'), 'from' => ($systype != -1 ? $systypes_array[$systype] : _('All')), 'to' => ''),
						3 => array('text' => _('User'), 'from' => ($user != -1 ? $user_id : _('All')), 'to' => ''));

	$rep = new FrontReport(_('Audit Trail'), 'AuditTrail', user_pagesize(), 9, $orientation);
	if ($orientation == 'L')
		recalculate_cols($cols);

	$rep->Font();
	$rep->Info($params, $cols, $headers, $aligns);
	$rep->NewPage();

	$trans = getTransactions($from, $to, $systype, $user);

	$tot_amount = 0;
	while ($myrow=db_fetch($trans)) {
		$rep->TextCol(0, 1, sql2date(date('Y-m-d', $myrow['unix_stamp'])));
		if (user_date_format() == 0)
			$rep->TextCol(1, 2, date('h:i:s a', $myrow['unix_stamp']));
		else	
			$rep->TextCol(1, 2, date('H:i:s', $myrow['unix_stamp']));
		$rep->TextCol(2, 3, $myrow['user_id']);
		$rep->TextCol(3, 4, sql2date($myrow['gl_date']));
		$rep->TextCol(4, 5, $systypes_array[$myrow['type']]);
		$rep->TextCol(5, 6, $myrow['trans_no']);
		if ($myrow['gl_seq'] == null)
			$action = _('Changed');
		else
			$action = _('Closed');
		$rep->TextCol(6, 7, $action);
		if ($myrow['amount'] != null) {
			$rep->AmountCol(7, 8, $myrow['amount'], $dec);
			if ($systype != -1)
				$tot_amount += $myrow['amount'];
		}
		$rep->NewLine(1, 2);
	}
	$rep->Line($rep->row  + 4);
	if ($systype != -1) {
		$rep->NewLine(1, 2);
		$rep->TextCol(6, 7, _('Total'));
		$rep->AmountCol(7, 8, $tot_amount, $dec);
	}
	$rep->End();
}