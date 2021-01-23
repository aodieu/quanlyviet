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
class suppliers_app extends application {
	function __construct() {
		parent::__construct('AP', _($this->help_context = '&Purchases'));

		$this->add_module(_('Transactions'));
		$this->add_lapp_function(0, _('Purchase &Order Entry'), 'purchasing/po_entry_items.php?NewOrder=Yes', 'SA_PURCHASEORDER', MENU_TRANSACTION);
		$this->add_lapp_function(0, _('&Outstanding Purchase Orders Maintenance'), 'purchasing/inquiry/po_search.php?', 'SA_GRN', MENU_TRANSACTION);
		$this->add_lapp_function(0, _('Direct &GRN'), 'purchasing/po_entry_items.php?NewGRN=Yes', 'SA_GRN', MENU_TRANSACTION);
		$this->add_lapp_function(0, _('Direct Supplier &Invoice'), 'purchasing/po_entry_items.php?NewInvoice=Yes', 'SA_SUPPLIERINVOICE', MENU_TRANSACTION);

		$this->add_rapp_function(0, _('&Payments to Suppliers'), 'purchasing/supplier_payment.php?', 'SA_SUPPLIERPAYMNT', MENU_TRANSACTION);
		$this->add_rapp_function(0, '','');
		$this->add_rapp_function(0, _('Supplier &Invoices'), 'purchasing/supplier_invoice.php?New=1', 'SA_SUPPLIERINVOICE', MENU_TRANSACTION);
		$this->add_rapp_function(0, _('Supplier &Credit Notes'), 'purchasing/supplier_credit.php?New=1', 'SA_SUPPLIERCREDIT', MENU_TRANSACTION);
		$this->add_rapp_function(0, _('&Allocate Supplier Payments or Credit Notes'), 'purchasing/allocations/supplier_allocation_main.php?', 'SA_SUPPLIERALLOC', MENU_TRANSACTION);

		$this->add_module(_('Inquiries and Reports'));
		$this->add_lapp_function(1, _('Purchase Orders &Inquiry'), 'purchasing/inquiry/po_search_completed.php?', 'SA_SUPPTRANSVIEW', MENU_INQUIRY);
		$this->add_lapp_function(1, _('Supplier Transaction &Inquiry'), 'purchasing/inquiry/supplier_inquiry.php?', 'SA_SUPPTRANSVIEW', MENU_INQUIRY);
		$this->add_lapp_function(1, _('Supplier Allocation &Inquiry'), 'purchasing/inquiry/supplier_allocation_inquiry.php?', 'SA_SUPPLIERALLOC', MENU_INQUIRY);

		$this->add_rapp_function(1, _('Supplier and Purchasing &Reports'), 'reporting/reports_main.php?Class=1', 'SA_SUPPTRANSVIEW', MENU_REPORT);

		$this->add_module(_('Maintenance'));
		$this->add_lapp_function(2, _('&Suppliers'), 'purchasing/manage/suppliers.php?', 'SA_SUPPLIER', MENU_ENTRY);

		$this->add_extensions();
	}
}


