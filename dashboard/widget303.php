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

$title = _('Items Below Reorder Level');

$widget = new Widget();
$widget->setTitle($title);
$widget->Start();

if($widget->checkSecurity('SA_ITEMSTRANSVIEW')) {
	$today = Today();
	$result = get_reorder_stock($today);
	
	$th = array(_('Item Code'), _('Item Name'), _('Stock Location'), _('Reorder Level'), _('QTY On Hand'));
	start_table(TABLESTYLE, "width='100%'");
	table_header($th);
	$k = 0;
	while ($myrow = db_fetch($result)) {
		alt_table_row_color($k);
		label_cell($myrow['stock_id']);
		label_cell($myrow['description']);
		label_cell($myrow['location_name']);
		qty_cell($myrow['reorder_level']);
        qty_cell($myrow['QtyOnHand']);
		end_row();
	}
	end_table();
}

$widget->End();
