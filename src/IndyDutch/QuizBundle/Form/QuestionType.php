<?php

namespace IndyDutch\QuizBundle\Form;

use IndyDutch\QuizBundle\Entity\Category;
use IndyDutch\QuizBundle\QuizBundle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('questionText')
//        $builder->add('categories', 'entity_typeahead', array(
//            'class'  => 'MyBundle:User',
//            'render' => 'fullname',
//            'route'  => 'user_list',
//        ));
            ->add('categories', CollectionType::class, array(
                'entry_typeahead'   => Category::class,
                'render' => 'name',
                'route' => 'categories',
            ))
            ->add('save', SubmitType::class, array('label' => 'Create Question'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IndyDutch\QuizBundle\Entity\Question',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'indydutch_quizbundle_question';
    }
}
