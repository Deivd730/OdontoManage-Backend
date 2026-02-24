<?php

namespace App\Controller;

use App\Entity\Dentist;
use App\Repository\DentistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/dentists')]
class DentistController extends AbstractController
{
    public function __construct(
        private DentistRepository $dentistRepository,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $dentists = $this->dentistRepository->findAll();
        $data = $this->serializer->serialize($dentists, 'json', ['groups' => 'dentist:read']);

        return JsonResponse::fromJson($data);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Dentist $dentist): JsonResponse
    {
        $data = $this->serializer->serialize($dentist, 'json', ['groups' => 'dentist:read']);

        return JsonResponse::fromJson($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $dentist = $this->serializer->deserialize(
                $request->getContent(),
                Dentist::class,
                'json',
                ['groups' => 'dentist:write']
            );

            $errors = $this->validator->validate($dentist);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->persist($dentist);
            $this->entityManager->flush();

            $data = $this->serializer->serialize($dentist, 'json', ['groups' => 'dentist:read']);

            return JsonResponse::fromJson($data, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', methods: ['PUT'])]
    #[IsGranted('ROLE_USER')]
    public function update(Dentist $dentist, Request $request): JsonResponse
    {
        try {
            $this->serializer->deserialize(
                $request->getContent(),
                Dentist::class,
                'json',
                ['object_to_populate' => $dentist, 'groups' => 'dentist:write']
            );

            $errors = $this->validator->validate($dentist);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->flush();

            $data = $this->serializer->serialize($dentist, 'json', ['groups' => 'dentist:read']);

            return JsonResponse::fromJson($data);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', methods: ['PATCH'])]
    #[IsGranted('ROLE_USER')]
    public function patch(Dentist $dentist, Request $request): JsonResponse
    {
        try {
            $this->serializer->deserialize(
                $request->getContent(),
                Dentist::class,
                'json',
                ['object_to_populate' => $dentist, 'groups' => 'dentist:write']
            );

            $errors = $this->validator->validate($dentist);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->flush();

            $data = $this->serializer->serialize($dentist, 'json', ['groups' => 'dentist:read']);

            return JsonResponse::fromJson($data);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Dentist $dentist): JsonResponse
    {
        $this->entityManager->remove($dentist);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
