<?php

namespace App\Form;

use App\Entity\Actor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use App\Service\GlobalService;
use App\Entity\User;

class ActorType extends AbstractType
{
    private $user;

    public function __construct(GlobalService $user)
    {
        $this->user = $user->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('programs', null, ['choice_label' => 'title']);
        if ($this->user && in_array("ROLE_ADMIN", $this->user->getRoles())) {
            $builder
            ->add('photoFile', VichFileType::class, [
                'required'     => false,
                'allow_delete' => true, // not mandatory, default is true
                'download_uri' => true, // not mandatory, default is true
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Actor::class,
        ]);
    }
}
