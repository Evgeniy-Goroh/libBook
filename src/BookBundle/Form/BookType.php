<?php

namespace BookBundle\Form;

use BookBundle\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('author', TextType::class)
            ->add('cover', FileType::class, ['data_class' => null,'required' => false])
            ->add('file', FileType::class, ['data_class' => null,'required' => false])
            ->add('readIt', null, ['widget' => 'single_text'])
            ->add('allowDownload', CheckboxType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Book::class]);
    }
    
    public function getBlockPrefix()
    {
        return 'bookbundle_book';
    }
}
