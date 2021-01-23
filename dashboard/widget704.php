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

$weeks = 5;
$today = Today();

$result = gl_performance($today, $weeks);

$title = sprintf(_("Last %s weeks Performance"), $weeks);

$i = 0;
while ($myrow = db_fetch($result)) {
	$pg->x[$i] = $myrow['week_name']; 
	$pg->y[$i] = $myrow['sales'];
	$pg->z[$i] = $myrow['costs'];
	$i++;
}

$widget = new Widget();
$widget->setTitle($title);
$widget->Start();

if($widget->checkSecurity('SA_GLANALYTIC'))
	source_graphic($title, _('Week'), $pg, _('Sales'), _('Costs'), 1);

$widget->End();