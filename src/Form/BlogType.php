<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Blog;
use App\Entity\Category;
use App\Entity\User;
use App\Form\DataTransformer\TagTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\SecurityBundle\Security;

class BlogType extends AbstractType
{
    public function __construct(
        private readonly TagTransformer $tagTransformer,
        private readonly Security       $security,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'help' => 'Заполните заголовок текста',
                'attr' => [
                    'class' => 'myclass'
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
            ])
            ->add('text', TextareaType::class, [
                'required' => true,
            ]);

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder->add('category', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function ($repository) {
                    return $repository->createQueryBuilder('p')->orderBy('p.name', 'ASC');
                },
                'required' => false,
                'empty_data' => '',
//                'choice_label' => 'name',
                'placeholder' => '-- выбор категории --',
            ])
                ->add('user', EntityType::class, [
                    'class' => User::class,
                    'query_builder' => function ($repository) {
                        return $repository->createQueryBuilder('p')->orderBy('p.id', 'ASC');
                    },
                    'required' => false,
                    'empty_data' => '',
                    'choice_label' => 'emailFormatted',
                    'placeholder' => '-- выбор пользователя --',
                ]);
        }

        $builder->add('tags', TextType::class, array(
            'label' => 'Теги',
            'required' => false,
        ));

        $builder->get('tags')->addModelTransformer($this->tagTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => Blog::class,
            'csrf_protection' => false,
            'csrf_field_name' => '_token',
//             a unique key to help generate the secret token
            'intention' => 'task_item',
        ));
    }
}
