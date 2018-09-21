{extends file="parent:frontend/custom/index.tpl"}

{block name="frontend_custom_article_content"}
    {$smarty.block.parent}
    {if $sCustomPage.id == $paulGalleryPageID}
        {include file="frontend/custom/gallery.tpl"}
    {/if}
{/block}