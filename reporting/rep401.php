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
$page_security = 'SA_BOMREP';
$path_to_root='..';

include_once($path_to_root . '/includes/session.inc');
include_once($path_to_root . '/includes/date_functions.inc');
include_once($path_to_root . '/includes/data_checks.inc');
include_once($path_to_root . '/gl/includes/gl_db.inc');
include_once($path_to_root . '/inventory/includes/db/items_db.inc');

//----------------------------------------------------------------------------------------------------

print_bill_of_material();

function getTransactions($from, $to) {
	$sql = "SELECT bom.parent,
			bom.component,
			item.description as CompDescription,
			bom.quantity,
			bom.loc_code,
			bom.workcentre_added
		FROM "
			.TB_PREF."stock_master item,"
			.TB_PREF."bom bom
		WHERE item.stock_id=bom.component
		AND bom.parent >= ".db_escape($from)."
		AND bom.parent <= ".db_escape($to)."
		ORDER BY
			bom.parent,
			bom.component";

	return db_query($sql, 'No transactions were returned');
}

//----------------------------------------------------------------------------------------------------

function print_bill_of_material() {
	global $path_to_root;

	$frompart = $_POST['PARAM_0'];
	$topart = $_POST['PARAM_1'];
	$comments = $_POST['PARAM_2'];
	$orientation = $_POST['PARAM_3'];
	$destination = $_POST['PARAM_4'];
	if ($destination)
		include_once($path_to_root . '/reporting/includes/excel_report.inc');
	else
		include_once($path_to_root . '/reporting/includes/pdf_report.inc');

	$orientation = ($orientation ? 'L' : 'P');
	$cols = array(0, 50, 305, 375, 445,	515);

	$headers = array(_('Component'), _('Description'), _('Loc'), _('Wrk Ctr'), _('Quantity'));

	$aligns = array('left',	'left',	'left', 'left', 'right');

	$params =   array( 	0 => $comments,
						1 => array('text' => _('Component'), 'from' => $frompart, 'to' => $topart));

	$rep = new FrontReport(_('Bill of Material Listing'), 'BillOfMaterial', user_pagesize(), 9, $orientation);
	if ($orientation == 'L')
		recalculate_cols($cols);

	$rep->Font();
	$rep->Info($params, $cols, $headers, $aligns);
	$rep->NewPage();

	$res = getTransactions($frompart, $topart);
	$parent = '';
	while ($trans=db_fetch($res)) {
		if ($parent != $trans['parent']) {
			if ($parent != '') {
				$rep->Line($rep->row - 2);
				$rep->NewLine(2, 3);
			}
			$rep->TextCol(0, 1, $trans['parent']);
			$desc = get_item($trans['parent']);
			$rep->TextCol(1, 2, $desc['description']);
			$parent = $trans['parent'];
			$rep->NewLine();
		}

		$rep->NewLine();
		$dec = get_qty_dec($trans['component']);
		$rep->TextCol(0, 1, $trans['component']);
		$rep->TextCol(1, 2, $trans['CompDescription']);
		$wc = get_work_centre($trans['workcentre_added']);
		$rep->TextCol(2, 3, get_location_name($trans['loc_code']));
		$rep->TextCol(3, 4, $wc['name']);
		$rep->AmountCol(4, 5, $trans['quantity'], $dec);
	}
	$rep->Line($rep->row - 4);
	$rep->NewLine();
	$rep->End();
}