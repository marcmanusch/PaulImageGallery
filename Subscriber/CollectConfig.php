<?php
namespace PaulImageGallery\Subscriber;

use Enlight\Event\SubscriberInterface;
use PaulImageGallery\PaulImageGallery;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CollectConfig implements SubscriberInterface
{
    /** @var  ContainerInterface */
    private $container;

    /**
     * Frontend contructor.
     * @param ContainerInterface $container
     **/
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onPostDispatch',
        ];
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     */
    public function onPostDispatch(\Enlight_Event_EventArgs $args)
    {
        /** @var \Enlight_Controller_Action $controller */
        $controller = $args->get('subject');
        $view = $controller->View();
        $view->addTemplateDir($this->pluginDirectory . '/Resources/Views');
        $config = $this->container->get('shopware.plugin.config_reader')->getByPluginName('PaulImageGallery');

        // get plugin settings
        $active = $config['active'];
        $pageID = $config['pageID'];

        $view->assign('paulGalleryPageID', $pageID);

        //Werte aus View holen
        $currentPageID = $view->getAssign('sCustomPage')['id'];


        if ($pageID == $currentPageID) {

            /** @var \Doctrine\DBAL\Connection $connection */
            $connection = $this->container->get('dbal_connection');
            $builder = $this->getImagePathsQueryBuilder(PaulImageGallery::ALBUMNAME);
            $stmt = $builder->execute();
            $imagesResult = $stmt->fetchAll();

            $thumbArray = [];

            foreach ($imagesResult as $image) {

                // Get media-ids
                $context = Shopware()->Container()->get('shopware_storefront.context_service')->getShopContext();
                $media = Shopware()->Container()->get('shopware_storefront.media_service')->get($image['id'], $context);
                $mediaData = Shopware()->Container()->get('legacy_struct_converter')->convertMediaStruct($media);
                $thumbArray[] = $mediaData;
            }



            $view->assign('paulGalleryImages', $thumbArray);
        }

    }

    private function getImagePathsQueryBuilder($albumName)
    {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $this->container->get('dbal_connection');
        $builder = $connection->createQueryBuilder();
        $builder->select('album.name, album.id, media.path, media.created, media.albumID, media.id')
            ->from('s_media_album', 'album')
            ->innerJoin(
                'album',
                's_media',
                'media',
                'album.id = media.albumID'
            )
            ->where('album.name = \'' . $albumName . '\'')
            ->orderBy('created', 'DESC');
        return $builder;
    }
}