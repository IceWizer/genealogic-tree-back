<?php

namespace App\Controller;

use App\Entity\Person;
use App\Repository\PersonRepository;
use App\Request\Person\Create;
use App\Request\Person\Update;
use App\Response\Person\Option;
use App\Response\Person\Read;
use App\Response\Person\Show;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Nelmio\ApiDocBundle\Annotation\Security;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

#[Route("/api/person")]
#[IsGranted('ROLE_USER')]
final class PersonController extends BaseController
{
    private PersonRepository $repository;

    public function __construct(PersonRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(#[CurrentUser] User $user, Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 10);

        $q = $request->query->get('q');
        if ($q) {
            $paginatedPeople = $this->repository->paginateFindAllByOwnerAndQuery($user, $q, $page, $limit);
        } else {
            $paginatedPeople = $this->repository->paginateFindAllByOwner($user, $page, $limit);
        }

        $formattedPeople = array_map(function ($person) {
            return new Read($person);
        }, iterator_to_array($paginatedPeople->getIterator()));

        $total = $paginatedPeople->count();

        return $this->json([
            'data' => $formattedPeople,
            'total' => $total
        ], Response::HTTP_OK, []);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => self::UUID_REGEX], methods: ['GET'])]
    public function show(string $id): Response
    {
        $person = $this->repository->findOneBy(['id' => $id]);

        if ($person === null) {
            return $this->json(['error' => 'Person not found'], Response::HTTP_NOT_FOUND);
        }

        if ($person->getOwner()->getId() !== $this->getUser()->getId()) {
            return $this->json(['error' => 'Person not found'], Response::HTTP_FORBIDDEN);
        }

        return $this->json(new Show($person), Response::HTTP_OK, []);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(#[MapRequestPayload(acceptFormat: "json")] Create $createRequest, EntityManagerInterface $em): Response
    {
        $person = new Person();

        $person->setName($createRequest->name);
        $person->setFirstNames($createRequest->firstNames);
        if (isset($createRequest->birthName) && !empty($createRequest->birthName)) {
            $person->setBirthName($createRequest->birthName);
        }
        if (isset($createRequest->birthDate) && !empty($createRequest->birthDate)) {
            $person->setBirthDate(new \DateTime($createRequest->birthDate));
        } else {
            $person->setBirthDate(null);
        }
        if (isset($createRequest->birthCertificate) && !empty($createRequest->birthCertificate)) {
            $person->setBirthCertificate(intval($createRequest->birthCertificate));
        }
        if (isset($createRequest->deathDate) && !empty($createRequest->deathDate)) {
            $person->setDeathDate(new \DateTime($createRequest->deathDate));
        } else {
            $person->setDeathDate(null);
        }
        if (isset($createRequest->deathCertificate) && !empty($createRequest->deathCertificate)) {
            $person->setDeathCertificate(intval($createRequest->deathCertificate));
        }
        $person->setOwner($this->getUser());

        $em->persist($person);
        $em->flush();

        return $this->json(new Show($person), Response::HTTP_CREATED, []);
    }

    #[Route('/{id}', name: 'update', requirements: ['id' => self::UUID_REGEX], methods: ['PUT'])]
    public function update(#[MapRequestPayload(acceptFormat: "json")] Update $request, string $id, EntityManagerInterface $em): Response
    {
        $person = $this->repository->findOneBy(['id' => Uuid::fromString($id)->toBinary(), 'owner' => $this->getUser()->getId()->toBinary()]);

        if ($person === null) {
            return $this->json(['error' => 'Person not found'], Response::HTTP_NOT_FOUND);
        }

        if ($person->getOwner()->getId() !== $this->getUser()->getId()) {
            return $this->json(['error' => 'Person not found'], Response::HTTP_FORBIDDEN);
        }

        $person->setName($request->name);
        $person->setFirstNames($request->firstNames);
        $person->setBirthName($request->birthName);
        if (isset($request->birthDate) && !empty($request->birthDate)) {
            $person->setBirthDate(new \DateTime($request->birthDate));
        } else {
            $person->setBirthDate(null);
        }
        if (isset($request->birthCertificate) && !empty($request->birthCertificate)) {
            $person->setBirthCertificate(intval($request->birthCertificate));
        } else {
            $person->setBirthCertificate(null);
        }
        if (isset($request->deathDate) && !empty($request->deathDate)) {
            $person->setDeathDate(new \DateTime($request->deathDate));
        } else {
            $person->setDeathDate(null);
        }
        if (isset($request->deathCertificate) && !empty($request->deathCertificate)) {
            $person->setDeathCertificate(intval($request->deathCertificate));
        } else {
            $person->setDeathCertificate(null);
        }

        $em->flush();

        return $this->json(new Show($person), Response::HTTP_OK, []);
    }

    #[Route('/{id}', name: 'delete', requirements: ['id' => self::UUID_REGEX], methods: ['DELETE'])]
    public function delete(string $id, EntityManagerInterface $em): Response
    {
        $person = $this->repository->findOneBy(['id' => Uuid::fromString($id)->toBinary(), 'owner' => $this->getUser()->getId()->toBinary()]);

        if ($person === null) {
            return $this->json(['error' => 'Person not found'], Response::HTTP_NOT_FOUND);
        }

        if ($person->getOwner()->getId() !== $this->getUser()->getId()) {
            return $this->json(['error' => 'Person not found'], Response::HTTP_FORBIDDEN);
        }

        $em->remove($person);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}/possible-children', name: 'possible_children', requirements: ['id' => self::UUID_REGEX], methods: ['GET'])]
    public function possibleChildren(string $id): Response
    {
        $parent = $this->repository->findOneBy(['id' => Uuid::fromString($id)->toBinary()]);

        if ($parent === null) {
            return $this->json(['error' => 'Parent not found'], Response::HTTP_NOT_FOUND);
        }

        $possibleChildren = array_map(function (Person $person): Option {
            return new Option($person);
        }, $this->repository->findPossibleChildren($parent));

        return $this->json($possibleChildren, Response::HTTP_OK, []);
    }

    #[Route('/{id}/add-child/{childId}', name: 'add_child', requirements: ['id' => self::UUID_REGEX, 'childId' => self::UUID_REGEX], methods: ['POST'])]
    public function addChild(string $id, string $childId, EntityManagerInterface $em): Response
    {
        $parent = $this->repository->findOneBy(['id' => Uuid::fromString($id)->toBinary()]);
        $child = $this->repository->findOneBy(['id' => Uuid::fromString($childId)->toBinary()]);

        if (
            $parent === null
            || $child === null
            || $parent->getOwner()->getId() !== $this->getUser()->getId()
            || $child->getOwner()->getId() !== $this->getUser()->getId()
        ) {
            return $this->json(['error' => 'Person not found'], Response::HTTP_NOT_FOUND);
        }

        if ($parent->getChildren()->contains($child)) {
            return $this->json(['error' => 'Person already has this child'], Response::HTTP_BAD_REQUEST);
        }

        if ($child->getParent1() !== null && $child->getParent2() !== null) {
            return $this->json(['error' => 'Person already has two parents'], Response::HTTP_BAD_REQUEST);
        }

        if ($child->getParent1() === null) {
            $child->setParent1($parent);
        } else {
            $child->setParent2($parent);
        }

        $em->flush();

        return $this->json("Child added", Response::HTTP_OK, [], ['groups' => 'person:show']);
    }

    #[Route('/{id}/remove-child/{childId}', name: 'remove_child', requirements: ['id' => self::UUID_REGEX, 'childId' => self::UUID_REGEX], methods: ['POST'])]
    public function removeChild(string $id, string $childId, EntityManagerInterface $em): Response
    {
        $parent = $this->repository->findOneBy(['id' => Uuid::fromString($id)->toBinary()]);
        $child = $this->repository->findOneBy(['id' => Uuid::fromString($childId)->toBinary()]);

        if ($parent === null || $child === null) {
            return $this->json(['error' => 'Person not found'], Response::HTTP_NOT_FOUND);
        }

        if ($parent->getOwner()->getId() !== $this->getUser()->getId() || $child->getOwner()->getId() !== $this->getUser()->getId()) {
            return $this->json(['error' => 'Person not found'], Response::HTTP_FORBIDDEN);
        }

        if (!$parent->getChildren()->contains($child)) {
            return $this->json(['error' => 'Person does not have this child'], Response::HTTP_BAD_REQUEST);
        }

        if ($child->getParent1() !== $parent && $child->getParent2() !== $parent) {
            return $this->json(['error' => 'Person is not a parent of this child'], Response::HTTP_BAD_REQUEST);
        }

        if ($child->getParent1() === $parent) {
            $child->setParent1(null);
        } else {
            $child->setParent2(null);
        }

        $em->flush();

        return $this->json("Child removed", Response::HTTP_OK, [], ['groups' => 'person:show']);
    }
}
