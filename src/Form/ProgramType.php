<?php

namespace App\Form;

use App\Entity\Program;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('synopsis', TextareaType::class)
            ->add('poster', FileType::class, [
                'label' => 'Poster (.jpg, .png, .bmp)',
                // неотображенное означает, что это поле не ассоциировано ни с одним свойством сущности
                'mapped' => false,
                // сделайте его необязательным, чтобы вам не нужно было повторно загружать файл
                // каждый раз, когда будете редактировать детали Product
                'required' => false,
                // неотображенные полля не могут определять свою валидацию используя аннотации
                // в ассоциированной сущности, поэтому вы можете использовать органичительные классы PHP
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpg',                            
                            'image/jpeg', 
                            'image/png', 
                            'image/bmp', 
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])            
            ->add('category', null, ['choice_label' => 'name'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}
