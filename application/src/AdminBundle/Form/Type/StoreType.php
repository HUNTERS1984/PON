<?php

namespace AdminBundle\Form\Type;

use CoreBundle\Entity\Store;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StoreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Store::class
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'form.store.title',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.store.title'
                ]
            ])
            ->add('operationStartTime', DateTimeType::class, [
                'label' => 'form.store.operation_start_time',
                'format' => 'y-M-d H:i',
                'required' => true,
            ])
            ->add('operationEndTime', DateTimeType::class, [
                'label' => 'form.store.operation_end_time',
                'format' => 'y-M-d H:i',
                'required' => true,
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'form.store.avatar',
                'required' => false,
                'attr' => [
                    'class' => 'avatar_file'
                ],
            ])
            ->add('tel', TextType::class, [
                'label' => 'form.store.tel',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.store.tel'
                ],
                'required' => true
            ])
            ->add('latitude', TextType::class, [
                'label' => 'form.store.latitude',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.store.latitude'
                ],
                'required' => true
            ])
            ->add('longitude', TextType::class, [
                'label' => 'form.store.longitude',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.store.longitude'
                ],
                'required' => true
            ])
            ->add('address', TextType::class, [
                'label' => 'form.store.address',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.store.address'
                ],
                'required' => true
            ])
            ->add('aveBill', IntegerType::class, [
                'label' => 'form.store.ave_bill',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.store.ave_bill'
                ],
                'required' => true
            ])
            ->add('closeDate', TextareaType::class, [
                'label' => 'form.store.close_date',
                'attr' => [
                    'rows' => '5',
                    'class' => 'form-control',
                    'placeholder' => 'form.store.close_date'
                ],
            ])
            ->add('helpText', TextareaType::class, [
                'label' => 'form.store.description',
                'attr' => [
                    'rows' => '5',
                    'class' => 'form-control',
                    'placeholder' => 'form.store.description'
                ],
            ])
            ->add('category', CategorySearchType::class, [
                'label' => false,
            ])
            ->add('storePhotos', CollectionType::class, [
                'label' => 'form.store.store_photos',
                'required' => false,
                'entry_type' => StorePhotoType::class,
                'by_reference' => false,
                'show_when_empty' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ]);
    }
}