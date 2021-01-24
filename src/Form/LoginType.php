<?php

declare(strict_types=1);

namespace App\Form;

use App\DataFixtures\AppFixtures;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

final class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $users = [
            AppFixtures::ADMIN_USERNAME,
            AppFixtures::MANAGER_USERNAME,
            AppFixtures::DEVELOPER_USERNAME,
        ];
        $builder
            ->add('username', ChoiceType::class, [
                'choices' => array_combine($users, $users),
            ])
            ->add('login', SubmitType::class);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
