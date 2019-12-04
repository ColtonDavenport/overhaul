
$(document).ready(function(){
	$(".browse-head").click(function(){
		var browseTable = $(this).siblings();
		$(browseTable).find('input:checked').prop("checked", false);
		browseTable.toggle();
	});
	$(".book-info no work here").click(function(){
		var book =$(this);
		if(book.prop("expanded")){
			// if expanded then shrink
			book.css({
				 "min-width": "30em", 
				 "width": "35%"
			 });
			// book.width("30%");
			// book.prop("min-width", "25em");
			$(book).find('h4, p').css("display", "block");
			$(book).find('.book-expand').hide();
			book.prop("expanded", false);
			
		} else {
			// if shrunk then expand
			book.css({
				 "min-width": "50em", 
				 "width": "93.5%"
			});
			//book.width("60%");
			// book.prop("min-width", "50em");
			$(book).find('h4, p').css("display", "inline-block");
			$(book).find('.book-expand').show();
			book.prop("expanded", true);
		}

	});
});