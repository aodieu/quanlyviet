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
include_once($path_to_root . '/admin/db/company_db.inc');

/*
	System and per company prefferences.
	Object is created only with site wide preffernces.
	After user login refresh method have to be called to re-initialize company specific settings.
*/
class sys_prefs {
	var $prefs;
	var $db_ok; // check whether database has been upgraded after src update.

	function __construct() {
		global $path_to_root;

		// retrieve set system wide preferences
		include $path_to_root.'/config.default.php';
		if (file_exists($path_to_root.'/config.php'))
			include $path_to_root.'/config.php';

		foreach(get_defined_vars() as $name => $value)
			$this->$name = $value;

		if (!@$this->time_zone)
			$this->time_zone = ini_get('date.timezone');

		if (!$this->time_zone)
			$this->time_zone = 'Europe/Berlin';
			
		if (!isset($this->use_popup_search))
			$this->use_popup_search = false;

		ini_set('date.timezone', $this->time_zone);
	}

	/*
		Re-initialize company preferrencies.
	*/
	function refresh() {
		global $db_version;

		$sql = "SELECT name, value FROM ".TB_PREF."sys_prefs";
		$result = @db_query($sql); // supress errors before 2.3 db structure upgrade

		if(!$result)
			return null;

		while($pref = db_fetch_assoc($result)) {
			$this->prefs[$pref['name']] = $pref['value'];
		}

		// update current db status for info in log file
		$this->db_ok = $this->prefs['version_id'] == $db_version;
	}

	function allow_negative_stock() {
		return $this->prefs['allow_negative_stock'];
	}

	/* Sherifoz 26.06.03 Proportion by which items can be received over the quantity that is specified in a purchase
	invoice
	The figure entered is interpreted as a percentage ie 10 means 0.1 or 10% not 10 times
	*/
	function over_receive_allowance() {
		return $this->prefs['po_over_receive'];
	}
	
	/* Proportion by which a purchase invoice line is an overcharge for a purchase order item received
	is an overcharge. If the overcharge is more than this percentage then an error is reported and
	purchase invoice line cannot be entered
	The figure entered is interpreted as a percentage ie 20 means 0.2 or 20% not 20 times
	*/	
	function over_charge_allowance() {
		return $this->prefs['po_over_charge'];
	}
	
	function default_credit_limit() {
		return $this->prefs['default_credit_limit'];
	}				
	
	function default_wo_required_by() {
		return $this->prefs['default_workorder_required'];
	}

	function default_quote_valid_days() {
		return $this->prefs['default_quote_valid_days'];
	}

	function default_delivery_required_by() {
		return $this->prefs['default_delivery_required'];
	}

	function default_receival_required_by() {
		return $this->prefs['default_receival_required'];
	}

	function default_dimension_required_by() {
		return $this->prefs['default_dim_required'];
	}	
	
	function auto_currency_revaluation() {
		return $this->prefs['auto_curr_reval'];
	}	
	
	function allocation_settled_allowance(){
		return $this->config_allocation_settled_allowance;
	}

	function no_zero_lines_amount() {
		return $this->prefs['no_zero_lines_amount'];
	}

	function show_po_item_codes() {
		return $this->prefs['show_po_item_codes'];
	}

	function accounts_alpha() {
		return $this->prefs['accounts_alpha'];
	}

	function loc_notification() {
		return $this->prefs['loc_notification'];
	}

	function print_invoice_no() {
		return $this->prefs['print_invoice_no'];
	}

	function allow_negative_prices() {
		return $this->prefs['allow_negative_prices'];
	}

	function print_item_images_on_quote() {
		return $this->prefs['print_item_images_on_quote'];
	}

	function alternative_tax_include_on_docs() {
		return $this->prefs['alternative_tax_include_on_docs'];
	}

	function suppress_tax_rates() {
		return $this->prefs['suppress_tax_rates'];
	}

	function backup_dir($comp=null){
		if (!isset($comp))
			$comp = user_company();

		if (isset($this->backup_path))
			return sprintf($this->backup_path, $comp);
		else
			return $this->comp_path.'/'.$comp."/backup/";
	}
}