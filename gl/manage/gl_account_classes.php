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
$page_security = 'SA_GLACCOUNTCLASS';
$path_to_root = '../..';
include($path_to_root.'/includes/session.inc');

page(_($help_context = 'GL Account Classes'));

include($path_to_root.'/gl/includes/gl_db.inc');

include($path_to_root.'/includes/ui.inc');

simple_page_mode(false);

//-----------------------------------------------------------------------------------

function can_process() {
	global $SysPrefs;

	if (strlen(trim($_POST['id'])) == 0) {
		display_error( _('The account class ID cannot be empty.'));
		set_focus('id');
		return false;
	}
	if (strlen(trim($_POST['name'])) == 0) {
		display_error( _('The account class name cannot be empty.'));
		set_focus('name');
		return false;
	}
	if (isset($SysPrefs->use_oldstyle_convert) && $SysPrefs->use_oldstyle_convert == 1)
		$_POST['Balance'] = check_value('Balance');
	return true;
}

//-----------------------------------------------------------------------------------

if ($Mode=='ADD_ITEM' || $Mode=='UPDATE_ITEM') {

	if (can_process()) {

		if ($selected_id != '') {
			if(update_account_class($selected_id, $_POST['name'], $_POST['ctype']))
				display_notification(_('Selected account class settings has been updated'));
		} 
		else {
			if(add_account_class($_POST['id'], $_POST['name'], $_POST['ctype'])) {
				display_notification(_('New account class has been added'));
				$Mode = 'RESET';
			}
		}
	}
}

//-----------------------------------------------------------------------------------

function can_delete($selected_id) {
	if ($selected_id == '')
		return false;
	if (key_in_foreign_table($selected_id, 'chart_types', 'class_id')) {
		display_error(_('Cannot delete this account class because GL account types have been created referring to it.'));
		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------------

if ($Mode == 'Delete') {

	if (can_delete($selected_id)) {
		delete_account_class($selected_id);
		display_notification(_('Selected account class has been deleted'));
	}
	$Mode = 'RESET';
}

//-----------------------------------------------------------------------------------

if ($Mode == 'RESET') {
	$selected_id = '';
	$_POST['id']  = '';
	$_POST['name']  = '';
	$_POST['ctype'] =  '';
}

//-----------------------------------------------------------------------------------

$result = get_account_classes(check_value('show_inactive'));

start_form();
start_table(TABLESTYLE);
$th = array(_('Class ID'), _('Class Name'), _('Class Type'), '', '');
if (isset($SysPrefs->use_oldstyle_convert) && $SysPrefs->use_oldstyle_convert == 1)
	$th[2] = _('Balance Sheet');
inactive_control_column($th);
table_header($th);

$k = 0;
while ($myrow = db_fetch($result)) {
	alt_table_row_color($k);

	label_cell($myrow['cid']);
	label_cell('<a href="./gl_account_types.php?cid='.$myrow["cid"].'">'.$myrow['class_name'].'</a>');
	if (isset($SysPrefs->use_oldstyle_convert) && $SysPrefs->use_oldstyle_convert == 1) {
		$myrow['ctype'] = ($myrow['ctype'] >= CL_ASSETS && $myrow['ctype'] < CL_INCOME ? 1 : 0);
		label_cell(($myrow['ctype'] == 1 ? _('Yes') : _('No')));
	}	
	else	
		label_cell($class_types[$myrow['ctype']]);
	inactive_control_cell($myrow['cid'], $myrow['inactive'], 'chart_class', 'cid');
	edit_button_cell('Edit'.$myrow['cid'], _('Edit'));
	delete_button_cell('Delete'.$myrow['cid'], _('Delete'));
	end_row();
}
inactive_control_row($th);
end_table(1);

//-----------------------------------------------------------------------------------

start_table(TABLESTYLE2);

if ($selected_id != '') {
	if ($Mode == 'Edit') {
		//editing an existing status code
		$myrow = get_account_class($selected_id);
	
		$_POST['id']  = $myrow['cid'];
		$_POST['name']  = $myrow['class_name'];
		if (isset($SysPrefs->use_oldstyle_convert) && $SysPrefs->use_oldstyle_convert == 1)
			$_POST['ctype'] = ($myrow['ctype'] >= CL_ASSETS && $myrow['ctype'] < CL_INCOME ? 1 : 0);
		else
			$_POST['ctype']  = $myrow['ctype'];
		hidden('selected_id', $selected_id);
	}
	hidden('id');
	label_row(_('Class ID:'), $_POST['id']);

} 
else
	text_row_ex(_('Class ID:'), 'id', 3);

text_row_ex(_('Class Name:'), 'name', 50, 60);

if (isset($SysPrefs->use_oldstyle_convert) && $SysPrefs->use_oldstyle_convert == 1)
	check_row(_('Balance Sheet'), 'ctype', null);
else
	class_types_list_row(_('Class Type:'), 'ctype', null);

end_table(1);

submit_add_or_update_center($selected_id == '', '', 'both');

end_form();

end_page();