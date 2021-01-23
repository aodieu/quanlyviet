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
include_once($path_to_root . '/includes/banking.inc');

include_once($path_to_root . '/gl/includes/db/gl_db_trans.inc');
include_once($path_to_root . '/gl/includes/db/gl_journal.inc');
include_once($path_to_root . '/gl/includes/db/gl_db_bank_trans.inc');
include_once($path_to_root . '/gl/includes/db/gl_db_banking.inc');
include_once($path_to_root . '/gl/includes/db/gl_db_bank_accounts.inc');
include_once($path_to_root . '/gl/includes/db/gl_db_currencies.inc');
include_once($path_to_root . '/gl/includes/db/gl_db_rates.inc');
include_once($path_to_root . '/gl/includes/db/gl_db_accounts.inc');
include_once($path_to_root . '/gl/includes/db/gl_db_account_types.inc');
//--------------------------------------------------------------------------------
