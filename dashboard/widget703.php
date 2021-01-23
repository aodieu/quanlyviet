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
$today = Today();

$result = gl_top($today);

$title = _('Class Balances');

$i = 0;
while ($myrow = db_fetch($result)) {

	if ($myrow['ctype'] > 3) {
		$total += $myrow['total'];
		$myrow['total'] = -$myrow['total'];
		if ($pg != null) {
			$pg->x[$i] = $myrow['class_name']; 
			$pg->y[$i] = abs($myrow['total']);
		}	
		$i++;
	}
}
if ($pg != null) {
	$pg->x[$i] = $calculated; 
	$pg->y[$i] = -$total;
}

$widget = new Widget();
$widget->setTitle($title);
$widget->Start();

if($widget->checkSecurity('SA_GLANALYTIC'))
	source_graphic($title, _('Class'), $pg, _('Amount'), null, 5);

$widget->End();