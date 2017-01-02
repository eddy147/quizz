<?php

namespace IndyDutch\QuizzBundle\Form;

use Doctrine\ORM\EntityRepository;
use IndyDutch\QuizzBundle\Entity\Choice;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionChoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'choice', EntityType::class, [
                'class' => Choice::class,
                'multiple' => true,
                'placeholder' => 'Select one of the options below:',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c');
                }
            ])
            ->add('correctAnswer', CheckboxType::class, [
                'label' => 'Correct Answer?',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IndyDutch\QuizzBundle\Entity\QuestionChoice'
        ));
    }

    public function getName()
    {
        return 'quizz_bundle_question_choice_type';
    }
}
