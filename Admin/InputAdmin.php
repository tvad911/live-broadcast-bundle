<?php

namespace Martin1982\LiveBroadcastBundle\Admin;

use Martin1982\LiveBroadcastBundle\Entity\Media\MediaFile;
use Martin1982\LiveBroadcastBundle\Entity\Media\MediaRtmp;
use Martin1982\LiveBroadcastBundle\Entity\Media\MediaUrl;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class InputAdmin
 * @package Martin1982\LiveBroadcastBundle\Admin
 */
class InputAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'broadcast-input';

    /**
     * {@inheritdoc}
     * @throws \RuntimeException
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $subject = $this->getSubject();

        $formMapper
            ->tab('General')
            ->with('General');

        if ($subject instanceof MediaFile) {
            $formMapper->add('fileLocation', 'text', array('label' => 'File location on server'));
        }

        if ($subject instanceof MediaUrl) {
            $formMapper->add('url', 'text', array('label' => 'URL to videofile'));
        }

        if ($subject instanceof MediaRtmp) {
            $formMapper->add('streamKey', 'text', array('label' => 'Secret stream key'));
            $formMapper->add('issuedTo', 'text', array('label' => 'Key owner'));
        }

        $formMapper->end()
            ->end();
    }

    /**
     * {@inheritdoc}
     * @throws \RuntimeException
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('__toString', 'string', array('label' => 'Input'));
    }
}
