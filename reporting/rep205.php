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
$page_security = 'SA_SUPPLIERANALYTIC';
$path_to_root='..';

include_once($path_to_root . '/includes/session.inc');
include_once($path_to_root . '/includes/date_functions.inc');
include_once($path_to_root . '/includes/data_checks.inc');
include_once($path_to_root . '/includes/db/crm_contacts_db.inc');
include_once($path_to_root . '/gl/includes/gl_db.inc');

//----------------------------------------------------------------------------------------------------

print_supplier_details_listing();

function get_supplier_details_for_report() {
	$sql = "SELECT supplier_id,	supp_name, address, supp_address, supp_ref,
				contact, curr_code,	dimension_id, dimension2_id, notes, gst_no
			FROM ".TB_PREF."suppliers
			WHERE inactive = 0
			ORDER BY supp_name";

	return db_query($sql, 'No transactions were returned');
}

function getTransactions($supplier_id, $date) {
	$date = date2sql($date);

	$sql = "SELECT SUM((ov_amount+ov_discount)*rate) AS Turnover
		FROM ".TB_PREF."supp_trans
		WHERE supplier_id=".db_escape($supplier_id)."
		AND (type=".ST_SUPPINVOICE." OR type=".ST_SUPPCREDIT.")
		AND tran_date >='$date'";

	$result = db_query($sql, 'No transactions were returned');

	$row = db_fetch_row($result);
	return $row[0];
}

//----------------------------------------------------------------------------------------------------

function print_supplier_details_listing() {
	global $path_to_root;

	$from = $_POST['PARAM_0'];
	$more = $_POST['PARAM_1'];
	$less = $_POST['PARAM_2'];
	$comments = $_POST['PARAM_3'];
	$orientation = $_POST['PARAM_4'];
	$destination = $_POST['PARAM_5'];
	if ($destination)
		include_once($path_to_root . '/reporting/includes/excel_report.inc');
	else
		include_once($path_to_root . '/reporting/includes/pdf_report.inc');

	$orientation = ($orientation ? 'L' : 'P');
	$dec = 0;

	if ($more != '')
		$morestr = _('Greater than ') . number_format2($more, $dec);
	else
		$morestr = '';
	if ($less != '')
		$lessstr = _('Less than ') . number_format2($less, $dec);
	else
		$lessstr = '';

	$more = (double)$more;
	$less = (double)$less;

	$cols = array(0, 150, 300, 425, 550);

	$headers = array(_('Mailing Address:'), _('Turnover'),	_('Contact Information'),
		_('Physical Address'));

	$aligns = array('left',	'left',	'left',	'left');

	$params =   array( 	0 => $comments,
						1 => array('text' => _('Activity Since'), 	'from' => $from, 		'to' => ''),
						2 => array('text' => _('Activity'), 		'from' => $morestr, 	'to' => $lessstr . ' ' . get_company_pref('curr_default')));

	$rep = new FrontReport(_('Supplier Details Listing'), 'SupplierDetailsListing', user_pagesize(), 9, $orientation);
	if ($orientation == 'L')
		recalculate_cols($cols);

	$rep->Font();
	$rep->Info($params, $cols, $headers, $aligns);
	$rep->NewPage();

	$result = get_supplier_details_for_report();

	while ($myrow=db_fetch($result)) {
		$printsupplier = true;
		if ($more != '' || $less != '') {
			$turnover = getTransactions($myrow['supplier_id'], $from);
			if ($more != 0.0 && $turnover <= (double)$more)
				$printsupplier = false;
			if ($less != 0.0 && $turnover >= (double)$less)
				$printsupplier = false;
		}
		if ($printsupplier) {
			$newrow = 0;
			$rep->NewLine();
			// Here starts the new report lines
			$contacts = get_supplier_contacts($myrow['supplier_id']);
			$rep->TextCol(0, 1,	$myrow['supp_name']);
			$rep->TextCol(1, 2,	_('Tax_Id') . ': ' . $myrow['gst_no']);
			$rep->TextCol(2, 3,	$myrow['contact']);
			$rep->NewLine();
			$adr = Explode("\n", $myrow['address']);
			$adr2 = Explode("\n", $myrow['supp_address']);
			$count1 = count($adr);
			$count2 = count($adr2);
			$count1 = max($count1, $count2);
			$count1 = max($count1, 4); 
			if (isset($adr[0]))
				$rep->TextCol(0, 1, $adr[0]);
			$rep->TextCol(1, 2,	_('Currency') . ': ' . $myrow['curr_code']);
			if (isset($contacts[0]))
				$rep->TextCol(2, 3, $contacts[0]['name']. ' ' .$contacts[0]['name2']);
			if (isset($adr2[0]))	
				$rep->TextCol(3, 4, $adr2[0]);
			$rep->NewLine();
			if (isset($adr[1]))
				$rep->TextCol(0, 1, $adr[1]);
			if ($myrow['dimension_id'] != 0) {
				$dim = get_dimension($myrow['dimension_id']);
				$rep->TextCol(1, 2,	_('Dimension') . ': ' . $dim['name']);
			}		
			if (isset($contacts[0]))
				$rep->TextCol(2, 3, _('Ph') . ': ' . $contacts[0]['phone']);
			if (isset($adr2[1]))
				$rep->TextCol(3, 4, $adr2[1]);
			$rep->NewLine();
			if (isset($adr[2]))
				$rep->TextCol(0, 1, $adr[2]);
			if ($myrow['dimension2_id'] != 0) {
				$dim = get_dimension($myrow['dimension2_id']);
				$rep->TextCol(1, 2,	_('Dimension') . ' 2: ' . $dim['name']);
			}
			if ($myrow['notes'] != '') {
				$oldrow = $rep->row;
				$rep->NewLine();
				$rep->TextColLines(1, 2, _('General Notes:').' '.$myrow['notes'], -2);
				$newrow = $rep->row;
				$rep->row = $oldrow;
			}	
			if (isset($contacts[0]))
				$rep->TextCol(2, 3, _('Fax') . ': ' . $contacts[0]['fax']);
			if (isset($adr2[2]))
				$rep->TextCol(3, 4, $adr2[2]);
			if ($more != 0.0 || $less != 0.0)
				$rep->TextCol(1, 2,	_('Turnover') . ': ' . number_format2($turnover, $dec));
			for ($i = 3; $i < $count1; $i++) {
				$rep->NewLine();
				if (isset($adr[$i]))
					$rep->TextCol(0, 1, $adr[$i]);
				if ($i == 3 && isset($contacts[0]) && isset($contacts[0]['email']))	
					$rep->TextCol(2, 3, _('Email') . ': ' . $contacts[0]['email']);
				if (isset($adr2[$i]))
					$rep->TextCol(3, 4, $adr2[$i]);
			}	
			if ($newrow != 0 && $newrow < $rep->row)
				$rep->row = $newrow;
			$rep->NewLine();
			$rep->Line($rep->row + 8);
			$rep->NewLine(0, 3);
		}
	}
	$rep->End();
}