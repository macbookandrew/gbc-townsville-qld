jQuery(document).ready(function(){
    // collapse on page load
    jQuery('.entry-content h3').each(function(index){
        if (index > 0) {
            jQuery(this).nextUntil('h3').slideUp().addClass('hidden');
        } else {
            jQuery(this).nextUntil('h3').addClass('visible');
        }
    });

    // expand/collaps when clicked
    jQuery('.entry-content h3').on('click', function(){
        jQuery('.visible').slideUp().toggleClass('visible hidden');
        jQuery(this).nextUntil('h3').slideDown().toggleClass('visible hidden');
    });
});
