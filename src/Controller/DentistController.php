<?php

namespace App\Controller;

use App\Entity\Dentist;
use App\Repository\DentistRepository;
use App\Repository\TreatmentRepository;
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
        private TreatmentRepository $treatmentRepository,
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
            $payload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($payload)) {
                return new JsonResponse(['error' => 'Invalid JSON payload'], Response::HTTP_BAD_REQUEST);
            }

            $payloadForSerializer = $payload;
            unset($payloadForSerializer['treatments'], $payloadForSerializer['treatment']);

            $dentist = $this->serializer->deserialize(
                json_encode($payloadForSerializer, JSON_THROW_ON_ERROR),
                Dentist::class,
                'json',
                ['groups' => 'dentist:write']
            );

            $syncError = $this->syncTreatmentsFromPayload($dentist, $payload);
            if ($syncError !== null) {
                return $syncError;
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
        } catch (\JsonException) {
            return new JsonResponse(['error' => 'Invalid JSON payload'], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(Dentist $dentist, Request $request): JsonResponse
    {
        try {
            $payload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($payload)) {
                return new JsonResponse(['error' => 'Invalid JSON payload'], Response::HTTP_BAD_REQUEST);
            }

            $payloadForSerializer = $payload;
            unset($payloadForSerializer['treatments'], $payloadForSerializer['treatment']);

            $this->serializer->deserialize(
                json_encode($payloadForSerializer, JSON_THROW_ON_ERROR),
                Dentist::class,
                'json',
                ['object_to_populate' => $dentist, 'groups' => 'dentist:write']
            );

            $syncError = $this->syncTreatmentsFromPayload($dentist, $payload);
            if ($syncError !== null) {
                return $syncError;
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
        } catch (\JsonException) {
            return new JsonResponse(['error' => 'Invalid JSON payload'], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', methods: ['PATCH'])]
    #[IsGranted('ROLE_ADMIN')]
    public function patch(Dentist $dentist, Request $request): JsonResponse
    {
        try {
            $payload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($payload)) {
                return new JsonResponse(['error' => 'Invalid JSON payload'], Response::HTTP_BAD_REQUEST);
            }

            $payloadForSerializer = $payload;
            unset($payloadForSerializer['treatments'], $payloadForSerializer['treatment']);

            $this->serializer->deserialize(
                json_encode($payloadForSerializer, JSON_THROW_ON_ERROR),
                Dentist::class,
                'json',
                ['object_to_populate' => $dentist, 'groups' => 'dentist:write']
            );

            $syncError = $this->syncTreatmentsFromPayload($dentist, $payload);
            if ($syncError !== null) {
                return $syncError;
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
        } catch (\JsonException) {
            return new JsonResponse(['error' => 'Invalid JSON payload'], Response::HTTP_BAD_REQUEST);
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

    private function syncTreatmentsFromPayload(Dentist $dentist, array $payload): ?JsonResponse
    {
        $hasTreatments = array_key_exists('treatments', $payload);
        $hasLegacyTreatment = array_key_exists('treatment', $payload);

        if (!$hasTreatments && !$hasLegacyTreatment) {
            return null;
        }

        $treatmentIds = $hasTreatments ? $payload['treatments'] : [$payload['treatment']];

        if (!is_array($treatmentIds)) {
            return new JsonResponse(['error' => 'Treatments must be an array of ids'], Response::HTTP_BAD_REQUEST);
        }

        foreach ($dentist->getTreatments()->toArray() as $existingTreatment) {
            $dentist->removeTreatment($existingTreatment);
        }

        foreach ($treatmentIds as $treatmentId) {
            if (!is_numeric($treatmentId)) {
                return new JsonResponse(['error' => 'Each treatment id must be numeric'], Response::HTTP_BAD_REQUEST);
            }

            $treatment = $this->treatmentRepository->find((int) $treatmentId);
            if (!$treatment) {
                return new JsonResponse(['error' => sprintf('Treatment not found: %s', (string) $treatmentId)], Response::HTTP_BAD_REQUEST);
            }

            $dentist->addTreatment($treatment);
        }

        return null;
    }
}
