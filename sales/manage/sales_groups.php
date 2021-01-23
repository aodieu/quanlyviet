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
$page_security = 'SA_SALESGROUP';
$path_to_root = '../..';
include($path_to_root . '/includes/session.inc');

page(_($help_context = 'Sales Groups'));

include($path_to_root . '/includes/ui.inc');

simple_page_mode(true);

if ($Mode=='ADD_ITEM' || $Mode=='UPDATE_ITEM')  {

	$input_error = 0;

	if (strlen($_POST['description']) == 0) {
		$input_error = 1;
		display_error(_('The sales group description cannot be empty.'));
		set_focus('description');
	}

	if ($input_error != 1) {
		if ($selected_id != -1) {
			update_sales_group($selected_id, $_POST['description']);
			$note = _('Selected sales group has been updated');
		} 
		else {
			add_sales_group($_POST['description']);
			$note = _('New sales group has been added');
		}
	
		display_notification($note);    	
		$Mode = 'RESET';
	}
} 

if ($Mode == 'Delete') {

	$cancel_delete = 0;

	// PREVENT DELETES IF DEPENDENT RECORDS IN 'debtors_master'

	if (key_in_foreign_table($selected_id, 'cust_branch', 'group_no')) {
		$cancel_delete = 1;
		display_error(_('Cannot delete this group because customers have been created using this group.'));
	} 
	if ($cancel_delete == 0) {
		delete_sales_group($selected_id);
		display_notification(_('Selected sales group has been deleted'));
	} //end if Delete group
	$Mode = 'RESET';
} 

if ($Mode == 'RESET') {
	$selected_id = -1;
	$sav = get_post('show_inactive');
	unset($_POST);
	if ($sav) $_POST['show_inactive'] = 1;
}

//-------------------------------------------------------------------------------------------------

$result = get_sales_groups(check_value('show_inactive'));

start_form();
start_table(TABLESTYLE, "width='30%'");
$th = array(_('ID'), _('Group Name'), '', '');
inactive_control_column($th);

table_header($th);
$k = 0; 

while ($myrow = db_fetch($result)) {
	
	alt_table_row_color($k);
		
	label_cell($myrow['id'], "nowrap align='right'");
	label_cell($myrow['description']);
	inactive_control_cell($myrow['id'], $myrow['inactive'], 'groups', 'id');
	edit_button_cell('Edit'.$myrow['id'], _('Edit'));
	delete_button_cell('Delete'.$myrow['id'], _('Delete'));
	end_row();
}

inactive_control_row($th);
end_table(1);

//-------------------------------------------------------------------------------------------------

start_table(TABLESTYLE2);

if ($selected_id != -1) {
	if ($Mode == 'Edit') {
		//editing an existing group
		$myrow = get_sales_group($selected_id);
		$_POST['description']  = $myrow['description'];
	}
	hidden('selected_id', $selected_id);
	label_row(_('ID'), $myrow['id']);
} 

text_row_ex(_('Group Name:'), 'description', 30); 

end_table(1);

submit_add_or_update_center($selected_id == -1, '', 'both');

end_form();

end_page();