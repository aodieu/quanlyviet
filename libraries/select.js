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

var loadSelect2 = {
	'select': function(e) {

		if((e.hasAttribute('multiple') === false) && $(e).hasClass('nosearch') === false) {
			$(e).select2({
				dropdownAutoWidth : true,
				templateResult: function(item) {
					// replace(/</g, '&lt;') : prevent the code in the option’s value from being executed by the browser.
					// split('\n') : break a select option item into multi lines
					var selectionText = item.text.replace(/</g, '&lt;').split('\n');
					var returnString = $('<span></span>');
					$.each(selectionText, function(index, value){
						line = value === undefined ? '' : value;
						returnString.append(line + '</br>');
					})
						
					return returnString;
				}
			});
			$(e).on('select2:close', function() {
				$(this).focus();
			});
			$(e).on('select2:open', function(e2){

				$('.dynamic_combo_btn').remove();

				var target_id = $(e2.target).attr('id');
				var search_btn = $('#_'+target_id+'_search').clone();
				var add_btn = $('#_'+target_id+'_add').clone();
				$(search_btn).addClass('dynamic_combo_btn');
				$(add_btn).addClass('dynamic_combo_btn');
				$(search_btn).removeAttr('id hidden');
				$(add_btn).removeAttr('id hidden');

				$('.select2-dropdown').append(search_btn);
				if($(add_btn) !== 'undefined')
					$('.select2-dropdown').append(add_btn);
				$(search_btn).add(add_btn).click(function(){
					$('select').select2('close');
				});
				
			});
		}
	}
}
Behaviour.register(loadSelect2);