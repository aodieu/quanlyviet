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
$page_security = 'SA_WORKCENTRES';
$path_to_root = '../..';
include($path_to_root.'/includes/session.inc');

page(_($help_context = 'Work Centres'));

include($path_to_root.'/manufacturing/includes/manufacturing_db.inc');
include($path_to_root.'/includes/ui.inc');

simple_page_mode(true);

//-----------------------------------------------------------------------------------

if ($Mode=='ADD_ITEM' || $Mode=='UPDATE_ITEM') {

	//initialise no input errors assumed initially before we test
	$input_error = 0;

	if (strlen($_POST['name']) == 0) {
		$input_error = 1;
		display_error(_('The work centre name cannot be empty.'));
		set_focus('name');
	}

	if ($input_error != 1) {
		
		if ($selected_id != -1) {
			update_work_centre($selected_id, $_POST['name'], $_POST['description']);
			display_notification(_('Selected work center has been updated'));
		} 
		else {
			add_work_centre($_POST['name'], $_POST['description']);
			display_notification(_('New work center has been added'));
		}
		$Mode = 'RESET';
	}
} 

//-----------------------------------------------------------------------------------

function can_delete($selected_id) {
	if (key_in_foreign_table($selected_id, 'bom', 'workcentre_added')) {
		display_error(_('Cannot delete this work centre because BOMs have been created referring to it.'));
		return false;
	}

	if (key_in_foreign_table($selected_id, 'wo_requirements', 'workcentre')) {
		display_error(_('Cannot delete this work centre because work order requirements have been created referring to it.'));
		return false;
	}		
	
	return true;
}


//-----------------------------------------------------------------------------------

if ($Mode == 'Delete') {

	if (can_delete($selected_id)) {
		delete_work_centre($selected_id);
		display_notification(_('Selected work center has been deleted'));
	}
	$Mode = 'RESET';
}

if ($Mode == 'RESET') {
	$selected_id = -1;
	$sav = get_post('show_inactive');
	unset($_POST);
	$_POST['show_inactive'] = $sav;
}

//-----------------------------------------------------------------------------------

$result = get_all_work_centres(check_value('show_inactive'));

start_form();
start_table(TABLESTYLE, "width='50%'");
$th = array(_('Name'), _('description'), '', '');
inactive_control_column($th);
table_header($th);

$k = 0;
while ($myrow = db_fetch($result)) {
	
	alt_table_row_color($k);	

	label_cell($myrow['name']);
	label_cell($myrow['description']);
	inactive_control_cell($myrow['id'], $myrow['inactive'], 'workcentres', 'id');
	edit_button_cell('Edit'.$myrow['id'], _('Edit'));
	delete_button_cell('Delete'.$myrow['id'], _('Delete'));
	end_row();
}

inactive_control_row($th);
end_table(1);

//-----------------------------------------------------------------------------------

start_table(TABLESTYLE2);

if ($selected_id != -1) {
	if ($Mode == 'Edit') {
		//editing an existing status code
		$myrow = get_work_centre($selected_id);
		
		$_POST['name']  = $myrow['name'];
		$_POST['description']  = $myrow['description'];
	}
	hidden('selected_id', $selected_id);
} 

text_row_ex(_('Name:'), 'name', 40);
text_row_ex(_('Description:'), 'description', 50);

end_table(1);

submit_add_or_update_center($selected_id == -1, '', 'both');

end_form();

end_page();