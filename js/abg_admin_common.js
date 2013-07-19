jQuery(document).ready(function(){
	jQuery('input:radio[name="schedule_type"]').change(
    function(){
        if (jQuery(this).is(':checked') && jQuery(this).val() == 'simple') {
            jQuery('.abg-and-to').css('visibility','hidden');
            jQuery('.abg-and-from').css('visibility','hidden');
        }else{
        	jQuery('.abg-and-to').css('visibility','visible');
            jQuery('.abg-and-from').css('visibility','visible');
        }
    });
})