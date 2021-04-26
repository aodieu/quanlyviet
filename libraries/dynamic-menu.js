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

$(function() {
	var $nav = $('div.collapsible-nav');
	var $btn = $('div.collapsible-nav button');
	var $vlinks = $('div.collapsible-nav .collapsible-menu');
	var $hlinks = $('div.collapsible-nav .hidden-menu-links');

	var numOfItems = 0;
	var totalSpace = 0;
	var breakWidths = [];

	// Get initial state
	$vlinks.children().outerWidth(function(i, w) {
		totalSpace += w;
		numOfItems += 1;
		breakWidths.push(totalSpace);
	});

	var availableSpace, numOfVisibleItems, requiredSpace;

	function check(minWidth) {
		// Get instant state
		availableSpace = $vlinks.width() - 50;
		numOfVisibleItems = $vlinks.children().length;
		requiredSpace = breakWidths[numOfVisibleItems - 1];

		// There is not enought space
		if (requiredSpace > availableSpace) {
			$vlinks.children().last().prependTo($hlinks);
			numOfVisibleItems -= 1;
			check();
		}// There is more than enough space
		else if (availableSpace > breakWidths[numOfVisibleItems]) {
			$hlinks.children().first().appendTo($vlinks);
			numOfVisibleItems += 1;
		}
		// Update the button accordingly
		$btn.attr("count", numOfItems - numOfVisibleItems);
		if (numOfVisibleItems === numOfItems) {
			$btn.attr('hidden', 'hidden');
			$hlinks.addClass('hidden');
		}
		else
			$btn.removeAttr('hidden');

		// if($(window).width() <= minWidth)
			// reset();
	}

	function reset() {
		$hlinks.children().appendTo($vlinks);
		$btn.attr('hidden', 'hidden');

		numOfVisibleItems = $vlinks.children().length;
	}

	$(window).on('resize load', function() {
		check(768);
	});
	
	$(window).on('click', function() {
		if ($(event.target).is($btn))
			$hlinks.toggleClass('hidden');
		else
			$hlinks.addClass('hidden');
	});

	check(768);
});