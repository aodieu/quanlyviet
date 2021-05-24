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
class InventoryApp extends application {
	function __construct() {
		parent::__construct('stock', _($this->help_context = '&Inventory'));

		$this->add_module(_('Transactions'));
		$this->add_lapp_function(0, _('Inventory Location &Transfers'), 'inventory/transfers.php?NewTransfer=1', 'SA_LOCATIONTRANSFER', MENU_TRANSACTION);
		$this->add_lapp_function(0, _('Inventory &Adjustments'), 'inventory/adjustments.php?NewAdjustment=1', 'SA_INVENTORYADJUSTMENT', MENU_TRANSACTION);

		$this->add_module(_('Inquiries and Reports'));
		$this->add_lapp_function(1, _('Inventory Item &Movements'), 'inventory/inquiry/stock_movements.php?', 'SA_ITEMSTRANSVIEW', MENU_INQUIRY);
		$this->add_lapp_function(1, _('Inventory Item &Status'), 'inventory/inquiry/stock_status.php?', 'SA_ITEMSSTATVIEW', MENU_INQUIRY);
		$this->add_rapp_function(1, _('Inventory &Reports'), 'reporting/reports_main.php?Class=2', 'SA_ITEMSTRANSVIEW', MENU_REPORT);

		$this->add_module(_('Maintenance'));
		$this->add_lapp_function(2, _('&Items'), 'inventory/manage/items.php?', 'SA_ITEM', MENU_ENTRY);
		$this->add_lapp_function(2, _('&Foreign Item Codes'), 'inventory/manage/item_codes.php?', 'SA_FORITEMCODE', MENU_MAINTENANCE);
		$this->add_lapp_function(2, _('Sales &Kits'), 'inventory/manage/sales_kits.php?', 'SA_SALESKIT', MENU_MAINTENANCE);
		$this->add_lapp_function(2, _('Item &Categories'), 'inventory/manage/item_categories.php?', 'SA_ITEMCATEGORY', MENU_MAINTENANCE);
		$this->add_rapp_function(2, _('Inventory &Locations'), 'inventory/manage/locations.php?', 'SA_INVENTORYLOCATION', MENU_MAINTENANCE);
		$this->add_rapp_function(2, _('&Units of Measure'), 'inventory/manage/item_units.php?', 'SA_UOM', MENU_MAINTENANCE);
		$this->add_rapp_function(2, _('&Reorder Levels'), 'inventory/reorder_level.php?', 'SA_REORDER', MENU_MAINTENANCE);

		$this->add_module(_('Pricing and Costs'));
		$this->add_lapp_function(3, _('Sales &Pricing'), 'inventory/prices.php?', 'SA_SALESPRICE', MENU_MAINTENANCE);
		$this->add_lapp_function(3, _('Purchasing &Pricing'), 'inventory/purchasing_data.php?', 'SA_PURCHASEPRICING', MENU_MAINTENANCE);
		$this->add_rapp_function(3, _('Standard &Costs'), 'inventory/cost_update.php?', 'SA_STANDARDCOST', MENU_MAINTENANCE);

		$this->add_extensions();
	}
}
