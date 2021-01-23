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
$page_security = 'SA_GLREP';
$path_to_root='..';

include_once($path_to_root . '/includes/session.inc');
include_once($path_to_root . '/includes/date_functions.inc');
include_once($path_to_root . '/includes/data_checks.inc');
include_once($path_to_root . '/gl/includes/gl_db.inc');

//----------------------------------------------------------------------------------------------------

function display_type ($type, $typename, &$dec, &$rep, $showbalance, $level) {
	$printtitle = 0; //Flag for printing type name	

	//Get Accounts directly under this group/type
	$result = get_gl_accounts(null, null, $type);	
	while ($account=db_fetch($result)) {
		//Print Type Title if it has atleast one non-zero account	
		if (!$printtitle) {
			$prefix = '';
			for ($sp=1; $sp<=$level; $sp++) {
				$prefix .= '         ';
			}
			$printtitle = 1;
			$rep->row -= 4;
			$rep->TextCol(0, 1, $type);
			$rep->TextCol(1, 4, $prefix.$typename);
			$rep->row -= 4;
			$rep->Line($rep->row);
			$rep->NewLine();		
		}			
		if ($showbalance == 1) {
			$begin = begin_fiscalyear();
			if (is_account_balancesheet($account['account_code']))
				$begin = '';
			$balance = get_gl_trans_from_to($begin, ToDay(), $account['account_code'], 0);
		}
		$rep->TextCol(0, 1,	$account['account_code']);
		$rep->TextCol(1, 2,	$prefix.$account['account_name']);
		$rep->TextCol(2, 3,	$account['account_code2']);
		if ($showbalance == 1)	
			$rep->AmountCol(3, 4, $balance, $dec);
		$rep->NewLine();
	}

	//Get Account groups/types under this group/type
	$result = get_account_types(false, false, $type);
	while ($accounttype=db_fetch($result)) {
		//Print Type Title if has sub types and not previously printed
		if (!$printtitle) {
			$printtitle = 1;
			$rep->row -= 4;
			$rep->TextCol(0, 1, $type);
			$rep->TextCol(1, 4, $typename);
			$rep->row -= 4;
			$rep->Line($rep->row);
			$rep->NewLine();		
		}
		$nextlevel = $level + 1;
		display_type($accounttype['id'], $accounttype['name'].' ('.$typename.')', $dec, $rep, $showbalance, $nextlevel);
	}
}

//----------------------------------------------------------------------------------------------------

print_Chart_of_Accounts();

//----------------------------------------------------------------------------------------------------

function print_Chart_of_Accounts() {
	global $path_to_root;

	$showbalance = $_POST['PARAM_0'];
	$comments = $_POST['PARAM_1'];
	$orientation = $_POST['PARAM_2'];
	$destination = $_POST['PARAM_3'];
	if ($destination)
		include_once($path_to_root . '/reporting/includes/excel_report.inc');
	else
		include_once($path_to_root . '/reporting/includes/pdf_report.inc');

	$orientation = ($orientation ? 'L' : 'P');

	$cols = array(0, 60, 300, 425, 500);

	$headers = array(_('Account'), _('Account Name'), _('Account Code'), _('Balance'));
	
	$aligns = array('left',	'left',	'left',	'right');
	
	$params = array(0 => $comments);

	$rep = new FrontReport(_('Chart of Accounts'), 'ChartOfAccounts', user_pagesize(), 9, $orientation);
	if ($orientation == 'L')
		recalculate_cols($cols);
	
	$rep->Font();
	$rep->Info($params, $cols, $headers, $aligns);
	$rep->NewPage();

	$classresult = get_account_classes(false);
	while ($class = db_fetch($classresult)) {
		$rep->Font('bold');
		$rep->TextCol(0, 1, $class['cid']);
		$rep->TextCol(1, 4, $class['class_name']);
		$rep->Font();
		$rep->NewLine();

		//Get Account groups/types under this group/type with no parents
		$typeresult = get_account_types(false, $class['cid'], -1);
		while ($accounttype=db_fetch($typeresult)) {
			display_type($accounttype['id'], $accounttype['name'], $dec, $rep, $showbalance, 0);
		}
		$rep->NewLine();
	}
	$rep->Line($rep->row + 10);
	$rep->End();
}