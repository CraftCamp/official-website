<?php

namespace App\Form\Repository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Developtech\AgilityBundle\Entity\Repository\GithubRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class GithubRepositoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('owner', TextType::class)
			->add('ownerType', ChoiceType::class, [
				'choices' => [
					'organization' => GithubRepository::OWNER_ORGANIZATION,
					'user' => GithubRepository::OWNER_USER
				]
			])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => GithubRepository::class
        ));
    }
}
