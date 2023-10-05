<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Payment;
use App\Form\PaymentType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    #[Route('/campaign/{id}/payment', name: 'app_campaign_payment', methods: ['GET', 'POST'])]
    public function newPayment(Request $request, EntityManagerInterface $entityManager, Campaign $campaign): Response
    {
        if ($campaign->getTotal() >= $campaign->getGoal()) { // Si la cagnotte est pleine on redirige sur le show
            return $this->redirectToRoute('app_campaign_show', ['id' => $campaign->getId()], Response::HTTP_SEE_OTHER);

        } else {
            $payment = new Payment();
            
            $paymentForm = $this->createForm(PaymentType::class, $payment);
            $paymentForm->handleRequest($request);
    
            if ($paymentForm->isSubmitted() && $paymentForm->isValid()) {
                $payment->setCreatedAt(new DateTimeImmutable());
                $payment->setUpdatedAt(new DateTimeImmutable());
                $payment->getParticipant()->setCampaign($campaign);
                $payment->getParticipant()->getCampaign()->setUpdatedAt(new DateTimeImmutable());
                // $campaign->setUpdatedAt(new DateTimeImmutable());
                // dd($payment);
                $entityManager->persist($payment);
                $entityManager->flush();

                return $this->redirectToRoute('app_campaign_show', ['id' => $campaign->getId()], Response::HTTP_SEE_OTHER); 
            }
        }
        
        return $this->render('campaign/payment.html.twig', [
            'paymentForm' => $paymentForm,
        ]);
    }

}
