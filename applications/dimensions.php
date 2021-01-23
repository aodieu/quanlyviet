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
class dimensions_app extends application {
	function __construct() {
		$dim = get_company_pref('use_dimension');
		parent::__construct('proj', _($this->help_context = '&Dimensions'), $dim);

		if ($dim > 0) {
			$this->add_module(_('Transactions'));
			$this->add_lapp_function(0, _('Dimension &Entry'), 'dimensions/dimension_entry.php?', 'SA_DIMENSION', MENU_ENTRY);
			$this->add_lapp_function(0, _('&Outstanding Dimensions'), 'dimensions/inquiry/search_dimensions.php?outstanding_only=1', 'SA_DIMTRANSVIEW', MENU_TRANSACTION);

			$this->add_module(_('Inquiries and Reports'));
			$this->add_lapp_function(1, _('Dimension &Inquiry'), 'dimensions/inquiry/search_dimensions.php?', 'SA_DIMTRANSVIEW', MENU_INQUIRY);

			$this->add_rapp_function(1, _('Dimension &Reports'), 'reporting/reports_main.php?Class=4', 'SA_DIMENSIONREP', MENU_REPORT);
			
			$this->add_module(_('Maintenance'));
			$this->add_lapp_function(2, _('Dimension &Tags'), 'admin/tags.php?type=dimension', 'SA_DIMTAGS', MENU_MAINTENANCE);

			$this->add_extensions();
		}
	}
}

