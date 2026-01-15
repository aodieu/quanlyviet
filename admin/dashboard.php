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

$page_security = 'SA_SETUPDISPLAY';
$path_to_root = '..';

include_once($path_to_root.'/includes/session.inc');
include_once($path_to_root.'/includes/ui.inc');
include_once($path_to_root.'/reporting/includes/class.graphic.inc');
include_once($path_to_root.'/dashboard/includes/dashboard_classes.inc');

$js = '';
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(800, 500);

page(_($help_context = 'Dashboard'), false, false, '', $js);

if (isset($_GET['sel_app'])) {

	$selected_app = $_GET['sel_app'];

	if (!$_SESSION['wa_current_user']->check_application_access($selected_app))
		return;

	$dir = company_path().'/pdf_files';

	if ($d = @opendir($dir)) {
		while (($file = readdir($d)) !== false) {
			if (!is_file($dir.'/'.$file) || $file == 'index.php')
				continue;
			$ftime = filemtime($dir.'/'.$file);

			if (time()-$ftime > 180)
				unlink($dir.'/'.$file);
		}
		closedir($d);
	}
	
	$dashboard = new Dashboard;

	$dashboard->addDashboard(_('Customer'), DA_CUSTOMER);
	$dashboard->addWidget(DA_CUSTOMER, 101, WIDGET_HALF);
	$dashboard->addWidget(DA_CUSTOMER, 102, WIDGET_HALF);
	$dashboard->addWidget(DA_CUSTOMER, 103, WIDGET_HALF);
	$dashboard->addWidget(DA_CUSTOMER, 104, WIDGET_HALF);

	$dashboard->addDashboard(_('Supplier'), DA_SUPPLIER);
	$dashboard->addWidget(DA_SUPPLIER, 201, WIDGET_HALF);
	$dashboard->addWidget(DA_SUPPLIER, 202, WIDGET_HALF);
	$dashboard->addWidget(DA_SUPPLIER, 203, WIDGET_HALF);
	$dashboard->addWidget(DA_SUPPLIER, 204, WIDGET_HALF);

	$dashboard->addDashboard(_('Inventory'), DA_INVENTORY);
	$dashboard->addWidget(DA_INVENTORY, 301, WIDGET_HALF);
	$dashboard->addWidget(DA_INVENTORY, 302, WIDGET_HALF);
	$dashboard->addWidget(DA_INVENTORY, 303, WIDGET_HALF);
	$dashboard->addWidget(DA_INVENTORY, 304, WIDGET_HALF);

	$dashboard->addDashboard(_('Manufaturing'), DA_MANUFACTURE);
	$dashboard->addWidget(DA_MANUFACTURE, 401, WIDGET_HALF);
	$dashboard->addWidget(DA_MANUFACTURE, 402, WIDGET_HALF);

	$dashboard->addDashboard(_('Fixed Assets'), DA_FIXEDASSETS);
	$dashboard->addWidget(DA_FIXEDASSETS, 501, WIDGET_HALF);
	$dashboard->addWidget(DA_FIXEDASSETS, 502, WIDGET_HALF);

	$dashboard->addDashboard(_('Dimensions'), DA_DIMENSIONS);
	$dashboard->addWidget(DA_DIMENSIONS, 601, WIDGET_HALF);
	$dashboard->addWidget(DA_DIMENSIONS, 602, WIDGET_HALF);

	$dashboard->addDashboard(_('General Ledger'), DA_GL);
	$dashboard->addWidget(DA_GL, 701, WIDGET_HALF);
	$dashboard->addWidget(DA_GL, 702, WIDGET_HALF);
	$dashboard->addWidget(DA_GL, 703, WIDGET_HALF);
	$dashboard->addWidget(DA_GL, 704, WIDGET_HALF);

	$dashboard->addDashboard(_('Setup'), DA_SETUP);
	$dashboard->addWidget(DA_SETUP, 801, WIDGET_HALF);
	$dashboard->addWidget(DA_SETUP, 802, WIDGET_HALF);
	$dashboard->addWidget(DA_SETUP, 803, WIDGET_HALF);
	$dashboard->addWidget(DA_SETUP, 804, WIDGET_HALF);
	$dashboard->addWidget(DA_SETUP, 805, WIDGET_HALF);
	$dashboard->addWidget(DA_SETUP, 806, WIDGET_HALF);
	$dashboard->addWidget(DA_SETUP, 807, WIDGET_HALF);
	$dashboard->addWidget(DA_SETUP, 808, WIDGET_HALF);
	$dashboard->addWidget(DA_SETUP, 809, WIDGET_HALF);
	$dashboard->addWidget(DA_SETUP, 810, WIDGET_HALF);
	$dashboard->addWidget(DA_SETUP, 811, WIDGET_HALF);
	$dashboard->addWidget(DA_SETUP, 812, WIDGET_HALF);

	add_custom_dashboards($dashboard);

	echo $dashboard->display();
}
else
	display_error(_('Mising context data'));

end_page();
