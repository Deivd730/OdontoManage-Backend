<?php

namespace App\Controller;

use App\Entity\Document;
use App\Repository\DocumentRepository;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/api/documents')]
class DocumentController extends AbstractController
{
    public function __construct(
        private DocumentRepository $documentRepository,
        private PatientRepository $patientRepository,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
    ) {
    }

    // GET /api/documents/patient/{patientId}
    #[Route('/patient/{patientId}', methods: ['GET'])]
    public function getByPatient(int $patientId): JsonResponse
    {
        $patient = $this->patientRepository->find($patientId);
        if (!$patient) {
            return new JsonResponse(['error' => 'Patient not found'], Response::HTTP_NOT_FOUND);
        }

        $documents = $this->documentRepository->findBy(['patient' => $patient]);
        $data = $this->serializer->serialize($documents, 'json', ['groups' => 'document:read']);

        return JsonResponse::fromJsonString($data);
    }

    // POST /api/documents  (multipart/form-data)
    #[Route('', methods: ['POST'])]
    public function upload(Request $request): JsonResponse
    {
        try {
            $patientId = $request->request->get('patient');
            $type = $request->request->get('type');
            $captureDate = $request->request->get('captureDate');
            /** @var UploadedFile|null $file */
            $file = $request->files->get('documentFile');

            if (!$file) {
                return new JsonResponse(['error' => 'No se ha subido ningún archivo'], Response::HTTP_BAD_REQUEST);
            }

            // Validar tamaño de archivo (máximo 10MB)
            $maxSize = 10 * 1024 * 1024; // 10MB en bytes
            if ($file->getSize() > $maxSize) {
                return new JsonResponse(
                    ['error' => 'El archivo es demasiado grande. Tamaño máximo: 10MB'],
                    Response::HTTP_BAD_REQUEST
                );
            }

            // Validar tipo MIME
            $allowedMimeTypes = [
                'application/pdf',
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/gif'
            ];
            if (!in_array($file->getMimeType(), $allowedMimeTypes, true)) {
                return new JsonResponse(
                    ['error' => 'Tipo de archivo no permitido. Solo se aceptan PDF e imágenes (JPEG, PNG, GIF)'],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $patient = $this->patientRepository->find($patientId);
            if (!$patient) {
                return new JsonResponse(['error' => 'Paciente no encontrado'], Response::HTTP_NOT_FOUND);
            }

            if (!$type || trim($type) === '') {
                return new JsonResponse(['error' => 'El tipo de documento es obligatorio'], Response::HTTP_BAD_REQUEST);
            }

            if (!$captureDate) {
                return new JsonResponse(['error' => 'La fecha de captura es obligatoria'], Response::HTTP_BAD_REQUEST);
            }

            $document = new Document();
            $document->setPatient($patient);
            $document->setType(trim($type));
            $document->setCaptureDate(new \DateTime($captureDate));
            $document->setDocumentFile($file);

            $this->entityManager->persist($document);
            $this->entityManager->flush();

            $data = $this->serializer->serialize($document, 'json', ['groups' => 'document:read']);

            return JsonResponse::fromJsonString($data, Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => 'Error al subir el documento: ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    // DELETE /api/documents/{id}
    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Document $document): JsonResponse
    {
        $this->entityManager->remove($document);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}