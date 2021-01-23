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

$today = date2sql(Today());

$sql = "SELECT bank_act, bank_account_name, bank_curr_code, SUM(amount) balance FROM ".TB_PREF."bank_trans bt INNER JOIN ".TB_PREF."bank_accounts ba ON bt.bank_act = ba.id WHERE trans_date <= '$today' AND inactive <> 1 GROUP BY bank_act, bank_account_name ORDER BY bank_account_name";

$result = db_query($sql);

$title = _('Bank Account Balances');

$widget = new Widget();
$widget->setTitle($title);
$widget->Start();

$th = array(_('Account'), _('Currency'), _('Balance'));

if($widget->checkSecurity('SA_GLANALYTIC')) {

	start_table(TABLESTYLE, "width='100%'");
	table_header($th);
	$k = 0;
	while ($myrow = db_fetch($result)) {
		alt_table_row_color($k);
		label_cell(viewer_link($myrow['bank_account_name'], 'gl/inquiry/bank_inquiry.php?bank_account='.$myrow['bank_act']));
		label_cell($myrow['bank_curr_code']);
		amount_cell($myrow['balance']);
		end_row();
	}
	end_table();
}

$widget->End();