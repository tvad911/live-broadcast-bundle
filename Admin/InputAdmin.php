<?php

namespace Martin1982\LiveBroadcastBundle\Admin;

use Martin1982\LiveBroadcastBundle\Entity\Input\InputFile;
use Martin1982\LiveBroadcastBundle\Entity\Input\InputRtmp;
use Martin1982\LiveBroadcastBundle\Entity\Input\InputUrl;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class InputAdmin.
 */
class InputAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'broadcast-input';

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $subject = $this->getSubject();

        $formMapper
            ->tab('General')
            ->with('General');

        if ($subject instanceof InputFile) {
            $formMapper->add('fileLocation', 'text', array('label' => 'File location on server'));
        }

        if ($subject instanceof InputUrl) {
            $formMapper->add('url', 'text', array('label' => 'URL to videofile'));
        }

        if ($subject instanceof InputRtmp) {
            $formMapper->add('streamKey', 'text', array('label' => 'Secret stream key'));
            $formMapper->add('issuedTo', 'text', array('label' => 'Key owner'));
        }

        $formMapper->end()
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('__toString', 'string', array('label' => 'Input'));
    }
}
