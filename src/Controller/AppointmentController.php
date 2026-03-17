<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use App\Repository\BoxRepository;
use App\Repository\DentistRepository;
use App\Repository\PatientRepository;
use App\Repository\TreatmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/appointments')]
class AppointmentController extends AbstractController
{
    public function __construct(
        private AppointmentRepository $appointmentRepository,
        private TreatmentRepository $treatmentRepository,
        private PatientRepository $patientRepository,
        private DentistRepository $dentistRepository,
        private BoxRepository $boxRepository,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $treatmentId = $request->query->get('treatment');
        
        if ($treatmentId) {
            $treatment = $this->treatmentRepository->find($treatmentId);
            if (!$treatment) {
                return new JsonResponse(['error' => 'Treatment not found'], Response::HTTP_NOT_FOUND);
            }
            $appointments = $this->appointmentRepository->findBy(['treatment' => $treatment], ['visitDate' => 'ASC']);
        } else {
            $appointments = $this->appointmentRepository->findBy([], ['visitDate' => 'ASC']);
        }

        $data = $this->serializer->serialize($appointments, 'json', ['groups' => 'appointment:read']);

        return JsonResponse::fromJsonString($data);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Appointment $appointment): JsonResponse
    {
        $data = $this->serializer->serialize($appointment, 'json', ['groups' => 'appointment:read']);

        return JsonResponse::fromJsonString($data);
    }

    #[Route('/dentist/{dentistId}', methods: ['GET'])]
    public function getByDentist(int $dentistId): JsonResponse
    {
        $appointments = $this->appointmentRepository->createQueryBuilder('a')
            ->where('a.dentist = :dentistId')
            ->setParameter('dentistId', $dentistId)
            ->orderBy('a.visitDate', 'ASC')
            ->getQuery()
            ->getResult();

        $data = $this->serializer->serialize($appointments, 'json', ['groups' => 'appointment:read']);

        return JsonResponse::fromJsonString($data);
    }

    #[Route('/patient/{patientId}', methods: ['GET'])]
    public function getByPatient(int $patientId): JsonResponse
    {
        $appointments = $this->appointmentRepository->createQueryBuilder('a')
            ->where('a.patient = :patientId')
            ->setParameter('patientId', $patientId)
            ->orderBy('a.visitDate', 'DESC')
            ->getQuery()
            ->getResult();

        $data = $this->serializer->serialize($appointments, 'json', ['groups' => 'appointment:read']);

        return JsonResponse::fromJsonString($data);
    }

    #[Route('/box/{boxId}', methods: ['GET'])]
    public function getByBox(int $boxId): JsonResponse
    {
        $appointments = $this->appointmentRepository->createQueryBuilder('a')
            ->where('a.box = :boxId')
            ->setParameter('boxId', $boxId)
            ->orderBy('a.visitDate', 'ASC')
            ->getQuery()
            ->getResult();

        $data = $this->serializer->serialize($appointments, 'json', ['groups' => 'appointment:read']);

        return JsonResponse::fromJsonString($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            // Manually fetch entities to ensure they're fully loaded
            $patient = $this->patientRepository->find($data['patient'] ?? null);
            $dentist = $this->dentistRepository->find($data['dentist'] ?? null);
            $treatment = $this->treatmentRepository->find($data['treatment'] ?? null);

            if (!$patient) {
                return new JsonResponse(['error' => 'Patient not found'], Response::HTTP_BAD_REQUEST);
            }
            if (!$dentist) {
                return new JsonResponse(['error' => 'Dentist not found'], Response::HTTP_BAD_REQUEST);
            }
            if (!$treatment) {
                return new JsonResponse(['error' => 'Treatment not found'], Response::HTTP_BAD_REQUEST);
            }

            if (!isset($data['visitDate'])) {
                return new JsonResponse(['error' => 'visitDate is required'], Response::HTTP_BAD_REQUEST);
            }

            $visitDate = new \DateTime($data['visitDate']);

            // Auto-assign an available box for this date/time
            $box = $this->findAvailableBox($visitDate, $treatment);
            if (!$box) {
                return new JsonResponse(['error' => 'No available boxes for the requested time slot'], Response::HTTP_CONFLICT);
            }

            $appointment = new Appointment();
            $appointment->setPatient($patient);
            $appointment->setDentist($dentist);
            $appointment->setBox($box);
            $appointment->setTreatment($treatment);
            $appointment->setVisitDate($visitDate);
            
            if (isset($data['consultationReason'])) {
                $appointment->setConsultationReason($data['consultationReason']);
            }

            $errors = $this->validator->validate($appointment);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->persist($appointment);
            $this->entityManager->flush();

            $data = $this->serializer->serialize($appointment, 'json', ['groups' => 'appointment:read']);

            return JsonResponse::fromJsonString($data, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Find an available box for the given date/time and treatment duration
     */
    private function findAvailableBox(\DateTime $visitDate, \App\Entity\Treatment $treatment): ?\App\Entity\Box
    {
        $durationMinutes = $treatment->getDurationMinutes();
        $bufferMinutes = 5;

        // Get all boxes and check which one is available
        $boxes = $this->boxRepository->findAll();

        foreach ($boxes as $box) {
            // Load appointments for this box on the same day
            $dayStart = \DateTime::createFromInterface($visitDate);
            $dayStart = $dayStart->setTime(0, 0, 0);
            $dayEnd = \DateTime::createFromInterface($visitDate);
            $dayEnd = $dayEnd->setTime(23, 59, 59);

            $appointments = $this->appointmentRepository->createQueryBuilder('a')
                ->innerJoin('a.treatment', 't')
                ->where('a.box = :box')
                ->andWhere('a.visitDate >= :dayStart')
                ->andWhere('a.visitDate <= :dayEnd')
                ->setParameter('box', $box)
                ->setParameter('dayStart', $dayStart)
                ->setParameter('dayEnd', $dayEnd)
                ->getQuery()
                ->getResult();

            // Check if this box has conflicts
            $endTime = (clone $visitDate)->modify('+' . $durationMinutes . ' minutes');
            $endWithBuffer = (clone $endTime)->modify('+' . $bufferMinutes . ' minutes');
            $hasConflict = false;

            foreach ($appointments as $other) {
                $otherTreatment = $other->getTreatment();
                if (!$otherTreatment) {
                    continue;
                }

                $otherStart = \DateTime::createFromInterface($other->getVisitDate());
                $otherEnd = (clone $otherStart)->modify('+' . $otherTreatment->getDurationMinutes() . ' minutes');
                $otherEndWithBuffer = (clone $otherEnd)->modify('+' . $bufferMinutes . ' minutes');

                if ($otherStart < $endWithBuffer && $otherEndWithBuffer > $visitDate) {
                    $hasConflict = true;
                    break;
                }
            }

            if (!$hasConflict) {
                return $box;
            }
        }

        return null;
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Appointment $appointment, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            // Manually fetch entities to ensure they're fully loaded
            if (isset($data['patient'])) {
                $patient = $this->patientRepository->find($data['patient']);
                if (!$patient) {
                    return new JsonResponse(['error' => 'Patient not found'], Response::HTTP_BAD_REQUEST);
                }
                $appointment->setPatient($patient);
            }

            if (isset($data['dentist'])) {
                $dentist = $this->dentistRepository->find($data['dentist']);
                if (!$dentist) {
                    return new JsonResponse(['error' => 'Dentist not found'], Response::HTTP_BAD_REQUEST);
                }
                $appointment->setDentist($dentist);
            }

            if (isset($data['treatment'])) {
                $treatment = $this->treatmentRepository->find($data['treatment']);
                if (!$treatment) {
                    return new JsonResponse(['error' => 'Treatment not found'], Response::HTTP_BAD_REQUEST);
                }
                $appointment->setTreatment($treatment);
            }
            
            if (isset($data['visitDate'])) {
                $appointment->setVisitDate(new \DateTime($data['visitDate']));
                
                // Re-assign box when date changes
                $treatment = $appointment->getTreatment();
                if ($treatment) {
                    $box = $this->findAvailableBox($appointment->getVisitDate(), $treatment);
                    if (!$box) {
                        return new JsonResponse(['error' => 'No available boxes for the requested time slot'], Response::HTTP_CONFLICT);
                    }
                    $appointment->setBox($box);
                }
            }
            
            if (isset($data['consultationReason'])) {
                $appointment->setConsultationReason($data['consultationReason']);
            }

            $errors = $this->validator->validate($appointment);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->flush();

            $data = $this->serializer->serialize($appointment, 'json', ['groups' => 'appointment:read']);

            return JsonResponse::fromJsonString($data);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Appointment $appointment): JsonResponse
    {
        $this->entityManager->remove($appointment);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
