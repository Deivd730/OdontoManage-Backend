<?php

namespace App\Controller;

use App\Entity\Box;
use App\Repository\BoxRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/boxes')]
class BoxController extends AbstractController
{
    public function __construct(
        private BoxRepository $boxRepository,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $boxes = $this->boxRepository->findAll();
        $data = $this->serializer->serialize($boxes, 'json', ['groups' => 'box:read']);

        return JsonResponse::fromJsonString($data);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Box $box): JsonResponse
    {
        $data = $this->serializer->serialize($box, 'json', ['groups' => 'box:read']);

        return JsonResponse::fromJsonString($data);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Box $box, Request $request): JsonResponse
    {
        try {
            $this->serializer->deserialize(
                $request->getContent(),
                Box::class,
                'json',
                ['object_to_populate' => $box, 'groups' => 'box:write']
            );

            $errors = $this->validator->validate($box);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->flush();

            $data = $this->serializer->serialize($box, 'json', ['groups' => 'box:read']);

            return JsonResponse::fromJsonString($data);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
