<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    #[Route('/order/new', name: 'order_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $order = new Order();

        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $order->setCreatedAt(new \DateTime());

            $order->setStatus('PENDING');

            $entityManager->persist($order);
            $entityManager->flush();

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
