<?php

namespace Website\StatisticsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class StatisticType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('createdAt', DateType::class, array(
            'label' => 'Date of Clicks',
            'required' => true,
            'widget' => 'single_text',
            'html5' => true,
            'format' => 'yyyy-MM-dd',
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'yyyy-mm-dd'
            ),
        ));

        $builder->add('clicks', NumberType::class, array(
            'label' => 'Clicks of Product',
            'required' => true,
            'attr' => array(
                'class' => 'form-control',
                'int' => true,
                'min' => '0',
                'max' => '100000',
                'step' => '1',
                'maxlength' => '5',
            ),
        ));

        $builder->add('productArticle', NumberType::class, array(
            'label' => 'Article Number',
            'required' => true,
            'attr' => array(
                'class' => 'form-control',
                'int' => true,
                'min' => '0',
                'max' => '10000000',
                'step' => '1',
                'maxlength' => '7',
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Website\StatisticsBundle\Entity\Statistic'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'website_statisticsbundle_statistic';
    }
}