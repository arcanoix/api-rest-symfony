<?php

namespace App\Controller;

use App\Dto\ProductDto;
use App\Entity\Product;
use App\ArgumentResolver\Body;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api', name: 'api_')]
class ProductController extends AbstractController
{

    public function __construct(private readonly ManagerRegistry $doctrine)
    {
    }

    #[Route('/products', name: 'product_list', methods: ['get'])]
    public function index(): JsonResponse
    {
        $products = $this->doctrine->getRepository(Product::class)->findAll();

        $data = [];

        foreach($products as $product)
        {
            $data[] = [
                'id'            => $product->getId(),
                'name'          => $product->getName(),
                'description'   => $product->getDescription(),
                'category'      => $product->getCategory(),
                'price'         => $product->getPrice()
            ];
        }
        
        return new JsonResponse($data, Response::HTTP_ACCEPTED, ["Content-Type" => "application/json"]);
    }

    #[Route('/products', name: 'product_create', methods: ['post'])]
    public function create(#[Body] ProductDto $data): JsonResponse
    {
        $entityManager = $this->doctrine->getManager();
        $category = $entityManager->getRepository(Category::class)->find($data->getCategory());
        $entity = Product::create($data->getName(), $data->getDescription(), $data->getPrice(), $category);
        $entityManager->persist($entity);
        $entityManager->flush();

        return new JsonResponse($data, Response::HTTP_ACCEPTED, ["Content-Type" => "application/json"]);
    }

    #[Route('/products/{id}', name: 'product_show', methods: ['get'])]
    public function show(int $id): JsonResponse
    {
        $product = $this->doctrine->getRepository(Product::class)->find($id);

        if(!$product)
        {
            return $this->json(['message' => 'Product not found'], 404);
        }

        $data = [
            'id'          => $product->getId(),
            'name'        => $product->getName(),
            'description' => $product->getDescription(),
            'price'       => $product->getPrice(),
            'category'    => $product->getCategories()
        ];

        return $this->json($data);
    }

    #[Route('/products/{id}', name: 'product_update', methods: ['put', 'patch'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $entityManager = $this->doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if(!$product)
        {
            return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND, ["Content-Type" => "application/json"]);
        }

        $parameter = json_decode($request->getContent(), true);
        
        $product->setName($parameter['name']);
        $product->setDescription($parameter['description']);
        $product->setPrice($parameter['price']);
        $product->setCategory($parameter['category']);

        $entityManager->flush();

        return new JsonResponse($product, Response::HTTP_CREATED, ["Content-Type" => "application/json"]);
    }

    #[Route('/products/{id}', name: 'product_delete', methods: ['delete'])]
    public function delete(int $id): JsonResponse
    {
        $entityManager = $this->doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if(!$product)
        {
            return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND, ["Content-Type" => "application/json"]);
        }

        $entityManager->remove($product);
        $entityManager->flush();


        return new JsonResponse(['message' => 'delete a product succesfully'], Response::HTTP_CREATED, ["Content-Type" => "application/json"]);
    }
   
}
