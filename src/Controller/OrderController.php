<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpClient\HttpClient;

class OrderController extends AbstractController
{
    #[Route('/order/new', name: 'order_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $order = new Order();

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $order->setCreatedAt(new \DateTime());
            $order->setStatus('WAITING');

            $entityManager->persist($order);
            $entityManager->flush();

            $payload = [
                'order' => [
                    'id' => $order->getId(),
                    'product' => $order->getProduct()?->getName() ?? 'Test Product',
                    'payment_method' => $order->getPaymentMethod() ?? 'Stripe',
                    'status' => $order->getStatus(),
                    'client' => [
                        'firstname' => $order->getClient()?->getFirstname() ?? 'John',
                        'lastname' => $order->getClient()?->getLastname() ?? 'Doe',
                        'email' => $order->getClient()?->getEmail() ?? 'john.doe@example.com',
                    ],
                    'addresses' => [
                        'billing' => [
                            'address_line1' => $order->getBillingAddress()?->getStreet1() ?? '123 Rue Test',
                            'address_line2' => $order->getBillingAddress()?->getStreet2() ?? '',
                            'city' => $order->getBillingAddress()?->getCity() ?? 'Paris',
                            'zipcode' => $order->getBillingAddress()?->getZip() ?? '75000',
                            'country' => $order->getBillingAddress()?->getCountry() ?? 'France',
                            'phone' => '0123456789',
                        ],
                        'shipping' => [
                            'address_line1' => $order->getShippingAddress()?->getStreet1() ?? '123 Rue Test',
                            'address_line2' => $order->getShippingAddress()?->getStreet2() ?? '',
                            'city' => $order->getShippingAddress()?->getCity() ?? 'Paris',
                            'zipcode' => $order->getShippingAddress()?->getZip() ?? '75000',
                            'country' => $order->getShippingAddress()?->getCountry() ?? 'France',
                            'phone' => '0123456789',
                        ],
                    ],
                ],
            ];

            try {
                $client = HttpClient::create();
                $response = $client->request('POST', 'https://api-commerce.simplon-roanne.com/order', [
                    'headers' => [
                        'Authorization' => 'Bearer mJxTXVXMfRzLg6ZdhUhM4F6Eutcm1ZiPk4fNmvBMxyNR4ciRsc8v0hOmlzA0vTaX',
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'json' => $payload,
                ]);

                $data = $response->toArray();
                $order->setApiOrderId($data['id']);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur API : ' . $e->getMessage());
                return $this->redirectToRoute('order_new');
            }

            return $this->redirectToRoute('order_success');
        }

        return $this->render('order/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/order/success', name: 'order_success')]
    public function success(): Response
    {
        return $this->render('order/success.html.twig');
    }
}
