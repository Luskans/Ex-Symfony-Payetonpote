<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Participant;
use App\Entity\Payment;
use App\Form\MixedType;
use App\Form\ParticipantType;
use App\Form\PaymentType;
use App\Repository\PaymentRepository;
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
        $participant = new Participant();

        // dummy code - add some example tags to the task
        // (otherwise, the template will render an empty list of tags)
        $payment = new Payment();
        $payment->setAmount(0);
        $participant->getPayments()->add($payment);
        // end dummy code

        $formParticipant = $this->createForm(ParticipantType::class, $participant);
        $formParticipant->handleRequest($request);

        if ($formParticipant->isSubmitted() && $formParticipant->isValid()) {

            if ($formParticipant->getData()->getPayments()[0]->getAmount() <= 0) {
                return $this->redirectToRoute('app_campaign_payment', ['id' => $campaign->getId()], Response::HTTP_SEE_OTHER);
                // renvoyer un message d'errur

            } else {
                $payment->setAmount($formParticipant->getData()->getPayments()[0]->getAmount());
                $payment->setCreatedAt(new DateTimeImmutable());
                $payment->setUpdatedAt(new DateTimeImmutable());
                $payment->setParticipant($participant);
                $participant->getPayments()->add($payment);
                $participant->setCampaign($campaign);

                $entityManager->persist($participant);
                $entityManager->flush();

                return $this->redirectToRoute('app_campaign_show', ['id' => $campaign->getId()], Response::HTTP_SEE_OTHER);
            }
        }
        
        
        return $this->render('campaign/payment.html.twig', [
            'formParticipant' => $formParticipant
        ]);
    }

}
