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
function focus_alloc(i) {
	save_focus(i);
	i.setAttribute('_last', get_amount(i.name));
}

function blur_alloc(i) {
	var change = get_amount(i.name);
		
	if (i.name != 'amount' && i.name != 'charge' && i.name != 'discount')
		change = Math.min(change, get_amount('maxval'+i.name.substr(6), 1))

	price_format(i.name, change, user.pdec);
	if (i.name != 'amount' && i.name != 'charge') {
		if (change<0) change = 0;
		change = change-i.getAttribute('_last');
		if (i.name == 'discount')
			change = -change;

		var total = get_amount('amount')+change;
		price_format('amount', total, user.pdec, 0);
	}
}

function allocate_all(doc) {
	var amount = get_amount('amount'+doc);
	var unallocated = get_amount('un_allocated'+doc);
	var total = get_amount('amount');
	var left = 0;
	total -=  (amount-unallocated);
	left -= (amount-unallocated);
	amount = unallocated;
	if(left<0) {
		total  += left;
		amount += left;
		left = 0;
	}
	price_format('amount'+doc, amount, user.pdec);
	price_format('amount', total, user.pdec);
}

function allocate_none(doc) {
	amount = get_amount('amount'+doc);
	total = get_amount('amount');
	price_format('amount'+doc, 0, user.pdec);
	price_format('amount', total-amount, user.pdec);
}

var allocations = {
	'.amount': function(e) {
		if(e.name == 'allocated_amount' || e.name == 'bank_amount') {
			e.onblur = function() {
				var dec = this.getAttribute("dec");
				price_format(this.name, get_amount(this.name), dec);
			};
		}
		else {
			e.onblur = function() {
				blur_alloc(this);
			};
			e.onfocus = function() {
				focus_alloc(this);
			};
		}
	}
}

Behaviour.register(allocations);