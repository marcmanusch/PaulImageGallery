<?php

namespace PaulImageGallery;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PaulImageGallery extends Plugin
{
    const ALBUMNAME = 'Kundenbilder';

    /**
     * @param InstallContext $context
     */
    public function install(InstallContext $context)
    {
        $this->createAlbum(self::ALBUMNAME);
    }

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->setParameter('paul_image_gallery.plugin_dir', $this->getPath());
        parent::build($container);
    }


    public function createAlbum($albumName)
    {

        $sql = 'INSERT INTO s_media_album(name, position)
	SELECT ?, MAX(position) + 1 FROM s_media_album ;';

        Shopware()->Db()->query($sql, array($albumName));

        $albumId = Shopware()->Db()->lastInsertId();

        $sql = 'INSERT INTO s_media_album_settings(albumID, create_thumbnails, thumbnail_size, thumbnail_quality, thumbnail_high_dpi)
	VALUES(?, 1, "200x200;600x600;1280x1280", 90, 1)';

        Shopware()->Db()->query($sql, array($albumId));
    }

}