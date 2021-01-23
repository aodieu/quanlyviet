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
$page_security = 'SA_OPEN';
$path_to_root = '../..';
include_once($path_to_root . '/includes/session.inc');
include_once($path_to_root . '/includes/packages.inc');

page(_($help_context = 'Package Details'), true);

include_once($path_to_root . '/includes/ui.inc');

if (!isset($_GET['id']))  {
	/*Script was not passed the correct parameters */
	display_note(_('The script must be called with a valid package id to review the info for.'));
	end_page();
}

$filter = array(
	'Version' => _('Available version'), 
	'Type' => _('Package type'), 
	'Name' => _('Package content'),
	'Description' => _('Description'), 
	'Author' => _('Author'), 
	'Homepage' => _('Home page'),
	'Maintenance' => _('Package maintainer'),
	'InstallPath' => _('Installation path'),
	'Depends' => _('Minimal software versions'),
	'RTLDir' => _('Right to left'),
	'Encoding' => _('Charset encoding')
);

$pkg = get_package_info($_GET['id'], null, $filter);

display_heading(sprintf(_("Content information for package '%s'"), $_GET['id']));
br();
start_table(TABLESTYLE2, "width='80%'");
$th = array(_('Property'), _('Value'));
table_header($th);

foreach ($pkg as $field => $value) {
	if ($value == '')
		continue;
	start_row();
	label_cells($field, nl2br(html_specials_encode(is_array($value) ? implode("\n", $value) :$value)),
		 "class='tableheader2'");
	end_row();
}
end_table(1);

end_page(true);
