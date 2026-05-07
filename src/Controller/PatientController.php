<?php

namespace App\Controller;

use App\Entity\Odontogram;
use App\Entity\Patient;
use App\Repository\PatientRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/patients')]
class PatientController extends AbstractController
{
    private const MAX_PROFILE_IMAGE_SIZE_BYTES = 5 * 1024 * 1024;
    private const ALLOWED_PROFILE_IMAGE_MIME_TYPES = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'image/webp',
    ];
    private const NATIONAL_ID_ALREADY_EXISTS_MESSAGE = 'National ID already exists.';

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
        // Only ROLE_AUXILIAR and ROLE_ADMIN can create patients
        if (!$this->isGranted('ROLE_AUXILIAR') && !$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['error' => 'Only auxiliars and admins can create patients'], Response::HTTP_FORBIDDEN);
        }

        try {
            $payload = $this->extractPatientPayload($request);
            $legacyImageError = $this->rejectLegacyProfileImagePayload($payload);
            if ($legacyImageError !== null) {
                return $legacyImageError;
            }

            $patient = $this->serializer->deserialize(
                json_encode($payload, JSON_THROW_ON_ERROR),
                Patient::class,
                'json',
                ['groups' => 'patient:write']
            );

            $profileImageError = $this->applyProfileImageUpload($patient, $request);
            if ($profileImageError !== null) {
                return $profileImageError;
            }

            $errors = $this->validator->validate($patient);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $duplicateResponse = $this->validateNationalIdUniqueness($patient);
            if ($duplicateResponse !== null) {
                return $duplicateResponse;
            }

            $odontogram = new Odontogram();
            $odontogram->setPatient($patient);
            $odontogram->setType($this->isChildPatient($patient) ? Odontogram::TYPE_CHILD : Odontogram::TYPE_ADULT);

            $this->entityManager->persist($patient);
            $this->entityManager->persist($odontogram);
            $this->entityManager->flush();

            $data = $this->serializer->serialize($patient, 'json', ['groups' => 'patient:read']);

            return JsonResponse::fromJsonString($data, Response::HTTP_CREATED);
        } catch (UniqueConstraintViolationException) {
            return $this->nationalIdAlreadyExistsResponse();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Patient $patient, Request $request): JsonResponse
    {
        // Only ROLE_AUXILIAR and ROLE_ADMIN can update patients
        if (!$this->isGranted('ROLE_AUXILIAR') && !$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['error' => 'Only auxiliars and admins can update patients'], Response::HTTP_FORBIDDEN);
        }

        try {
            $payload = $this->extractPatientPayload($request);
            $legacyImageError = $this->rejectLegacyProfileImagePayload($payload);
            if ($legacyImageError !== null) {
                return $legacyImageError;
            }

            $this->serializer->deserialize(
                json_encode($payload, JSON_THROW_ON_ERROR),
                Patient::class,
                'json',
                ['object_to_populate' => $patient, 'groups' => 'patient:write']
            );

            $profileImageError = $this->applyProfileImageUpload($patient, $request);
            if ($profileImageError !== null) {
                return $profileImageError;
            }

            $errors = $this->validator->validate($patient);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $duplicateResponse = $this->validateNationalIdUniqueness($patient, $patient->getId());
            if ($duplicateResponse !== null) {
                return $duplicateResponse;
            }

            $this->entityManager->flush();

            $data = $this->serializer->serialize($patient, 'json', ['groups' => 'patient:read']);

            return JsonResponse::fromJsonString($data);
        } catch (UniqueConstraintViolationException) {
            return $this->nationalIdAlreadyExistsResponse();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', methods: ['PATCH'])]
    public function patch(Patient $patient, Request $request): JsonResponse
    {
        // Only ROLE_AUXILIAR and ROLE_ADMIN can patch patients
        if (!$this->isGranted('ROLE_AUXILIAR') && !$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['error' => 'Only auxiliars and admins can update patients'], Response::HTTP_FORBIDDEN);
        }

        try {
            $payload = $this->extractPatientPayload($request);
            $legacyImageError = $this->rejectLegacyProfileImagePayload($payload);
            if ($legacyImageError !== null) {
                return $legacyImageError;
            }

            $this->serializer->deserialize(
                json_encode($payload, JSON_THROW_ON_ERROR),
                Patient::class,
                'json',
                ['object_to_populate' => $patient, 'groups' => 'patient:write']
            );

            $profileImageError = $this->applyProfileImageUpload($patient, $request);
            if ($profileImageError !== null) {
                return $profileImageError;
            }

            $errors = $this->validator->validate($patient);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $duplicateResponse = $this->validateNationalIdUniqueness($patient, $patient->getId());
            if ($duplicateResponse !== null) {
                return $duplicateResponse;
            }

            $this->entityManager->flush();

            $data = $this->serializer->serialize($patient, 'json', ['groups' => 'patient:read']);

            return JsonResponse::fromJsonString($data);
        } catch (UniqueConstraintViolationException) {
            return $this->nationalIdAlreadyExistsResponse();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Patient $patient): JsonResponse
    {
        // Only ROLE_AUXILIAR and ROLE_ADMIN can delete patients
        if (!$this->isGranted('ROLE_AUXILIAR') && !$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['error' => 'Only auxiliars and admins can delete patients'], Response::HTTP_FORBIDDEN);
        }

        try {
            foreach ($patient->getOdontograms()->toArray() as $odontogram) {
                $this->entityManager->remove($odontogram);
            }

            foreach ($patient->getAppointments()->toArray() as $appointment) {
                $this->entityManager->remove($appointment);
            }

            foreach ($patient->getDocuments()->toArray() as $document) {
                $this->entityManager->remove($document);
            }

            $this->entityManager->remove($patient);
            $this->entityManager->flush();
        } catch (ForeignKeyConstraintViolationException) {
            return new JsonResponse(
                ['error' => 'No se puede eliminar el paciente porque tiene registros relacionados.'],
                Response::HTTP_CONFLICT
            );
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse( ['message' => 'Patient deleted successfully'], Response::HTTP_OK);
    }

    #[Route('/{id}/profile-image', methods: ['PATCH', 'POST'])]
    public function uploadProfileImage(Patient $patient, Request $request): JsonResponse
    {
        try {
            $payload = $this->extractPatientPayload($request);
            $legacyImageError = $this->rejectLegacyProfileImagePayload($payload);
            if ($legacyImageError !== null) {
                return $legacyImageError;
            }

            $profileImageFile = $request->files->get('profileImageFile');
            if (!$profileImageFile instanceof UploadedFile) {
                return new JsonResponse(
                    ['error' => 'The field profileImageFile is required.'],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $fileValidationError = $this->validateProfileImageUploadedFile($profileImageFile);
            if ($fileValidationError !== null) {
                return $fileValidationError;
            }

            $patient->setProfileImageFile($profileImageFile);
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
        $patient->setProfileImageFile(null);
        $patient->setProfileImageName(null);
        $patient->setUpdatedAt(new \DateTimeImmutable());
        $this->entityManager->flush();

        $data = $this->serializer->serialize($patient, 'json', ['groups' => 'patient:read']);

        return JsonResponse::fromJsonString($data);
    }

    private function validateNationalIdUniqueness(Patient $patient, ?int $currentPatientId = null): ?JsonResponse
    {
        $nationalId = $patient->getNationalId();

        if (!is_string($nationalId) || trim($nationalId) === '') {
            return null;
        }

        $existingPatient = $this->patientRepository->findOneByNationalId($nationalId);

        if ($existingPatient === null) {
            return null;
        }

        if ($currentPatientId !== null && $existingPatient->getId() === $currentPatientId) {
            return null;
        }

        return $this->nationalIdAlreadyExistsResponse();
    }

    private function nationalIdAlreadyExistsResponse(): JsonResponse
    {
        return new JsonResponse(
            ['errors' => ['nationalId' => self::NATIONAL_ID_ALREADY_EXISTS_MESSAGE]],
            Response::HTTP_BAD_REQUEST
        );
    }

    private function extractPatientPayload(Request $request): array
    {
        $contentType = (string) $request->headers->get('Content-Type');
        if (str_starts_with($contentType, 'multipart/form-data')) {
            return $request->request->all();
        }

        $payload = json_decode($request->getContent(), true);
        if (!is_array($payload)) {
            throw new \InvalidArgumentException('Invalid JSON payload.');
        }

        return $payload;
    }

    private function rejectLegacyProfileImagePayload(array $payload): ?JsonResponse
    {
        if (array_key_exists('profileImage', $payload) || array_key_exists('profileImageName', $payload)) {
            return new JsonResponse(
                ['error' => 'Use profileImageFile with multipart/form-data. Text image payloads are no longer supported.'],
                Response::HTTP_BAD_REQUEST
            );
        }

        return null;
    }

    private function applyProfileImageUpload(Patient $patient, Request $request): ?JsonResponse
    {
        $profileImageFile = $request->files->get('profileImageFile');
        if ($profileImageFile === null) {
            return null;
        }

        if (!$profileImageFile instanceof UploadedFile) {
            return new JsonResponse(
                ['error' => 'Invalid profileImageFile upload.'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $fileValidationError = $this->validateProfileImageUploadedFile($profileImageFile);
        if ($fileValidationError !== null) {
            return $fileValidationError;
        }

        $patient->setProfileImageFile($profileImageFile);

        return null;
    }

    private function validateProfileImageUploadedFile(UploadedFile $profileImageFile): ?JsonResponse
    {
        if ($profileImageFile->getSize() !== null && $profileImageFile->getSize() > self::MAX_PROFILE_IMAGE_SIZE_BYTES) {
            return new JsonResponse(
                ['error' => 'Image too large. Maximum size is 5MB.'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $mimeType = $profileImageFile->getMimeType();
        if ($mimeType === null || !in_array($mimeType, self::ALLOWED_PROFILE_IMAGE_MIME_TYPES, true)) {
            return new JsonResponse(
                ['error' => 'Invalid image format. Allowed: PNG, JPG, JPEG, GIF, WEBP.'],
                Response::HTTP_BAD_REQUEST
            );
        }

        return null;
    }

    private function isChildPatient(Patient $patient): bool
    {
        $birthDate = $patient->getBirthDate();
        if ($birthDate === null) {
            return false;
        }

        $today = new \DateTimeImmutable('today');

        return $birthDate->diff($today)->y < 12;
    }
}
