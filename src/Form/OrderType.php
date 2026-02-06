<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\Client;
use App\Entity\Product;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('paymentMethod', ChoiceType::class, [
                'choices' => [
                    'Stripe' => 'Stripe',
                    'Paypal' => 'Paypal',
                ],
                'label' => 'Payment Method',
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'label' => 'Product',
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => function (Client $client) {
                    return $client->getFirstname() . ' ' . $client->getLastname();
                },
                'label' => 'Client',
            ])
            ->add('billingAddress', EntityType::class, [
                'class' => Address::class,
                'choice_label' => function (Address $address) {
                    return $address->getStreet1() . ', ' . $address->getCity();
                },
                'label' => 'Billing Address',
            ])
            ->add('shippingAddress', EntityType::class, [
                'class' => Address::class,
                'choice_label' => function (Address $address) {
                    return $address->getStreet1() . ', ' . $address->getCity();
                },
                'label' => 'Shipping Address',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
