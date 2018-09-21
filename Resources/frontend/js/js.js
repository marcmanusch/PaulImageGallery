jQuery(function($) {
    $(document).on('click', '.lightboxgallery-gallery-item', function(event) {
        event.preventDefault();
        $(this).lightboxgallery({
            showCounter: true,
            showTitle: true,
            showDescription: true
        });
    });
});