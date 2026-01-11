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

function display_error($msg) {
	fa_trigger_error($msg, E_USER_ERROR);
}

function display_notification($msg) {
	fa_trigger_error($msg, E_USER_NOTICE);
}

function display_warning($msg) {
	fa_trigger_error($msg, E_USER_WARNING);
}

function display_notification_centered($msg) {
	display_notification($msg);
}

function display_caption($msg) {
	echo '<caption>'.$msg."</caption>\n";
}

function display_heading($msg) {
	echo "<center><span class='headingtext'>".$msg."</span></center>\n";
}

function display_heading2($msg) {
	echo "<center><span class='headingtext2'>".$msg."</span></center>\n";
}

function display_note($msg, $br=0, $br2=0, $extra='') {
	for ($i = 0; $i < $br; $i++)
		echo '<br>';
	if ($extra != '')	
		echo '<center><span '.$extra.'>'.$msg."</span></center>\n";	
	else	
		echo '<center><span>'.$msg."</span></center>\n";
	for ($i = 0; $i < $br2; $i++)
		echo '<br>';
}

function stock_item_heading($stock_id) {
	if ($stock_id != '') {
		$sql = "SELECT description, units FROM ".TB_PREF."stock_master WHERE stock_id=".db_escape($stock_id);
		$result = db_query($sql, 'could not get stock data');
		$myrow = db_fetch_row($result);
		
		display_heading($stock_id.' - '.$myrow[0]);
		display_heading2(_('in units of : ').$myrow[1]);
	}
}
