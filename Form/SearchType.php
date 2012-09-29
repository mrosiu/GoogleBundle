<?php

namespace AntiMattr\GoogleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('search', 'text', array(
                'required'      => false,
                'label'         => 'google.bundle.search',
                'attr'          => array(
                ),
            ));
    }

    public function getName()
    {
        return 'GoogleBundleSearch';
    }
}

