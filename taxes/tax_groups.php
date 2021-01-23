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
$page_security = 'SA_TAXGROUPS';
$path_to_root = '..';

include($path_to_root . '/includes/session.inc');

page(_($help_context = 'Tax Groups'));

include_once($path_to_root . '/includes/data_checks.inc');
include_once($path_to_root . '/includes/ui.inc');

include_once($path_to_root . '/taxes/db/tax_groups_db.inc');
include_once($path_to_root . '/taxes/db/tax_types_db.inc');

simple_page_mode(true);
	
check_db_has_tax_types(_('There are no tax types defined. Define tax types before defining tax groups.'));

//-----------------------------------------------------------------------------------

if ($Mode=='ADD_ITEM' || $Mode=='UPDATE_ITEM')  {

	//initialise no input errors assumed initially before we test
	$input_error = 0;

	if (strlen($_POST['name']) == 0) {
		$input_error = 1;
		display_error(_('The tax group name cannot be empty.'));
		set_focus('name');
	} 
	if ($input_error != 1) {

		// create an array of the taxes and array of rates
		$taxes = array();
		$tax_shippings = array();

		while (($id = find_submit('tax_type_id'))!=-1) {
			if (check_value('tax_type_id'.$id) != 0) {
				$taxes[] = $id;
				$tax_shippings[] = check_value('tax_shipping'.$id);
			}	
			unset($_POST['tax_type_id' . $id]);
			unset($_POST['tax_shipping' . $id]);
		}
		if ($selected_id != -1) {
			update_tax_group($selected_id, $_POST['name'], $taxes, $tax_shippings);
			display_notification(_('Selected tax group has been updated'));
		} 
		else {
			add_tax_group($_POST['name'], $taxes, $tax_shippings);
			display_notification(_('New tax group has been added'));
		}

		$Mode = 'RESET';
	}
}

//-----------------------------------------------------------------------------------

function can_delete($selected_id) {
	if ($selected_id == -1)
		return false;
	if (key_in_foreign_table($selected_id, 'cust_branch', 'tax_group_id')) {
		display_error(_('Cannot delete this tax group because customer branches been created referring to it.'));
		return false;
	}

	if (key_in_foreign_table($selected_id, 'suppliers', 'tax_group_id')) {
		display_error(_('Cannot delete this tax group because suppliers been created referring to it.'));
		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------------

if ($Mode == 'Delete') {

	if (can_delete($selected_id)) {
		delete_tax_group($selected_id);
		display_notification(_('Selected tax group has been deleted'));
	}
	$Mode = 'RESET';
}

if ($Mode == 'RESET') {
	$selected_id = -1;
	$sav = get_post('show_inactive');
	unset($_POST);
	if ($sav)
		$_POST['show_inactive'] = $sav;
}

//-----------------------------------------------------------------------------------

$result = get_all_tax_groups(check_value('show_inactive'));

start_form();

start_table(TABLESTYLE);
$th = array(_('Description'), '', '');
inactive_control_column($th);

table_header($th);

$k = 0;
while ($myrow = db_fetch($result)) {

	alt_table_row_color($k);

	label_cell($myrow['name']);

	inactive_control_cell($myrow['id'], $myrow['inactive'], 'tax_groups', 'id');
	edit_button_cell('Edit'.$myrow['id'], _('Edit'));
	delete_button_cell('Delete'.$myrow['id'], _('Delete'));
	end_row();
}

inactive_control_row($th);
end_table(1);

//-----------------------------------------------------------------------------------

start_table(TABLESTYLE2);

if ($selected_id != -1) {
	//editing an existing status code

	if ($Mode == 'Edit') {
		$group = get_tax_group($selected_id);

		$_POST['name']  = $group['name'];

	}
	hidden('selected_id', $selected_id);
}
text_row_ex(_('Description:'), 'name', 40);

end_table();

display_note(_('Select the taxes that are included in this group.'), 1, 1);

$items = get_tax_group_rates($selected_id!=-1 ? $selected_id : null);

start_table(TABLESTYLE2);
$th = array(_('Tax'), '', _('Shipping Tax'));
table_header($th);

while($item = db_fetch($items)) {
	start_row();
	if ($selected_id != -1) {
		check_cells($item['tax_type_name'], 'tax_type_id' . $item['tax_type_id'], isset($item['rate']), true, false, "align='center'");
		if (isset($item['rate']))
			check_cells(null, 'tax_shipping' . $item['tax_type_id'], $item['tax_shipping']);
	}
	else {
		check_cells($item['tax_type_name'], 'tax_type_id' . $item['tax_type_id'], 
			null, true, false, "align='center'");
		if (get_post('_tax_type_id' . $item['tax_type_id'].'_update'))	
			//$_POST['_tax_type_id' . $item['tax_type_id'].'_update'] = 0;
			$Ajax->activate('_page_body');
		
		if (check_value('tax_type_id' . $item['tax_type_id'])==1)
			check_cells(null, 'tax_shipping' . $item['tax_type_id'], null);
	}		
	end_row();
}

end_table(1);

submit_add_or_update_center($selected_id == -1, '', 'both');

end_form();

end_page();