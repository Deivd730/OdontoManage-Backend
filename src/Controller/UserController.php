<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/users')]
class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    #[Route('/me', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function me(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['error' => 'Not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }

    #[Route('/register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);
        if (!is_array($payload)) {
            return new JsonResponse(['error' => 'Invalid JSON body.'], Response::HTTP_BAD_REQUEST);
        }

        $name = isset($payload['name']) ? trim((string) $payload['name']) : '';
        $email = isset($payload['email']) ? trim((string) $payload['email']) : '';
        $plainPassword = isset($payload['password']) ? (string) $payload['password'] : '';
        $roles = isset($payload['roles']) && is_array($payload['roles']) ? $payload['roles'] : [];

        if ($name == '' || $email == '' || $plainPassword == '') {
            return new JsonResponse(
                ['error' => 'Name, email, and password are required.'],
                Response::HTTP_BAD_REQUEST
            );
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['error' => 'Invalid email format.'], Response::HTTP_BAD_REQUEST);
        }

        if ($this->userRepository->findOneBy(['email' => $email]) !== null) {
            return new JsonResponse(['error' => 'Email already registered.'], Response::HTTP_CONFLICT);
        }

        // Validate and filter roles - only ROLE_AUXILIAR allowed for self-registration
        // ROLE_DENTIST and ROLE_ADMIN can only be created by ROLE_ADMIN through /api/users endpoint
        $validRoles = ['ROLE_AUXILIAR'];
        $roles = array_filter($roles, static fn(string $role): bool => in_array($role, $validRoles, true));

        if (empty($roles)) {
            $roles = ['ROLE_AUXILIAR']; // Default role
        }

        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(
            [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ],
            Response::HTTP_CREATED
        );
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        $data = array_map(
            static fn(User $user): array => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ],
            $users
        );

        return new JsonResponse($data);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user instanceof User) {
            return new JsonResponse(['error' => 'User not found.'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }

    #[Route('', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);
        if (!is_array($payload)) {
            return new JsonResponse(['error' => 'Invalid JSON body.'], Response::HTTP_BAD_REQUEST);
        }

        $name = isset($payload['name']) ? trim((string) $payload['name']) : '';
        $email = isset($payload['email']) ? trim((string) $payload['email']) : '';
        $plainPassword = isset($payload['password']) ? (string) $payload['password'] : '';
        $roles = isset($payload['roles']) && is_array($payload['roles']) ? $payload['roles'] : [];

        if ($name == '' || $email == '' || $plainPassword == '') {
            return new JsonResponse(
                ['error' => 'Name, email, and password are required.'],
                Response::HTTP_BAD_REQUEST
            );
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['error' => 'Invalid email format.'], Response::HTTP_BAD_REQUEST);
        }

        if ($this->userRepository->findOneBy(['email' => $email]) !== null) {
            return new JsonResponse(['error' => 'Email already registered.'], Response::HTTP_CONFLICT);
        }

        // Validate roles - only ROLE_ADMIN can create ROLE_DENTIST and ROLE_ADMIN users
        $validRoles = ['ROLE_ADMIN', 'ROLE_AUXILIAR', 'ROLE_DENTIST'];
        $roles = array_filter($roles, static fn(string $role): bool => in_array($role, $validRoles, true));

        if (empty($roles)) {
            $roles = ['ROLE_AUXILIAR']; // Default role
        }

        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(
            [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ],
            Response::HTTP_CREATED
        );
    }

    #[Route('/{id}', methods: ['PUT', 'PATCH'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function update(int $id, Request $request): JsonResponse
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $userToUpdate = $this->userRepository->find($id);

        if (!$userToUpdate || $currentUser->getId() !== $userToUpdate->getId()) {
            return new JsonResponse(['error' => 'No autorizado'], Response::HTTP_FORBIDDEN);
        }

        $payload = json_decode($request->getContent(), true);

        if (isset($payload['name'])) {
            $userToUpdate->setName($payload['name']);
        }

        if (isset($payload['email'])) {
            // Validar si el email ya existe en otro usuario
            $existing = $this->userRepository->findOneBy(['email' => $payload['email']]);
            if ($existing && $existing->getId() !== $userToUpdate->getId()) {
                return new JsonResponse(['error' => 'El email ya está en uso'], Response::HTTP_CONFLICT);
            }
            $userToUpdate->setEmail($payload['email']);
        }

        // Only ROLE_ADMIN can change roles
        if (isset($payload['roles']) && $this->isGranted('ROLE_ADMIN')) {
            $validRoles = ['ROLE_ADMIN', 'ROLE_AUXILIAR'];
            $roles = array_filter($payload['roles'], static fn(string $role): bool => in_array($role, $validRoles, true));
            
            if (!empty($roles)) {
                $userToUpdate->setRoles($roles);
            }
        }

        $this->entityManager->flush();

        return new JsonResponse([
            'id' => $userToUpdate->getId(),
            'name' => $userToUpdate->getName(),
            'email' => $userToUpdate->getEmail(),
            'roles' => $userToUpdate->getRoles(),
        ]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]

    public function delete(int $id): JsonResponse
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $userToDelete = $this->userRepository->find($id);

        if (!$userToDelete) {
            return new JsonResponse(['error' => 'Usuario no encontrado'], 404);
        }

        if ($currentUser->getId() !== $userToDelete->getId()) {
            return new JsonResponse(['error' => 'No tienes permiso para borrar esta cuenta'], 403);
        }

        $this->entityManager->remove($userToDelete);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Cuenta eliminada'], 200);

    }

    #[Route('/{id}/password', methods: ['POST'])] // Usamos POST porque es una "acción"
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function changePassword(int $id, Request $request, UserPasswordHasherInterface $hasher): JsonResponse
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $user = $this->userRepository->find($id);

        if (!$user || $currentUser->getId() !== $user->getId()) {
            return new JsonResponse(['error' => 'No autorizado'], 403);
        }

        $data = json_decode($request->getContent(), true);
        $currentPassword = $data['currentPassword'] ?? '';
        $newPassword = $data['newPassword'] ?? '';

        if (!$currentPassword || !$newPassword) {
            return new JsonResponse(['error' => 'Faltan datos obligatorios'], 400);
        }

        if (!$hasher->isPasswordValid($user, $currentPassword)) {
            return new JsonResponse(['error' => 'La contraseña actual no es correcta.'], 401);
        }

        $user->setPassword($hasher->hashPassword($user, $newPassword));
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Contraseña actualizada correctamente']);
    }
}
