<?php

namespace App\Form;

use App\Entity\Program;
use App\Entity\Actor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('synopsis', TextareaType::class)
            /*
            ->add('poster', FileType::class, [
                'label' => 'Poster (.jpg, .png, .bmp)',
                // неотображенное означает, что это поле не ассоциировано ни с одним свойством сущности
                'mapped' => false,
                // сделайте его необязательным, чтобы вам не нужно было повторно загружать файл
                // каждый раз, когда будете редактировать детали Product
                'required' => false,
                // неотображенные поля не могут определять свою валидацию используя аннотации
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
            ]) */
            ->add('posterFile', VichFileType::class, [
                'required'     => false,
                'allow_delete' => true, // not mandatory, default is true
                'download_uri' => true, // not mandatory, default is true
    ])
            ->add('category', null, ['choice_label' => 'name'])
        ;

        $builder->add('actors', EntityType::class, [
            'class' => Actor::class,
            'choice_label' => 'name',
            'multiple' => true,
            'expanded' => false,
            'by_reference' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}
