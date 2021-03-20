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

$today = Today();
$result = get_recurrent_invoices($today);
$title = _('Overdue Recurrent Invoices');

$widget = new Widget();
$widget->setTitle($title);
$widget->Start();

$th = array(_('Description'), _('Template No'), _('Customer'), _('Branch').'/'._('Group'), _('Next invoice'));
start_table(TABLESTYLE, "width='100%'");
table_header($th);

$k = 0;
while ($myrow = db_fetch($result)) {
	if (!$myrow['overdue'])
		continue;
	alt_table_row_color($k);

	label_cell($myrow['description']);
	label_cell(get_customer_trans_view_str(ST_SALESORDER, $myrow['order_no']));
	if ($myrow['debtor_no'] == 0) {
		label_cell('');
		label_cell(get_sales_group_name($myrow['group_no']));
	}
	else {
		label_cell(get_customer_name($myrow['debtor_no']));
		label_cell(get_branch_name($myrow['group_no']));
	}
	label_cell(calculate_next_invoice($myrow), "align='center'");
	end_row();
}
end_table();

$widget->End();