<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\WeatherHistory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeatherHistoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('temperature')
            ->add('unit', ChoiceType::class, [
                'choices' => [
                    'celsius' => 'celsius',
                    'fahrenheit' => 'fahrenheit',
                ]
            ])
            ->add('created_at', null, [
                'widget' => 'single_text',
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WeatherHistory::class,
        ]);
    }
}
