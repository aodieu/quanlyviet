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
$page_security = 'SA_SOFTWAREUPGRADE';
$path_to_root = '../..';
include_once($path_to_root.'/includes/session.inc');
include_once($path_to_root.'/includes/packages.inc');

page(_($help_context = 'Log View'), true);

include_once($path_to_root.'/includes/ui.inc');

if (!isset($_GET['id'])) {
	/*Script was not passed the correct parameters */
	display_note(_('The script must be called with a valid company number.'));
	end_page();
}

display_heading(sprintf(_("Upgrade log for company '%s'"), $_GET['id']));
br();
start_table();
start_row();

$log = strtr(file_get_contents(VARLOG_PATH.'/upgrade.'.$_GET['id'].'.log'), array('Fatal error' => 'Fatal  error')); // prevent misinterpretation in output_handler
label_cells(null, nl2br(html_specials_encode($log)));
end_row();
end_table(1);

end_page(true);
