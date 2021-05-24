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
class SetupApp extends application {
	function __construct() {
		parent::__construct('system', _($this->help_context = 'S&etup'));

		$this->add_module(_('Company Setup'));
		$this->add_lapp_function(0, _('&Company Setup'), 'admin/company_preferences.php?', 'SA_SETUPCOMPANY', MENU_SETTINGS);
		$this->add_lapp_function(0, _('&User Accounts Setup'), 'admin/users.php?', 'SA_USERS', MENU_SETTINGS);
		$this->add_lapp_function(0, _('&Access Setup'), 'admin/security_roles.php?', 'SA_SECROLES', MENU_SETTINGS);
		$this->add_lapp_function(0, _('&Display Setup'), 'admin/display_prefs.php?', 'SA_SETUPDISPLAY', MENU_SETTINGS);
		$this->add_lapp_function(0, _('Transaction &References'), 'admin/forms_setup.php?', 'SA_FORMSETUP', MENU_SETTINGS);
		$this->add_rapp_function(0, _('&Taxes'), 'taxes/tax_types.php?', 'SA_TAXRATES', MENU_MAINTENANCE);
		$this->add_rapp_function(0, _('Tax &Groups'), 'taxes/tax_groups.php?', 'SA_TAXGROUPS', MENU_MAINTENANCE);
		$this->add_rapp_function(0, _('Item Ta&x Types'), 'taxes/item_tax_types.php?', 'SA_ITEMTAXTYPE', MENU_MAINTENANCE);
		$this->add_rapp_function(0, _('System and &General GL Setup'), 'admin/gl_setup.php?', 'SA_GLSETUP', MENU_SETTINGS);
		$this->add_rapp_function(0, _('&Fiscal Years'), 'admin/fiscalyears.php?', 'SA_FISCALYEARS', MENU_MAINTENANCE);
		$this->add_rapp_function(0, _('&Print Profiles'), 'admin/print_profiles.php?', 'SA_PRINTPROFILE', MENU_MAINTENANCE);

		$this->add_module(_('Miscellaneous'));
		$this->add_lapp_function(1, _('Pa&yment Terms'), 'admin/payment_terms.php?', 'SA_PAYTERMS', MENU_MAINTENANCE);
		$this->add_lapp_function(1, _('Shi&pping Company'), 'admin/shipping_companies.php?', 'SA_SHIPPING', MENU_MAINTENANCE);
		$this->add_rapp_function(1, _('&Points of Sale'), 'sales/manage/sales_points.php?', 'SA_POSSETUP', MENU_MAINTENANCE);
		$this->add_rapp_function(1, _('&Printers'), 'admin/printers.php?', 'SA_PRINTERS', MENU_MAINTENANCE);
		$this->add_rapp_function(1, _('Contact &Categories'), 'admin/crm_categories.php?', 'SA_CRMCATEGORY', MENU_MAINTENANCE);

		$this->add_module(_('Maintenance'));
		$this->add_lapp_function(2, _('&Void a Transaction'), 'admin/void_transaction.php?', 'SA_VOIDTRANSACTION', MENU_MAINTENANCE);
		$this->add_lapp_function(2, _('View or &Print Transactions'), 'admin/view_print_transaction.php?', 'SA_VIEWPRINTTRANSACTION', MENU_MAINTENANCE);
		$this->add_lapp_function(2, _('&Attach Documents'), 'admin/attachments.php?filterType=20', 'SA_ATTACHDOCUMENT', MENU_MAINTENANCE);
		$this->add_lapp_function(2, _('System &Diagnostics'), 'admin/system_diagnostics.php?', 'SA_SOFTWAREUPGRADE', MENU_SYSTEM);

		$this->add_rapp_function(2, _('&Backup and Restore'), 'admin/backups.php?', 'SA_BACKUP', MENU_SYSTEM);
		$this->add_rapp_function(2, _('Create/Update &Companies'), 'admin/create_coy.php?', 'SA_CREATECOMPANY', MENU_UPDATE);
		$this->add_rapp_function(2, _('Install/Update &Languages'), 'admin/inst_lang.php?', 'SA_CREATELANGUAGE', MENU_UPDATE);
		$this->add_rapp_function(2, _('Install/Activate &Extensions'), 'admin/inst_module.php?', 'SA_CREATEMODULES', MENU_UPDATE);
		$this->add_rapp_function(2, _('Install/Activate &Themes'), 'admin/inst_theme.php?', 'SA_CREATEMODULES', MENU_UPDATE);
		$this->add_rapp_function(2, _('Install/Activate &Chart of Accounts'), 'admin/inst_chart.php?', 'SA_CREATEMODULES', MENU_UPDATE);
		$this->add_rapp_function(2, _('Software &Upgrade'), 'admin/inst_upgrade.php?', 'SA_SOFTWAREUPGRADE', MENU_UPDATE);

		$this->add_extensions();
	}
}
