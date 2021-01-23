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

	// This file contains all application entry points.

	// Transaction editors. First paraneter is transaction number, second (optional) transaction type.

$trans_editors = array(

	ST_JOURNAL => "/gl/gl_journal.php?ModifyGL=Yes&trans_no=%d&trans_type=%d",
	ST_BANKPAYMENT => "/gl/gl_bank.php?ModifyPayment=Yes&trans_no=%d&trans_type=%d",
	ST_BANKDEPOSIT => "/gl/gl_bank.php?ModifyDeposit=Yes&trans_no=%d&trans_type=%d",
	//ST_BANKTRANSFER => ,

	ST_SALESINVOICE => "/sales/customer_invoice.php?ModifyInvoice=%d",
//   11=>
// free hand (debtors_trans.order_==0)
// 	"/sales/credit_note_entry.php?ModifyCredit=%d"
// credit invoice
// 	"/sales/customer_credit_invoice.php?ModifyCredit=%d"
	ST_CUSTCREDIT =>  "/sales/customer_credit_invoice.php?ModifyCredit=%s",
	ST_CUSTPAYMENT =>  "/sales/customer_payments.php?trans_no=%d",
	ST_CUSTDELIVERY => "/sales/customer_delivery.php?ModifyDelivery=%d",

	//ST_LOCTRANSFER =>  ,
	//ST_INVADJUST =>  ,

	ST_PURCHORDER =>  "/purchasing/po_entry_items.php?ModifyOrderNumber=%d",
	ST_SUPPINVOICE => "/purchasing/supplier_invoice.php?ModifyInvoice=%d",

	//ST_SUPPCREDIT =>  ,
	//ST_SUPPAYMENT =>  ,
	//ST_SUPPRECEIVE => ,

	//ST_WORKORDER =>  ,
	//ST_MANUISSUE =>  ,
	//ST_MANURECEIVE =>  ,

	ST_SALESORDER => "/sales/sales_order_entry.php?ModifyOrderNumber=%d",
	ST_SALESQUOTE => "/sales/sales_order_entry.php?ModifyQuotationNumber=%d",
	//ST_COSTUPDATE =>  ,
	//ST_DIMENSION =>  ,
);