<?php

namespace App\Controller;

use App\ArgumentResolver\Body;
use App\Dto\CategoryDto;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class CategoryController extends AbstractController
{

        public function __construct(private readonly ManagerRegistry $doctrine)
        {
        }


    #[Route('/categories', name: 'category_list', methods: ['get'])]
    public function index(): Response
    {
        $categories = $this->doctrine->getRepository(Category::class)->findAll();

        $data = [];

        foreach($categories as $category)
        {
            $data[] = [
                'id'   => $category->getId(),
                'name' => $category->getName()
            ];
        }
        
        return new JsonResponse($data, Response::HTTP_ACCEPTED, ["Content-Type" => "application/json"]);
    }

    #[Route('/categories', name: 'category_create', methods: ['post'])]
    public function create(#[Body] CategoryDto $data): JsonResponse
    {
        
        $entityManager = $this->doctrine->getManager();
        $entity = Category::create($data->getName());
        $entityManager->persist($entity);
        $entityManager->flush();

        return new JsonResponse($data, Response::HTTP_ACCEPTED, ["Content-Type" => "application/json"]);
    }


    #[Route('/categories/{id}', name: 'category_show', methods: ['get'])]
    public function show(int $id): JsonResponse
    {
        $category = $this->doctrine->getRepository(Category::class)->find($id);

        if(!$category)
        {
            return $this->json(['message' => 'Category not found'], 404);
        }

        $data = [
            'id'   => $category->getId(),
            'name' => $category->getName()
        ];

        return $this->json($data);
    }

    #[Route('/categories/{id}', name: 'category_update', methods: ['put', 'patch'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $entityManager = $this->doctrine->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);

        if(!$category)
        {
            return $this->json(['message' => 'Category not found'], 404);
        }

        $parameter = json_decode($request->getContent(), true);
        $category->setName($parameter['name']);

        $entityManager->flush();

        $data = [
            'id'   => $category->getId(),
            'name' => $category->getName()
        ];

        return $this->json($data);
    }

    #[Route('/categories/{id}', name: 'category_delete', methods: ['delete'])]
    public function delete(int $id): JsonResponse
    {
        $entityManager = $this->doctrine->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);

        if(!$category)
        {
            return $this->json(['message' => 'Category not found'], 404);
        }

        $entityManager->remove($category);
        $entityManager->flush();


        return $this->json(['message' => 'delete a category succesfully']);
    }


}
