<?php

namespace App\Controller;

use App\Entity\Odontogram;
use App\Repository\AppointmentRepository;
use App\Repository\OdontogramRepository;
use App\Repository\PathologyRepository;
use App\Repository\PatientRepository;
use App\Repository\ToothRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/odontograms')]
class OdontogramController extends AbstractController
{
    public function __construct(
        private OdontogramRepository $odontogramRepository,
        private PatientRepository $patientRepository,
        private AppointmentRepository $appointmentRepository,
        private ToothRepository $toothRepository,
        private PathologyRepository $pathologyRepository,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $odontograms = $this->odontogramRepository->findAll();
        $data = $this->serializer->serialize($odontograms, 'json', ['groups' => 'odontogram:read']);

        return JsonResponse::fromJsonString($data);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Odontogram $odontogram): JsonResponse
    {
        $data = $this->serializer->serialize($odontogram, 'json', ['groups' => 'odontogram:read']);

        return JsonResponse::fromJsonString($data);
    }

    #[Route('/patient/{patientId}', methods: ['GET'])]
    public function getByPatient(int $patientId): JsonResponse
    {
        $patient = $this->patientRepository->find($patientId);
        
        if (!$patient) {
            return new JsonResponse(['error' => 'Patient not found'], Response::HTTP_NOT_FOUND);
        }

        $odontograms = $this->odontogramRepository->findBy(['patient' => $patient]);
        $data = $this->serializer->serialize($odontograms, 'json', ['groups' => 'odontogram:read']);

        return JsonResponse::fromJsonString($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            $odontogram = new Odontogram();
            
            // Handle patient
            if (isset($data['patient'])) {
                $patientId = is_numeric($data['patient']) ? $data['patient'] : null;
                if ($patientId) {
                    $patient = $this->patientRepository->find($patientId);
                    if (!$patient) {
                        return new JsonResponse(['error' => 'Patient not found'], Response::HTTP_NOT_FOUND);
                    }
                    $odontogram->setPatient($patient);
                }
            }
            
            // Handle appointment (optional)
            if (isset($data['appointment']) && $data['appointment'] !== null) {
                $appointmentId = is_numeric($data['appointment']) ? $data['appointment'] : null;
                if ($appointmentId) {
                    $appointment = $this->appointmentRepository->find($appointmentId);
                    if ($appointment) {
                        $odontogram->setAppointment($appointment);
                    }
                }
            }
            
            // Handle toothPathologies
            if (isset($data['toothPathologies']) && is_array($data['toothPathologies'])) {
                foreach ($data['toothPathologies'] as $tpData) {
                    $toothPathology = new \App\Entity\ToothPathology();
                    
                    // Get tooth
                    if (isset($tpData['tooth'])) {
                        $tooth = $this->toothRepository->find($tpData['tooth']);
                        if ($tooth) {
                            $toothPathology->setTooth($tooth);
                        }
                    }
                    
                    // Get pathology
                    if (isset($tpData['pathology'])) {
                        $pathology = $this->pathologyRepository->find($tpData['pathology']);
                        if ($pathology) {
                            $toothPathology->setPathology($pathology);
                        }
                    }
                    
                    // Set other fields
                    if (isset($tpData['toothFace'])) {
                        $toothPathology->setToothFace($tpData['toothFace']);
                    }
                    if (isset($tpData['status'])) {
                        $toothPathology->setStatus($tpData['status']);
                    }
                    
                    $odontogram->addToothPathology($toothPathology);
                }
            }

            $errors = $this->validator->validate($odontogram);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->persist($odontogram);
            $this->entityManager->flush();

            $data = $this->serializer->serialize($odontogram, 'json', ['groups' => 'odontogram:read']);

            return JsonResponse::fromJsonString($data, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Odontogram $odontogram, Request $request): JsonResponse
    {
        try {
            $this->serializer->deserialize(
                $request->getContent(),
                Odontogram::class,
                'json',
                ['object_to_populate' => $odontogram, 'groups' => 'odontogram:write']
            );

            $errors = $this->validator->validate($odontogram);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->flush();

            $data = $this->serializer->serialize($odontogram, 'json', ['groups' => 'odontogram:read']);

            return JsonResponse::fromJsonString($data);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', methods: ['PATCH'])]
    public function patch(Odontogram $odontogram, Request $request): JsonResponse
    {
        try {
            $this->serializer->deserialize(
                $request->getContent(),
                Odontogram::class,
                'json',
                ['object_to_populate' => $odontogram, 'groups' => 'odontogram:write']
            );

            $errors = $this->validator->validate($odontogram);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->flush();

            $data = $this->serializer->serialize($odontogram, 'json', ['groups' => 'odontogram:read']);

            return JsonResponse::fromJsonString($data);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Odontogram $odontogram): JsonResponse
    {
        $this->entityManager->remove($odontogram);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
