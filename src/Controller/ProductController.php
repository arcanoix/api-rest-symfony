<?php

namespace App\Controller;

use App\Entity\Product;
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
    public function create(Request $request): JsonResponse
    {
        $entityManager = $this->doctrine->getManager();

        $product = new Product();
        $parameter = json_decode($request->getContent(), true);
        $product->setName($parameter['name']);
        $product->setDescription($parameter['description']);
        $product->setPrice($parameter['price']);
        $product->setCategory($parameter['category']);

        $entityManager->persist($product);
        $entityManager->flush();

        $data = [
            'id'            => $product->getId(),
            'name'          => $product->getName(),
            'description'   => $product->getDescription(),
            'price'         => $product->getPrice(),
            'category'      => $product->getCategory()
        ];

        return $this->json($data);
    }
   
}
