<?php

namespace AdminBundle\Form\Type;


use CoreBundle\Entity\Coupon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CouponType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Coupon::class
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
                'label' => 'form.coupon.title',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.coupon.title'
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'form.coupon.status',
                'required' => true,
                'choices'  => [
                    'active' => 1,
                    'inactive' => 0
                ],
                'choice_label' => function($value, $key, $index) {
                    return sprintf("form.coupon.status_choices.%s", $key);
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('type', ChoiceType::class,[
                'label' => 'form.coupon.type',
                'choices'  => [
                    'SNS' => 1,
                    'Store' => 2
                ],
                'choice_label' => function($value, $key, $index) {
                    return sprintf("form.coupon.type_choices.%s", $key);
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('needLogin', ChoiceType::class,[
                'label' => 'form.coupon.need_login',
                'choices'  => [
                    'yes' => 1,
                    'no' => 0
                ],
                'choice_label' => function($value, $key, $index) {
                    return sprintf("form.coupon.need_login_choices.%s", $key);
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('hashTag', TextareaType::class, [
                'label' => 'form.coupon.hash_tag',
                'attr' => [
                    'rows' => '5',
                    'class' => 'form-control',
                    'placeholder' => 'form.coupon.hash_tag'
                ],
            ])
            ->add('size', IntegerType::class, [
                'label' => 'form.coupon.amount',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.coupon.amount'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'form.coupon.description',
                'attr' => [
                    'rows' => '5',
                    'class' => 'form-control',
                    'placeholder' => 'form.coupon.description'
                ],
            ])
            ->add('expiredTime', DateType::class, [
                'label' => 'form.coupon.expired_time',
                'format' => 'y-M-d',
                'years' => range(date('Y'), date('Y') + 10),
                'attr' => [
                    'class' => 'select_day',
                ],
            ])
            ->add('couponPhotos', CollectionType::class, [
                'label' => 'form.coupon.coupon_photos',
                'required' => false,
                'entry_type' => CouponPhotoType::class,
                'by_reference' => false,
                'show_when_empty' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('couponUserPhotos', CollectionType::class, [
                'label' => 'form.coupon.coupon_user_photos',
                'required' => false,
                'entry_type' => CouponUserPhotoType::class,
                'by_reference' => false,
                'show_when_empty' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ])->add('imageFile', FileType::class, [
                'label' => 'form.coupon.avatar',
                'attr' => [
                    'class' => 'avatar_file'
                ],
                'required' => false
            ])
            ->add('store', StoreSearchType::class, [
                'label' => false,
            ]);
    }
}