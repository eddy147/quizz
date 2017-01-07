<?php

namespace IndyDutch\QuizzBundle\Form;

use Doctrine\ORM\EntityRepository;
use IndyDutch\QuizzBundle\Entity\Answer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PossibleAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'answer', EntityType::class, [
                'class' => Answer::class,
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
            'data_class' => 'IndyDutch\QuizzBundle\Entity\PossibleAnswer'
        ));
    }

    public function getName()
    {
        return 'quizz_bundle_question_answer_type';
    }
}
