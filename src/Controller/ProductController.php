<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\ProductManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/products', name: 'product_')]
class ProductController extends AbstractController {

    #[Route(name: 'all', methods: ["GET"])]
    public function index(ProductRepository $productRepository): Response {
        $products = $productRepository->findAll();

        return $this->json($products, Response::HTTP_OK, [], ['groups' => ['product:r']]);
    }

    #[Route(path: '/{id}', name: 'show', requirements: ['id' => '\d+'], methods: 'GET')]
    public function show( Product $product ): JsonResponse {
        return $this->json( $product ,Response::HTTP_OK,[],['groups'=>['product:r']] );
    }


    #[Route(name: 'add', methods: ["POST"])]
    public function save(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator): Response {
        /** @var Product $product */
        $product = $serializer->deserialize($request->getContent(), Product::class, 'json', ['groups' => ['product:w']]);

       // dd($product);

        $violations = $validator->validate($product);
        if ($violations->count() > 0)
            return $this->json(["message" => " Invalid data"], Response::HTTP_UNPROCESSABLE_ENTITY);

        $manager->persist($product);

        $manager->flush();

        return $this->json(["message" => "resource created successfully"], Response::HTTP_CREATED, ['groups' => ['product:r']]);
    }



    #[Route(path: '/{id}', name: 'update', requirements: ['id' => '\d+'], methods: ["PUT", "PATCH"])]
    public function update(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator, Product $product, ProductManagerInterface $productManager): Response {

        /** @var  Product $newProduct */
        $newProduct = $serializer->deserialize($request->getContent(), Product::class, 'json', ['groups' => ['product:w']]);
        $violations = $validator->validate($newProduct);
        if ($violations->count() > 0)
            return $this->json(["message" => " Invalid data"], Response::HTTP_UNPROCESSABLE_ENTITY);


        $productManager->UpdateProduct( $product, $newProduct);


        $manager->flush();

        return $this->json(["message" => "resource updated successfully"], Response::HTTP_OK);
    }



    #[Route(path: '/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ["DELETE"])]
    public function delete(Product $product, EntityManagerInterface $manager): JsonResponse {

        $manager->remove($product);
        $manager->flush();
        return $this->json([], Response::HTTP_NO_CONTENT, []);
    }
}
