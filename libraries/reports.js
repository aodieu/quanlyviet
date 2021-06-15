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
var replinks = {
	'a, button': function(e) { // traverse menu
		e.onkeydown = function(ev) { 
			ev = ev||window.event;
			key = ev.keyCode||ev.which;
			if(key==37 || key==38 || key==39 || key==40) {
				move_focus(key, e, document.links);
				ev.returnValue = false;
				return false;
			}
		}
	},
	'a.repopts_link': function(e) {
		e.onclick = function() {
			save_focus(this);
			set_options(this);
			JsHttpRequest.request(this, null);
			return false;
		}
	},
	'a.repclass_link': function(e) {
		e.onclick = function() {
			save_focus(this);
			showClass(this.id.substring(5)); // id=classX
			return false;
		}
	},
}

function set_options(e) {
	var replinks = document.getElementsBySelector('a.repopts_link');
	for(var i in replinks)
		replinks[i].style.fontWeight = replinks[i]==e ? 'bold' : 'normal';
}

function showClass(pClass) {
	var classes = document.getElementsBySelector('.repclass');
	for(var i in  classes) {
		cl = classes[i];
		cl.style.display = (cl.id==('TAB_'+pClass)) ? "block" : "none";
	}
	var classlinks = document.getElementsBySelector('a.repclass_link');
	for(var i in classlinks) {
		if(classlinks[i].id == ('class'+pClass))
			classlinks[i].className = 'repclass_link repclass_link_selected';
		else
			classlinks[i].className = 'repclass_link';
	}

	set_options(); // clear optionset links
	document.getElementById('rep_form').innerHTML = '';
	return false;
}

Behaviour.register(replinks);
