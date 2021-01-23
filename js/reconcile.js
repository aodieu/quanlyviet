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
function focus_amount(i) {
	save_focus(i);
	i.setAttribute('_last', get_amount(i.name));
}

function blur_amount(i) {
	var change = get_amount(i.name);

	price_format(i.name, change, user.pdec);
	change = change-i.getAttribute('_last');
	if (i.name=='beg_balance')
		change = -change;

	price_format('difference', get_amount('difference',1,1)+change, user.pdec, 1);
}

var balances = {
	'.amount': function(e) {
		e.onblur = function() {
			blur_amount(this);
		};
		e.onfocus = function() {
			focus_amount(this);
		};
	}
}

Behaviour.register(balances);