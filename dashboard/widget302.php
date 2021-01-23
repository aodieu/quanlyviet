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

$pg = new graph();

$width = 100;
$limit = 10;
$today = Today();

$result = stock_top($today, $limit);

$title = sprintf(_("Top %s Sold Items in fiscal year"), $limit);

$i = 0;
while ($myrow = db_fetch($result)) {

	$name = $myrow['description'];
	if ($pg != NULL) {
		$pg->x[$i] = $name; 
		$pg->y[$i] = $myrow['total'];
		$pg->z[$i] = $myrow['costs'];
	}	
	$i++;
}

$widget = new Widget();
$widget->setTitle($title);
$widget->Start();

if($widget->checkSecurity('SA_ITEMSTRANSVIEW'))
	source_graphic($title, _('Items'), $pg, _('Sales'), _('Costs'));

$widget->End();