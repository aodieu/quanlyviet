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
function fixPNG(myImage) {
	var arVersion = navigator.appVersion.split("MSIE")
	var version = parseFloat(arVersion[1])
	if ((version >= 5.5) && (version < 7) && (document.body.filters)) {
		var imgID = (myImage.id) ? "id='" + myImage.id + "' " : "";
		var imgClass = (myImage.className) ? "class='" + myImage.className + "' " : "";
		var imgTitle = (myImage.title) ? "title='" + myImage.title  + "' " : "title='" + myImage.alt + "' ";
		var imgStyle = "display:inline-block;" + myImage.style.cssText;
		var strNewHTML = "<span " + imgID + imgClass + imgTitle + " style=width:" + myImage.width + "px; height:" + myImage.height + "px;" + imgStyle + ";filter:progid:DXImageTransform.Microsoft.AlphaImageLoader" + "(src='" + myImage.src + "', sizingMethod='scale');></span>";
		myImage.outerHTML = strNewHTML;
	}
}

function set_fullmode() {
	document.getElementById('ui_mode').value = 1;
	document.loginform.submit();
	return true;
}

function retry() {
	document.getElementById('ui_mode').value = 1;
	JsHttpRequest.request(this);
	return true;
}