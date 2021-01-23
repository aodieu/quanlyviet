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
$page_security = $_POST['PARAM_0'] == $_POST['PARAM_1'] ? 'SA_MANUFTRANSVIEW' : 'SA_MANUFBULKREP';
$path_to_root='..';

include_once($path_to_root . '/includes/session.inc');
include_once($path_to_root . '/includes/date_functions.inc');
include_once($path_to_root . '/includes/data_checks.inc');
include_once($path_to_root . '/manufacturing/includes/manufacturing_db.inc');

//----------------------------------------------------------------------------------------------------

print_workorders();

//----------------------------------------------------------------------------------------------------

function print_workorders() {
	global $path_to_root, $dflt_lang;

	include_once($path_to_root . '/reporting/includes/pdf_report.inc');

	$from = $_POST['PARAM_0'];
	$to = $_POST['PARAM_1'];
	$email = $_POST['PARAM_2'];
	$comments = $_POST['PARAM_3'];
	$orientation = $_POST['PARAM_4'];

	if (!$from || !$to) return;

	$orientation = ($orientation ? 'L' : 'P');
	$fno = explode('-', $from);
	$tno = explode('-', $to);
	$from = min($fno[0], $tno[0]);
	$to = max($fno[0], $tno[0]);

	$cols = array(4, 60, 190, 255, 320, 385, 450, 515);

	// $headers in doctext.inc
	$aligns = array('left',	'left',	'left', 'left', 'right', 'right', 'right');

	$params = array('comments' => $comments);

	$cur = get_company_Pref('curr_default');

	if ($email == 0)
		$rep = new FrontReport(_('WORK ORDER'), 'WorkOrderBulk', user_pagesize(), 9, $orientation);
	if ($orientation == 'L')
		recalculate_cols($cols);

	for ($i = $from; $i <= $to; $i++) {
		$myrow = get_work_order($i, true);
		if ($myrow === false)
			continue;
		if ($email == 1) {
			$rep = new FrontReport('', '', user_pagesize(), 9, $orientation);
			$rep->title = _('WORK ORDER');
			$rep->filename = 'WorkOrder' . $myrow['wo_ref'] . '.pdf';
		}
		$rep->currency = $cur;
		$rep->Font();
		$rep->Info($params, $cols, null, $aligns);

		$contact = array('email' =>$myrow['email'],'lang' => $dflt_lang, // ???
			'name' => $myrow['contact'], 'name2' => '', 'contact');

		$rep->SetCommonData($myrow, null, null, '', 26, $contact);
		$rep->SetHeaderType('Header2');
		$rep->NewPage();

		$result = get_wo_requirements($i);
		$rep->TextCol(0, 5,_('Work Order Requirements'), -2);
		$rep->NewLine(2);
		while ($myrow2=db_fetch($result)) {
			$rep->TextCol(0, 1,	$myrow2['stock_id'], -2);
			$rep->TextCol(1, 2, $myrow2['description'], -2);

			$rep->TextCol(2, 3,	$myrow2['location_name'], -2);
			$rep->TextCol(3, 4,	$myrow2['WorkCentreDescription'], -2);
			$dec = get_qty_dec($myrow2['stock_id']);

			$rep->AmountCol(4, 5,	$myrow2['units_req'], $dec, -2);
			$rep->AmountCol(5, 6,	$myrow2['units_req'] * $myrow['units_issued'], $dec, -2);
			$rep->AmountCol(6, 7,	$myrow2['units_issued'], $dec, -2);
			$rep->NewLine(1);
			if ($rep->row < $rep->bottomMargin + (15 * $rep->lineHeight))
				$rep->NewPage();
		}
		$memo = get_comments_string(ST_WORKORDER, $i);
		if ($memo != '') {
			$rep->NewLine();
			$rep->TextColLines(1, 5, $memo, -2);
		}

		if ($email == 1) {
			$myrow['DebtorName'] = $myrow['contact'];
			$myrow['reference'] = $myrow['wo_ref'];
			$rep->End($email);
		}
	}
	if ($email == 0)
		$rep->End();
}