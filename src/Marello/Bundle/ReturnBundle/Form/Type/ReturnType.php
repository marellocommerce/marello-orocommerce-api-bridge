<?php

namespace Marello\Bundle\ReturnBundle\Form\Type;

use Marello\Bundle\ReturnBundle\Entity\ReturnEntity;
use Marello\Bundle\ReturnBundle\Form\EventListener\ReturnTypeSubscriber;
use Marello\Bundle\ReturnBundle\Validator\Constraints\ReturnEntityConstraint;
use Oro\Bundle\FormBundle\Form\Type\Select2ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReturnType extends AbstractType
{
    const BLOCK_PREFIX = 'marello_return';

    /**
     * @var ReturnTypeSubscriber
     */
    protected $returnTypeSubscriber;

    /**
     * @param ReturnTypeSubscriber $returnTypeSubscriber
     */
    public function __construct(ReturnTypeSubscriber $returnTypeSubscriber)
    {
        $this->returnTypeSubscriber = $returnTypeSubscriber;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'salesChannel',
            Select2ChoiceType::class,
            [
                'class' => 'MarelloSalesBundle:SalesChannel',
            ]
        );

        $builder->add('returnItems', ReturnItemCollectionType::class);

        $builder->addEventSubscriber($this->returnTypeSubscriber);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'  => ReturnEntity::class,
            'constraints' => [
                new ReturnEntityConstraint()
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return self::BLOCK_PREFIX;
    }
}
