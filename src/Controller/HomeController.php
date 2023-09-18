<?php

namespace App\Controller;

use App\Repository\CampaignRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CampaignRepository $campaignRepository, ManagerRegistry $registery): Response
    {
        $campaigns = $campaignRepository->findFiveOrderByUpdatedAtDesc();

        return $this->render('home/home.html.twig', [
            'campaigns' => $campaigns
        ]);
    }
}
