<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/patients')]
class PatientController extends AbstractController
{
    private const MAX_PROFILE_IMAGE_SIZE_BYTES = 5 * 1024 * 1024;

    public function __construct(
        private PatientRepository $patientRepository,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $patients = $this->patientRepository->findAll();
        $data = $this->serializer->serialize($patients, 'json', ['groups' => 'patient:read']);

        return JsonResponse::fromJsonString($data);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Patient $patient): JsonResponse
    {
        $data = $this->serializer->serialize($patient, 'json', ['groups' => 'patient:read']);

        return JsonResponse::fromJsonString($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $patient = $this->serializer->deserialize(
                $request->getContent(),
                Patient::class,
                'json',
                ['groups' => 'patient:write']
            );

            $errors = $this->validator->validate($patient);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->persist($patient);
            $this->entityManager->flush();

            $data = $this->serializer->serialize($patient, 'json', ['groups' => 'patient:read']);

            return JsonResponse::fromJsonString($data, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Patient $patient, Request $request): JsonResponse
    {
        try {
            $this->serializer->deserialize(
                $request->getContent(),
                Patient::class,
                'json',
                ['object_to_populate' => $patient, 'groups' => 'patient:write']
            );

            $errors = $this->validator->validate($patient);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->flush();

            $data = $this->serializer->serialize($patient, 'json', ['groups' => 'patient:read']);

            return JsonResponse::fromJsonString($data);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', methods: ['PATCH'])]
    public function patch(Patient $patient, Request $request): JsonResponse
    {
        try {
            $this->serializer->deserialize(
                $request->getContent(),
                Patient::class,
                'json',
                ['object_to_populate' => $patient, 'groups' => 'patient:write']
            );

            $errors = $this->validator->validate($patient);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->flush();

            $data = $this->serializer->serialize($patient, 'json', ['groups' => 'patient:read']);

            return JsonResponse::fromJsonString($data);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Patient $patient): JsonResponse
    {
        $this->entityManager->remove($patient);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}/profile-image', methods: ['PATCH'])]
    public function uploadProfileImage(Patient $patient, Request $request): JsonResponse
    {
        try {
            $payload = json_decode($request->getContent(), true);

            if (!is_array($payload)) {
                return new JsonResponse(
                    ['error' => 'Invalid JSON payload.'],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $profileImage = $payload['profileImage'] ?? $payload['profileImageName'] ?? null;

            if (!is_string($profileImage) || trim($profileImage) === '') {
                return new JsonResponse(
                    ['error' => 'The field profileImage is required.'],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $validationError = $this->validateBase64ProfileImage($profileImage);
            if ($validationError !== null) {
                return new JsonResponse(['error' => $validationError], Response::HTTP_BAD_REQUEST);
            }

            $patient->setProfileImageName($profileImage);
            $patient->setUpdatedAt(new \DateTimeImmutable());
            $this->entityManager->flush();

            $data = $this->serializer->serialize($patient, 'json', ['groups' => 'patient:read']);

            return JsonResponse::fromJsonString($data);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}/profile-image', methods: ['DELETE'])]
    public function removeProfileImage(Patient $patient): JsonResponse
    {
        $patient->setProfileImageName(null);
        $patient->setUpdatedAt(new \DateTimeImmutable());
        $this->entityManager->flush();

        $data = $this->serializer->serialize($patient, 'json', ['groups' => 'patient:read']);

        return JsonResponse::fromJsonString($data);
    }

    private function validateBase64ProfileImage(string $profileImage): ?string
    {
        if (!preg_match('/^data:image\/(png|jpe?g|gif|webp);base64,/', $profileImage)) {
            return 'Invalid image format. Allowed: PNG, JPG, JPEG, GIF, WEBP.';
        }

        $parts = explode(',', $profileImage, 2);
        if (count($parts) !== 2) {
            return 'Invalid base64 image payload.';
        }

        $decoded = base64_decode($parts[1], true);
        if ($decoded === false) {
            return 'Invalid base64 image content.';
        }

        if (strlen($decoded) > self::MAX_PROFILE_IMAGE_SIZE_BYTES) {
            return 'Image too large. Maximum size is 5MB.';
        }

        return null;
    }
}
