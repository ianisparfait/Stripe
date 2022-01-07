<?php

namespace App\Controller;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="payment")
     */
    public function index(): Response
    {
			return $this->render('payment/index.html.twig', [
				'controller_name' => 'PaymentController',
			]);
    }


    /**
     * @Route("/checkout", name="checkout")
     */
    public function checkout($stripeSK): Response
    {
			Stripe::setApiKey($stripeSK);

			$session = Session::create([
				'payment_method_types' => ['card'],
				'line_items'           => [
					[
						'price_data' => [
							'currency'     => 'usd',
							'product_data' => [
									'name' => 'T-shirt',
							],
							'unit_amount'  => 2000,
						],
						'quantity'   => 1,
					]
				],
				'mode' => 'payment',
				'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
				'cancel_url' => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
			]);

			/*

			mail: test@test.fr
			NUméro de carte: 4242 4242 4242 4242
			Expiration: date futur au hasar
			3-digit: 333
			nom: test
			pays: france

			*/

			return $this->redirect($session->url, 303);
    }


    /**
     * @Route("/success-url", name="success_url")
     */
    public function successUrl(): Response
    {
			return $this->render('payment/success.html.twig', []);
    }

		/**
     * @Route("/cancel-url", name="cancel_url")
     */
		public function cancelUrl(): Response
    {
			return $this->render('payment/cancel.html.twig', []);
    }
}