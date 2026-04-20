<?php

namespace App\Controller;

use App\Entity\Pathology;
use App\Entity\Odontogram;
use App\Entity\ToothTreatment;
use App\Entity\BridgeTreatment;
use App\Repository\AppointmentRepository;
use App\Repository\OdontogramRepository;
use App\Repository\PathologyRepository;
use App\Repository\PatientRepository;
use App\Repository\ToothRepository;
use App\Repository\TreatmentRepository;
use App\Repository\BridgeTreatmentRepository;
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
        private TreatmentRepository $treatmentRepository,
        private BridgeTreatmentRepository $bridgeTreatmentRepository,
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
        // Only ROLE_DENTIST and ROLE_ADMIN can create odontograms
        if (!$this->isGranted('ROLE_DENTIST') && !$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['error' => 'Only dentists and admins can create odontograms'], Response::HTTP_FORBIDDEN);
        }

        try {
            $data = json_decode($request->getContent(), true);
            $odontogram = new Odontogram();
                        
            if (isset($data['patient'])) {
                $pData = $data['patient'];
                $patientId = is_array($pData) ? $pData['id'] : (is_numeric($pData) ? $pData : basename($pData));
                $patient = $this->patientRepository->find($patientId);
                if (!$patient) return new JsonResponse(['error' => 'Patient not found'], 404);
                $odontogram->setPatient($patient);
                $odontogram->setType($this->isChildPatient($patient) ? Odontogram::TYPE_CHILD : Odontogram::TYPE_ADULT);
            }
            
           
            if (isset($data['appointment'])) {
                $appointmentData = $data['appointment'];
                $appointmentId = is_array($appointmentData) ? $appointmentData['id'] : (is_numeric($appointmentData) ? $appointmentData : basename($appointmentData));
                $appointment = $this->appointmentRepository->find($appointmentId);
                if (!$appointment) {
                    return new JsonResponse(['error' => 'Appointment not found'], 404);
                }
                $odontogram->setAppointment($appointment);
            }
            
            if (isset($data['toothPathologies']) && is_array($data['toothPathologies'])) {
                foreach ($data['toothPathologies'] as $tpData) {
                    $toothPathology = new \App\Entity\ToothPathology();

                    $toothNumber = $tpData['tooth']['toothNumber'] ?? null;
                    $tooth = $this->toothRepository->findOneBy(['toothNumber' => $toothNumber]);
                    if (!$tooth) {
                        return new JsonResponse(['error' => "El diente número $toothNumber no existe en la base de datos"], 400);
                    }

                    $pathology = $this->resolvePathologyFromPayload($tpData);
                    if (!$pathology) {
                        $pathologyId = $tpData['pathology']['id'] ?? ($tpData['pathologyId'] ?? null);
                        return new JsonResponse(['error' => "La patología ID $pathologyId no existe"], 400);
                    }

                    $toothPathology->setTooth($tooth);
                    $toothPathology->setPathology($pathology);
                    $toothPathology->setToothFace($tpData['toothFace'] ?? 0);
                    $odontogram->addToothPathology($toothPathology);
                }
            }

            if (isset($data['toothTreatments']) && is_array($data['toothTreatments'])) {
                foreach ($data['toothTreatments'] as $ttData) {
                    $toothTreatment = new ToothTreatment();

                    $treatment = $this->treatmentRepository->find($ttData['treatment']['id'] ?? $ttData['treatment'] ?? null);
                    if (!$treatment) {
                        $treatmentId = $ttData['treatment']['id'] ?? $ttData['treatment'] ?? null;
                        return new JsonResponse(['error' => "El tratamiento ID $treatmentId no existe"], 400);
                    }

                    $toothTreatment->setTreatment($treatment);
                    $toothTreatment->setToothNumber($ttData['toothNumber'] ?? null);
                    $toothTreatment->setToothFace($ttData['toothFace'] ?? 0);
                    $toothTreatment->setStatus($ttData['status'] ?? ToothTreatment::STATUS_PENDING);
                    $odontogram->addToothTreatment($toothTreatment);
                }
            }

            if (isset($data['bridgeTreatments']) && is_array($data['bridgeTreatments'])) {
                foreach ($data['bridgeTreatments'] as $btData) {
                    $bridgeTreatment = new BridgeTreatment();

                    $treatment = $this->treatmentRepository->find($btData['treatment']['id'] ?? $btData['treatment'] ?? null);
                    if (!$treatment) {
                        $treatmentId = $btData['treatment']['id'] ?? $btData['treatment'] ?? null;
                        return new JsonResponse(['error' => "El tratamiento ID $treatmentId no existe"], 400);
                    }

                    $bridgeTreatment->setTreatment($treatment);
                    $bridgeTreatment->setStartTooth($btData['startTooth'] ?? null);
                    $bridgeTreatment->setEndTooth($btData['endTooth'] ?? null);
                    $bridgeTreatment->setStatus($btData['status'] ?? BridgeTreatment::STATUS_PENDING);
                    $odontogram->addBridgeTreatment($bridgeTreatment);
                }
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
            $data = json_decode($request->getContent(), true);
            if (!$data) {
                return new JsonResponse(['error' => 'JSON inválido'], Response::HTTP_BAD_REQUEST);
            }
            
            foreach ($odontogram->getToothPathologies()->toArray() as $existingTp) {
                $odontogram->removeToothPathology($existingTp);
                $this->entityManager->remove($existingTp);      
            }

            foreach ($odontogram->getToothTreatments()->toArray() as $existingTt) {
                $odontogram->removeToothTreatment($existingTt);
                $this->entityManager->remove($existingTt);      
            }

            foreach ($odontogram->getBridgeTreatments()->toArray() as $existingBt) {
                $odontogram->removeBridgeTreatment($existingBt);
                $this->entityManager->remove($existingBt);      
            }                 
                        
            if (isset($data['toothPathologies']) && is_array($data['toothPathologies'])) {
                foreach ($data['toothPathologies'] as $tpData) {
                    $toothPathology = new \App\Entity\ToothPathology();
                    
                    $toothNumber = $tpData['tooth']['toothNumber'] ?? null;
                    $tooth = $this->toothRepository->findOneBy(['toothNumber' => $toothNumber]);
                    
                    if (!$tooth) {
                        return new JsonResponse(['error' => "El diente número $toothNumber no existe en la base de datos"], 400);
                    }
                    
                    $pathology = $this->resolvePathologyFromPayload($tpData);
                    
                    if (!$pathology) {
                        $pathologyId = $tpData['pathology']['id'] ?? ($tpData['pathologyId'] ?? null);
                        return new JsonResponse(['error' => "La patología ID $pathologyId no existe"], 400);
                    }

                    $toothPathology->setTooth($tooth);
                    $toothPathology->setPathology($pathology);
                    $toothPathology->setToothFace($tpData['toothFace'] ?? 0);                    
                    $odontogram->addToothPathology($toothPathology);
                }
            }

            if (isset($data['toothTreatments']) && is_array($data['toothTreatments'])) {
                foreach ($data['toothTreatments'] as $ttData) {
                    $toothTreatment = new ToothTreatment();

                    $treatment = $this->treatmentRepository->find($ttData['treatment']['id'] ?? $ttData['treatment'] ?? null);
                    if (!$treatment) {
                        $treatmentId = $ttData['treatment']['id'] ?? $ttData['treatment'] ?? null;
                        return new JsonResponse(['error' => "El tratamiento ID $treatmentId no existe"], 400);
                    }

                    $toothTreatment->setTreatment($treatment);
                    $toothTreatment->setToothNumber($ttData['toothNumber'] ?? null);
                    $toothTreatment->setToothFace($ttData['toothFace'] ?? 0);
                    $toothTreatment->setStatus($ttData['status'] ?? ToothTreatment::STATUS_PENDING);
                    $odontogram->addToothTreatment($toothTreatment);
                }
            }

            if (isset($data['bridgeTreatments']) && is_array($data['bridgeTreatments'])) {
                foreach ($data['bridgeTreatments'] as $btData) {
                    $bridgeTreatment = new BridgeTreatment();

                    $treatment = $this->treatmentRepository->find($btData['treatment']['id'] ?? $btData['treatment'] ?? null);
                    if (!$treatment) {
                        $treatmentId = $btData['treatment']['id'] ?? $btData['treatment'] ?? null;
                        return new JsonResponse(['error' => "El tratamiento ID $treatmentId no existe"], 400);
                    }

                    $bridgeTreatment->setTreatment($treatment);
                    $bridgeTreatment->setStartTooth($btData['startTooth'] ?? null);
                    $bridgeTreatment->setEndTooth($btData['endTooth'] ?? null);
                    $bridgeTreatment->setStatus($btData['status'] ?? BridgeTreatment::STATUS_PENDING);
                    $odontogram->addBridgeTreatment($bridgeTreatment);
                }
            }
            
            $this->entityManager->flush();

            $json = $this->serializer->serialize($odontogram, 'json', ['groups' => 'odontogram:read']);
            return JsonResponse::fromJsonString($json);

        } catch (\Exception $e) {            
            return new JsonResponse(['error' => 'Excepción: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }


    #[Route('/{id}', methods: ['PATCH'])]
    public function patch(Odontogram $odontogram, Request $request): JsonResponse
    {
        // Only ROLE_DENTIST and ROLE_ADMIN can patch odontograms
        if (!$this->isGranted('ROLE_DENTIST') && !$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['error' => 'Only dentists and admins can update odontograms'], Response::HTTP_FORBIDDEN);
        }

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
        // Only ROLE_DENTIST and ROLE_ADMIN can delete odontograms
        if (!$this->isGranted('ROLE_DENTIST') && !$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['error' => 'Only dentists and admins can delete odontograms'], Response::HTTP_FORBIDDEN);
        }

        $this->entityManager->remove($odontogram);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    private function isChildPatient(\App\Entity\Patient $patient): bool
    {
        $birthDate = $patient->getBirthDate();
        if ($birthDate === null) {
            return false;
        }

        $today = new \DateTimeImmutable('today');

        return $birthDate->diff($today)->y < 12;
    }

    private function resolvePathologyFromPayload(array $tpData): ?Pathology
    {
        $pathologyData = $tpData['pathology'] ?? null;
        $pathologyId = $pathologyData['id'] ?? ($tpData['pathologyId'] ?? null);

        if ($pathologyId !== null && is_numeric((string) $pathologyId)) {
            $pathology = $this->pathologyRepository->find((int) $pathologyId);
            if ($pathology instanceof Pathology) {
                return $pathology;
            }
        }

        $candidateNames = [];

        foreach ([
            $pathologyData['description'] ?? null,
            $pathologyData['name'] ?? null,
            $pathologyData['label'] ?? null,
            $tpData['pathologyDescription'] ?? null,
            $tpData['pathologyName'] ?? null,
        ] as $name) {
            if (is_string($name) && trim($name) !== '') {
                $candidateNames[] = trim($name);
            }
        }

        $legacyNameById = [
            1 => 'Caries',
            2 => 'Caries',
            3 => 'Obturacion',
            4 => 'Obturacion',
            5 => 'Corona',
            6 => 'Corona',
            7 => 'Ausente',
            8 => 'Endodoncia',
            9 => 'Endodoncia',
            10 => 'Exodoncia',
            11 => 'Exodonciaort',
            12 => 'Exodonciaort',
            13 => 'cariesX',
            14 => 'fisuras',
            15 => 'puente',
        ];

        if ($pathologyId !== null && is_numeric((string) $pathologyId)) {
            $legacyId = (int) $pathologyId;
            if (isset($legacyNameById[$legacyId])) {
                $candidateNames[] = $legacyNameById[$legacyId];
            }
        }

        if ($candidateNames === []) {
            return null;
        }

        $allPathologies = $this->pathologyRepository->findAll();
        $normalizedCandidates = array_map([$this, 'normalizePathologyLabel'], $candidateNames);

        foreach ($allPathologies as $pathology) {
            $normalizedDescription = $this->normalizePathologyLabel((string) $pathology->getDescription());
            foreach ($normalizedCandidates as $candidate) {
                if ($candidate !== '' && $normalizedDescription === $candidate) {
                    return $pathology;
                }
            }
        }

        foreach ($allPathologies as $pathology) {
            $normalizedDescription = $this->normalizePathologyLabel((string) $pathology->getDescription());
            foreach ($normalizedCandidates as $candidate) {
                if (
                    $candidate !== '' &&
                    (str_contains($normalizedDescription, $candidate) || str_contains($candidate, $normalizedDescription))
                ) {
                    return $pathology;
                }
            }
        }

        return null;
    }

    private function normalizePathologyLabel(string $value): string
    {
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
        if ($ascii === false) {
            $ascii = $value;
        }

        $normalized = strtolower($ascii);

        return preg_replace('/[^a-z0-9]+/', '', $normalized) ?? '';
    }
}
