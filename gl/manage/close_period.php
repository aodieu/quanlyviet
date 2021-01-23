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

$page_security = 'SA_GLCLOSE';
$path_to_root = '../..';
include_once($path_to_root.'/includes/session.inc');

include_once($path_to_root.'/includes/date_functions.inc');
include_once($path_to_root.'/includes/ui.inc');
include_once($path_to_root.'/includes/banking.inc');
include_once($path_to_root.'/admin/db/fiscalyears_db.inc');

$js = '';
if (user_use_date_picker())
	$js .= get_js_date_picker();
page(_($help_context = 'Closing GL Transactions'), false, false, '', $js);

//---------------------------------------------------------------------------------------------
function check_data() {
	global $SysPrefs;
	
	if (!is_date($_POST['date']) || date1_greater_date2($_POST['date'], Today())) {
		display_error( _('The entered date is invalid.'));
		set_focus('date');
		return false;
	}
	if (!is_date_in_fiscalyears($_POST['date'], false)) {
		display_error(_('Selected date is not in fiscal year or the year is closed.'));
		set_focus('date');
		return false;
	}
	if (date1_greater_date2(sql2date(get_company_pref('gl_closing_date')), $_POST['date'])) {
		if (!$SysPrefs->allow_gl_reopen) {
			display_error(_('The entered date is earlier than date already selected as closing date.'));
			set_focus('date');
			return false;
		}
		elseif (!user_check_access('SA_GLREOPEN')) {
			display_error(_('You are not allowed to reopen already closed transactions.'));
			set_focus('date');
			return false;
		}
	}
	return true;
}

//---------------------------------------------------------------------------------------------

function handle_submit() {
	if (!check_data())
		return;
	if (!close_transactions($_POST['date']))
		display_notification(sprintf( _('All transactions resulting in GL accounts changes up to %s has been closed for further edition.'), sql2date(get_company_pref('gl_closing_date'))));
}


//---------------------------------------------------------------------------------------------

function clear_data() {
	unset($_POST['date_']);
}

//---------------------------------------------------------------------------------------------

if (get_post('submit'))
	handle_submit();
else
	display_note(_('Using this feature you can prevent entering new transactions <br>
	and disable edition of already entered transactions up to specified date.<br>
	Only transactions which can generate GL postings are subject to the constraint.'));

//---------------------------------------------------------------------------------------------

br(1);
start_form();
start_table(TABLESTYLE2);
if (!isset($_POST['date'])) {
	$cdate = sql2date(get_company_pref('gl_closing_date'));
	$_POST['date'] = $cdate ;// ? end_month(add_months($cdate, 1)) : Today();
}
date_row(_('End date of closing period:'), 'date');
end_table(1);

submit_center('submit', _('Close Transactions'), true, false);
end_form();

end_page();