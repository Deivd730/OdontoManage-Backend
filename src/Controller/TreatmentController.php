<?php

namespace App\Controller;

use App\Repository\TreatmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/treatments')]
class TreatmentController extends AbstractController
{
    public function __construct(
        private TreatmentRepository $treatmentRepository,
        private SerializerInterface $serializer,
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $treatments = $this->treatmentRepository->findAll();
        $data = $this->serializer->serialize($treatments, 'json', ['groups' => 'treatment:read']);

        return JsonResponse::fromJsonString($data);
    }
}
