<?php
// src/Entretien/PlatformBundle/Form/ImageType.php

namespace Entretien\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('url', TextType::class, array('empty_data' => 'https://lh3.googleusercontent.com/Iip-8Yn3PLAzecCMb4ZaHTvFObl3ETUWZmd5zLflhbB6BXKyNc5aM4hrGAA9NXSs7i0=w100', 'required' => false))
      ->add('alt', TextType::class, array('empty_data' => 'WHO IS THE DOCTOR s Name ?', 'required' => false));
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Entretien\PlatformBundle\Entity\Image'
    ));
  }
}
