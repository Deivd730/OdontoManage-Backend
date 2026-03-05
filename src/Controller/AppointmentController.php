<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
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
            $appointment = $this->serializer->deserialize(
                $request->getContent(),
                Appointment::class,
                'json',
                ['groups' => 'appointment:write']
            );

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

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Appointment $appointment, Request $request): JsonResponse
    {
        try {
            $this->serializer->deserialize(
                $request->getContent(),
                Appointment::class,
                'json',
                ['object_to_populate' => $appointment, 'groups' => 'appointment:write']
            );

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

    #[Route('/{id}', methods: ['PATCH'])]
    public function patch(Appointment $appointment, Request $request): JsonResponse
    {
        try {
            $this->serializer->deserialize(
                $request->getContent(),
                Appointment::class,
                'json',
                ['object_to_populate' => $appointment, 'groups' => 'appointment:write']
            );

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
