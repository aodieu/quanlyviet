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
class AssetsApp extends application {
	function __construct() {
		parent::__construct('assets', _($this->help_context = '&Fixed Assets'));
			
		$this->add_module(_('Transactions'));
		$this->add_lapp_function(0, _('Fixed Assets &Purchase'), 'purchasing/po_entry_items.php?NewInvoice=Yes&FixedAsset=1', 'SA_SUPPLIERINVOICE', MENU_TRANSACTION);
		$this->add_lapp_function(0, _('Fixed Assets Location &Transfers'), 'inventory/transfers.php?NewTransfer=1&FixedAsset=1', 'SA_ASSETTRANSFER', MENU_TRANSACTION);
		$this->add_lapp_function(0, _('Fixed Assets &Disposal'), 'inventory/adjustments.php?NewAdjustment=1&FixedAsset=1', 'SA_ASSETDISPOSAL', MENU_TRANSACTION);
		$this->add_lapp_function(0, _('Fixed Assets &Sale'), 'sales/sales_order_entry.php?NewInvoice=0&FixedAsset=1', 'SA_SALESINVOICE', MENU_TRANSACTION);
		$this->add_rapp_function(0, _('Process &Depreciation'), 'fixed_assets/process_depreciation.php', 'SA_DEPRECIATION', MENU_MAINTENANCE);
	// TODO: needs work
		//$this->add_rapp_function(0, _('Fixed Assets &Revaluation'),
	//		'inventory/cost_update.php?FixedAsset=1', 'SA_STANDARDCOST', MENU_MAINTENANCE);

		$this->add_module(_('Inquiries and Reports'));
		$this->add_lapp_function(1, _('Fixed Assets &Movements'), 'inventory/inquiry/stock_movements.php?FixedAsset=1', 'SA_ASSETSTRANSVIEW', MENU_INQUIRY);
		$this->add_lapp_function(1, _('Fixed Assets In&quiry'), 'fixed_assets/inquiry/stock_inquiry.php?', 'SA_ASSETSANALYTIC', MENU_INQUIRY);


		$this->add_rapp_function(1, _('Fixed Assets &Reports'), 'reporting/reports_main.php?Class=7', 'SA_ASSETSANALYTIC', MENU_REPORT);

		$this->add_module(_('Maintenance'));
		
		$this->add_lapp_function(2, _('Fixed &Assets'), 'inventory/manage/items.php?FixedAsset=1', 'SA_ASSET', MENU_ENTRY);
		$this->add_rapp_function(2, _('Fixed Assets &Locations'), 'inventory/manage/locations.php?FixedAsset=1', 'SA_INVENTORYLOCATION', MENU_MAINTENANCE);
		$this->add_rapp_function(2, _('Fixed Assets &Categories'), 'inventory/manage/item_categories.php?FixedAsset=1', 'SA_ASSETCATEGORY', MENU_MAINTENANCE);
		$this->add_rapp_function(2, _('Fixed Assets Cl&asses'), 'fixed_assets/fixed_asset_classes.php', 'SA_ASSETCLASS', MENU_MAINTENANCE);

		$this->add_extensions();
	}
}
