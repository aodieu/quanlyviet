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
$page_security = 'SA_DIMENSIONREP';
$path_to_root='..';

include_once($path_to_root . '/includes/session.inc');
include_once($path_to_root . '/includes/date_functions.inc');
include_once($path_to_root . '/includes/data_checks.inc');
include_once($path_to_root . '/gl/includes/gl_db.inc');

//----------------------------------------------------------------------------------------------------

print_dimension_summary();

function getTransactions($from, $to) {
	$sql = "SELECT *
		FROM
			".TB_PREF."dimensions
		WHERE id >= ".db_escape($from)."
		AND id <= ".db_escape($to)."
		ORDER BY
			reference";

	return db_query($sql, 'No transactions were returned');
}

function getYTD($dim) {
	$date = Today();
	$date = begin_fiscalyear($date);
	date2sql($date);
	
	$sql = "SELECT SUM(amount) AS Balance
		FROM
			".TB_PREF."gl_trans
		WHERE (dimension_id = '$dim' OR dimension2_id = '$dim')
		AND tran_date >= '$date'";

	$TransResult = db_query($sql, 'No transactions were returned');
	if (db_num_rows($TransResult) == 1) {
		$DemandRow = db_fetch_row($TransResult);
		$balance = $DemandRow[0];
	}
	else
		$balance = 0.0;

	return $balance;
}

//----------------------------------------------------------------------------------------------------

function print_dimension_summary() {
	global $path_to_root;

	$fromdim = $_POST['PARAM_0'];
	$todim = $_POST['PARAM_1'];
	$showbal = $_POST['PARAM_2'];
	$comments = $_POST['PARAM_3'];
	$orientation = $_POST['PARAM_4'];
	$destination = $_POST['PARAM_5'];
	if ($destination)
		include_once($path_to_root . '/reporting/includes/excel_report.inc');
	else
		include_once($path_to_root . '/reporting/includes/pdf_report.inc');

	$orientation = ($orientation ? 'L' : 'P');
	$cols = array(0, 50, 210, 250, 320, 395, 465,	515);

	$headers = array(_('Reference'), _('Name'), _('Type'), _('Date'), _('Due Date'), _('Closed'), _('YTD'));

	$aligns = array('left',	'left', 'left',	'left', 'left', 'left', 'right');

	$params =   array( 	0 => $comments,
						1 => array('text' => _('Dimension'), 'from' => get_dimension_string($fromdim), 'to' => get_dimension_string($todim)));

	$rep = new FrontReport(_('Dimension Summary'), 'DimensionSummary', user_pagesize(), 9, $orientation);
	if ($orientation == 'L')
		recalculate_cols($cols);

	$rep->Font();
	$rep->Info($params, $cols, $headers, $aligns);
	$rep->NewPage();

	$res = getTransactions($fromdim, $todim);
	while ($trans=db_fetch($res)) {
		$rep->TextCol(0, 1, $trans['reference']);
		$rep->TextCol(1, 2, $trans['name']);
		$rep->TextCol(2, 3, $trans['type_']);
		$rep->DateCol(3, 4, $trans['date_'], true);
		$rep->DateCol(4, 5, $trans['due_date'], true);
		if ($trans['closed'])
			$str = _('Yes');
		else
			$str = _('No');
		$rep->TextCol(5, 6, $str);
		if ($showbal) {
			$balance = getYTD($trans['id']);
			$rep->AmountCol(6, 7, $balance, 0);
		}	
		$rep->NewLine(1, 2);
	}
	$rep->Line($rep->row);
	$rep->End();
}