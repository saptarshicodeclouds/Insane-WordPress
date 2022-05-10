jQuery(document).ready(function () {
	
	jQuery('.portfolio_load_more').click(function (event) {
        console.log('Triggerd Hi');
    
        var post_no = jQuery('#hidden_post_no').val();
        var post_cat = jQuery('#hidden_post_cat').val();
        var post_type = jQuery('#hidden_post_type').val();
        var next_post_no = parseInt(post_no) + 6;

        jQuery(".loader_div").show();

        // JQuery Ajax Call
        jQuery.post('/wp-admin/admin-ajax.php', {
        	async 		: true,
        	action 		: 'portfolio_load_more',
            post_no 	: post_no,
            post_type	: post_type,
            event_type  : 'load',
		}, function (response) {
			var data = jQuery.parseJSON(response);

			if (data.status) {
				jQuery(".loader_div").hide();

				jQuery('#hidden_post_no').val(next_post_no);
				jQuery('#loadmoresearchresult').append(data.html);
			}				
		});

    });


});



var scrolled = false;
jQuery(window).scroll(function (e) {
    
    var hT = jQuery('.load-more-button').offset().top,
        hH = jQuery('.load-more-button').outerHeight(),
        wH = jQuery(window).height(),
        wS = jQuery(this).scrollTop();
    
    var hidden_post_no 		= parseInt(jQuery('#hidden_post_no').val());
    var hidden_total_post 	= parseInt(jQuery('#hidden_total_post').val());

    if (!scrolled && wS > (hT + hH - wH) && (hT > wS) && (wS + wH > hT + hH)) {
        scrolled = true;
        if (!(hidden_total_post <= hidden_post_no)) {
            jQuery(".portfolio_load_more").click();
            setTimeout(function () {
                scrolled = false;
            }, 800);
            e.preventDefault();
        }
    }
});