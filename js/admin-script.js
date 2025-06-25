jQuery( document ).ready(function() {
				jQuery('body').on('change', '.changecat', function() {
					
					var selectedOption = jQuery(this);
					console.log(selectedOption.attr("iscal"));
			    	if(selectedOption.attr("iscal")=='yes'){
			    		jQuery(".showc_taxonomy_val").show();
			    		jQuery.ajax({
							    type: "post",
							    url: woo_ajax_object.ajax_url,
							    data: {action:"gmwqp_change_tax",option:jQuery(this).val()},
							    success: function(response){
							        jQuery(".changetax_val").html(response);
							    }
							});
			    	}else{
			    		jQuery(".showc_taxonomy_val").hide();
			    	}
			    });
			});

jQuery(document).ready(function($) {
    $('.changecat').change(function() {
        if ($(this).val() === 'all') {
            $('.showc_products').show();
        } else {
            $('.showc_products').hide();
        }
    });
});