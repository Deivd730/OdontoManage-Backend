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
                        
            if (isset($data['patient'])) {
                $pData = $data['patient'];
                $patientId = is_array($pData) ? $pData['id'] : (is_numeric($pData) ? $pData : basename($pData));
                $patient = $this->patientRepository->find($patientId);
                if (!$patient) return new JsonResponse(['error' => 'Patient not found'], 404);
                $odontogram->setPatient($patient);
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

                    $pathologyId = $tpData['pathology']['id'] ?? null;
                    $pathology = $this->pathologyRepository->find($pathologyId);
                    if (!$pathology) {
                        return new JsonResponse(['error' => "La patología ID $pathologyId no existe"], 400);
                    }

                    $toothPathology->setTooth($tooth);
                    $toothPathology->setPathology($pathology);
                    $toothPathology->setToothFace($tpData['toothFace'] ?? 0);
                    $toothPathology->setStatus($tpData['status'] ?? 'Activo');
                    $odontogram->addToothPathology($toothPathology);
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
                        
            if (isset($data['toothPathologies']) && is_array($data['toothPathologies'])) {
                foreach ($data['toothPathologies'] as $tpData) {
                    $toothPathology = new \App\Entity\ToothPathology();
                    
                    $toothNumber = $tpData['tooth']['toothNumber'] ?? null;
                    $tooth = $this->toothRepository->findOneBy(['toothNumber' => $toothNumber]);
                    
                    if (!$tooth) {
                        return new JsonResponse(['error' => "El diente número $toothNumber no existe en la base de datos"], 400);
                    }
                    
                    $pathologyId = $tpData['pathology']['id'] ?? null;
                    $pathology = $this->pathologyRepository->find($pathologyId);
                    
                    if (!$pathology) {
                        return new JsonResponse(['error' => "La patología ID $pathologyId no existe"], 400);
                    }

                    $toothPathology->setTooth($tooth);
                    $toothPathology->setPathology($pathology);
                    $toothPathology->setToothFace($tpData['toothFace'] ?? 0);
                    $toothPathology->setStatus($tpData['status'] ?? 'Activo');
                    
                    $odontogram->addToothPathology($toothPathology);
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
