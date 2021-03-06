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
function focus_budget(i) {
	save_focus(i);
	i.setAttribute('_last', get_amount(i.name));
}

function blur_budget(i) {
	var amount = get_amount(i.name);
	var total = get_amount('Total', 1);
	
	price_format(i.name, amount, 0);
	price_format('Total', total+amount-i.getAttribute('_last'), 0, 1, 1);
}


var budget_calc = {
	'.amount': function(e) {
		e.onblur = function() {
			blur_budget(this);
		};
		e.onfocus = function() {
			focus_budget(this);
		};
	}
}

Behaviour.register(budget_calc);