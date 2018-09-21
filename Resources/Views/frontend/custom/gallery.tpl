<div class="container">
    <div class="lightboxgallery-gallery clearfix">

        {foreach key=i from=$paulGalleryImages item=image}
            <a class="lightboxgallery-gallery-item" target="_blank" href="{$image.thumbnails[2].source}">
                <div>
                    <img src="{$image.thumbnails[1].source}">
                </div>
            </a>
        {/foreach}

    </div>
</div>