<?php

namespace AdminBundle\Form\Type;

use CoreBundle\Entity\Photo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhotoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Photo::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('imageUrl', HiddenType::class,[
                'required' => false,
                'attr' => [
                    'class' => 'photo_type'
                ]
            ])->add('imageFile', FileType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'class' => 'collection_image_file'
            ]
        ]);
    }
}
