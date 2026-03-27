<?php

namespace App\Controller;

use App\Entity\Dentist;
use App\Repository\DentistRepository;
use App\Repository\PathologyRepository;
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
        private PathologyRepository $pathologyRepository,
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

        return JsonResponse::fromJsonString($data);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Dentist $dentist): JsonResponse
    {
        $data = $this->serializer->serialize($dentist, 'json', ['groups' => 'dentist:read']);

        return JsonResponse::fromJsonString($data);
    }

    #[Route('', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): JsonResponse
    {
        try {
            $payload = json_decode($request->getContent(), true);
            if (!is_array($payload)) {
                return new JsonResponse(['error' => 'Invalid JSON payload'], Response::HTTP_BAD_REQUEST);
            }

            $dentist = $this->serializer->deserialize(
                $request->getContent(),
                Dentist::class,
                'json',
                ['groups' => 'dentist:write']
            );

            $pathologyError = $this->assignPathologyFromPayload($dentist, $payload);
            if ($pathologyError !== null) {
                return $pathologyError;
            }

            // Ensure dentist always has ROLE_DENTIST
            $dentist->setRoles(['ROLE_DENTIST']);

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

            return JsonResponse::fromJsonString($data, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(Dentist $dentist, Request $request): JsonResponse
    {
        try {
            $payload = json_decode($request->getContent(), true);
            if (!is_array($payload)) {
                return new JsonResponse(['error' => 'Invalid JSON payload'], Response::HTTP_BAD_REQUEST);
            }

            $this->serializer->deserialize(
                $request->getContent(),
                Dentist::class,
                'json',
                ['object_to_populate' => $dentist, 'groups' => 'dentist:write']
            );

            $pathologyError = $this->assignPathologyFromPayload($dentist, $payload);
            if ($pathologyError !== null) {
                return $pathologyError;
            }

            // Ensure dentist always has ROLE_DENTIST
            $dentist->setRoles(['ROLE_DENTIST']);

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

            return JsonResponse::fromJsonString($data);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', methods: ['PATCH'])]
    #[IsGranted('ROLE_ADMIN')]
    public function patch(Dentist $dentist, Request $request): JsonResponse
    {
        try {
            $payload = json_decode($request->getContent(), true);
            if (!is_array($payload)) {
                return new JsonResponse(['error' => 'Invalid JSON payload'], Response::HTTP_BAD_REQUEST);
            }

            $this->serializer->deserialize(
                $request->getContent(),
                Dentist::class,
                'json',
                ['object_to_populate' => $dentist, 'groups' => 'dentist:write']
            );

            $pathologyError = $this->assignPathologyFromPayload($dentist, $payload);
            if ($pathologyError !== null) {
                return $pathologyError;
            }

            // Ensure dentist always has ROLE_DENTIST
            $dentist->setRoles(['ROLE_DENTIST']);

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

            return JsonResponse::fromJsonString($data);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Dentist $dentist): JsonResponse
    {
        $this->entityManager->remove($dentist);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    private function assignPathologyFromPayload(Dentist $dentist, array $payload): ?JsonResponse
    {
        if (!array_key_exists('pathology', $payload) && !array_key_exists('pathologyId', $payload)) {
            return null;
        }

        $pathologyId = $payload['pathology'] ?? $payload['pathologyId'];
        if ($pathologyId === null || $pathologyId === '') {
            $dentist->setPathology(null);

            return null;
        }

        $pathology = $this->pathologyRepository->find((int) $pathologyId);
        if ($pathology === null) {
            return new JsonResponse(['error' => 'Pathology not found'], Response::HTTP_BAD_REQUEST);
        }

        $dentist->setPathology($pathology);

        return null;
    }
}
