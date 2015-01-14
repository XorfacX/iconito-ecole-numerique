
jQuery(document).ready(function($){

// Messages lors de la suppression
jQuery('.button-delete').click(function(event) {
    if (jQuery(this).hasClass('massAction')) {
        // Traitement de masse
        if (jQuery('#form input[type=checkbox]:checked').size() <= 0) {
            alert (i18n_groupe_check_members);
        } else {
            if(confirm(i18n_groupe_confirm_unsub_members)) {
                $('form#form').submit();
            }        
        }
    } else {
        // Traitement individuel
        jQuery('#form input[type=checkbox]').attr('checked', false);
        var idCheckbox = '#'+ $(this).attr('rel');
        jQuery(idCheckbox).attr('checked', true);
        if(confirm(i18n_groupe_confirm_unsub_members)) {
            $('form#form').submit();
        }
    }
	
	event.preventDefault();
});

});
