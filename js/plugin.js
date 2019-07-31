jQuery(document).ready(function($) {
	$(".ex-meta-unit select").change(function(){
		var thisval = $(this).parent().find(":selected").text();
		$(this).siblings('input').val(thisval);
		//alert(thisval);
	});
});