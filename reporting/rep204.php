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
include_once($path_to_root . '/gl/includes/gl_db.inc');

//----------------------------------------------------------------------------------------------------

print_outstanding_GRN();

function getTransactions($fromsupp) {
	$sql = "SELECT grn.id,
			order_no,
			grn.supplier_id,
			supplier.supp_name,
			item.item_code,
			item.description,
			qty_recd,
			quantity_inv,
			std_cost_unit,
			act_price,
			unit_price
		FROM ".TB_PREF."grn_items item,
			".TB_PREF."grn_batch grn,
			".TB_PREF."purch_order_details poline,
			".TB_PREF."suppliers supplier
		WHERE grn.supplier_id=supplier.supplier_id
		AND grn.id = item.grn_batch_id
		AND item.po_detail_item = poline.po_detail_item
		AND qty_recd-quantity_inv!=0";

	if ($fromsupp != ALL_TEXT)
		$sql .= " AND grn.supplier_id =".db_escape($fromsupp);

	$sql .= " ORDER BY grn.supplier_id,	grn.id";

	return db_query($sql, 'No transactions were returned');
}

//----------------------------------------------------------------------------------------------------

function print_outstanding_GRN() {
	global $path_to_root;

	$fromsupp = $_POST['PARAM_0'];
	$comments = $_POST['PARAM_1'];
	$orientation = $_POST['PARAM_2'];
	$destination = $_POST['PARAM_3'];
	if ($destination)
		include_once($path_to_root . '/reporting/includes/excel_report.inc');
	else
		include_once($path_to_root . '/reporting/includes/pdf_report.inc');

	$orientation = ($orientation ? 'L' : 'P');
	if ($fromsupp == ALL_TEXT)
		$from = _('All');
	else
		$from = get_supplier_name($fromsupp);
	$dec = user_price_dec();

	$cols = array(0, 40, 80, 190,	250, 320, 385, 450,	515);

	$headers = array(_('GRN'), _('Order'), _('Item') . '/' . _('Description'), _('Qty Recd'), _('qty Inv'), _('Balance'),
		_('Act Price'), _('Value'));

	$aligns = array('left',	'left',	'left',	'right', 'right', 'right', 'right', 'right');

	$params =   array( 	0 => $comments,
						1 => array('text' => _('Supplier'), 'from' => $from, 'to' => ''));

	$rep = new FrontReport(_('Outstanding GRNs Report'), 'OutstandingGRN', user_pagesize(), 9, $orientation);
	if ($orientation == 'L')
		recalculate_cols($cols);

	$rep->Font();
	$rep->Info($params, $cols, $headers, $aligns);
	$rep->NewPage();

	$Tot_Val=0;
	$Supplier = '';
	$SuppTot_Val=0;
	$res = getTransactions($fromsupp);

	while ($GRNs = db_fetch($res)) {
		$dec2 = get_qty_dec($GRNs['item_code']);
		if ($Supplier != $GRNs['supplier_id']) {
			if ($Supplier != '') {
				$rep->NewLine(2);
				$rep->TextCol(0, 7, _('Total'));
				$rep->AmountCol(7, 8, $SuppTot_Val, $dec);
				$rep->Line($rep->row - 2);
				$rep->NewLine(3);
				$SuppTot_Val = 0;
			}
			$rep->TextCol(0, 6, $GRNs['supp_name']);
			$Supplier = $GRNs['supplier_id'];
		}
		$rep->NewLine();
		$rep->TextCol(0, 1, $GRNs['id']);
		$rep->TextCol(1, 2, $GRNs['order_no']);
		$rep->TextCol(2, 3, $GRNs['item_code'] . '-' . $GRNs['description']);
		$rep->AmountCol(3, 4, $GRNs['qty_recd'], $dec2);
		$rep->AmountCol(4, 5, $GRNs['quantity_inv'], $dec2);
		$QtyOstg = $GRNs['qty_recd'] - $GRNs['quantity_inv'];
		$Value = ($GRNs['qty_recd'] - $GRNs['quantity_inv']) * $GRNs['act_price'];
		$rep->AmountCol(5, 6, $QtyOstg, $dec2);
		$rep->AmountCol(6, 7, $GRNs['act_price'], $dec);
		$rep->AmountCol(7, 8, $Value, $dec);
		$Tot_Val += $Value;
		$SuppTot_Val += $Value;

		$rep->NewLine(0, 1);
	}
	if ($Supplier != '') {
		$rep->NewLine();
		$rep->TextCol(0, 7, _('Total'));
		$rep->AmountCol(7, 8, $SuppTot_Val, $dec);
		$rep->Line($rep->row - 2);
		$rep->NewLine(3);
		$SuppTot_Val = 0;
	}
	$rep->NewLine(2);
	$rep->TextCol(0, 7, _('Grand Total'));
	$rep->AmountCol(7, 8, $Tot_Val, $dec);
	$rep->Line($rep->row - 2);
	$rep->NewLine();
	$rep->End();
}