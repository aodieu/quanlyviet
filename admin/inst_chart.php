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
$page_security = 'SA_CREATEMODULES';
$path_to_root = '..';
include_once($path_to_root.'/includes/session.inc');
include_once($path_to_root.'/includes/packages.inc');

if ($SysPrefs->use_popup_windows)
	$js = get_js_open_window(900, 500);

page(_($help_context = 'Install Charts of Accounts'), false, false, '', $js);

include_once($path_to_root.'/includes/date_functions.inc');
include_once($path_to_root.'/admin/db/company_db.inc');
include_once($path_to_root.'/admin/db/maintenance_db.inc');
include_once($path_to_root.'/includes/ui.inc');

//---------------------------------------------------------------------------------------------

if ($id = find_submit('Delete', false)) {
	$extensions = get_company_extensions();
	if (($extensions[$id]['type']=='chart') && uninstall_package($extensions[$id]['package'])) {
		unset($extensions[$id]);
		if (update_extensions($extensions)) {
			display_notification(_('Selected chart has been successfully deleted'));
			meta_forward($_SERVER['PHP_SELF']);
		}
	}
}

if ($id = find_submit('Update', false))
	install_extension($id);

//---------------------------------------------------------------------------------------------

function sortByOption($a, $b) {
	return strcmp($a['name'], $b['name']);
}

start_form(true);

div_start('ext_tbl');

$mods = get_charts_list();

if (!$mods)
	display_note(_('No optional chart of accounts is currently available.'));
else {
	uasort($mods, 'sortByOption');

	$th = array(_('Chart'),  _('Installed'), _('Available'), _('Encoding'), '', '');
	$k = 0;

	start_table(TABLESTYLE);
	table_header($th);
	foreach($mods as $pkg_name => $ext) {
		$available = @$ext['available'];
		$installed = @$ext['version'];
		$id = @$ext['local_id'];
		$encoding = @$ext['encoding'];

		alt_table_row_color($k);

		label_cell($available ? get_package_view_str($pkg_name, $ext['name']) : $ext['name']);

		label_cell($id === null ? _('None') : ($available && $installed ? $installed : _('Unknown')));
		label_cell($available ? $available : _('None'));
		label_cell($encoding ? $encoding : _('Unknown'));

		if ($available && check_pkg_upgrade($installed, $available)) // outdated or not installed theme in repo
			button_cell('Update'.$pkg_name, $installed ? _('Update') : _('Install'), _('Upload and install latest extension package'), ICON_DOWN);
		else
			label_cell('');

		if ($id !== null) {
			delete_button_cell('Delete'.$id, _('Delete'));
			submit_js_confirm('Delete'.$id, sprintf(_("You are about to remove package \'%s\'.\nDo you want to continue ?"), $ext['name']));
		}
		else
			label_cell('');

		end_row();
	}
	end_table(1);
}
div_end();

end_form();
end_page();