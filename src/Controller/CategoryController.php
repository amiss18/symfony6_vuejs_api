<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/categories', name: 'category_')]
class CategoryController extends AbstractController {

    public function __construct(private CategoryRepository $categoryRepository) {
    }

    #[Route(path: '', name: 'index', methods: "GET")]
    public function index(SerializerInterface $serializer): Response {
        $categories = $this->categoryRepository->findAll();
        //$categories = $serializer->serialize($categories,'json', ['groups'=>['category:r']] );


        return $this->json($categories, Response::HTTP_OK, [], ['groups' => ['category:r']]);
    }

    #[Route(path: '/{id}', name: 'show', requirements: ['id' => '\d+'], methods: 'GET')]
    public function show(Category $category): JsonResponse {
        return $this->json($category, Response::HTTP_OK, [], ['groups' => ['category:r']]);
    }

    #[Route(name: 'add', methods: ["POST"])]
    public function save(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator): Response {
        $category = $serializer->deserialize($request->getContent(), Category::class, 'json', ['groups' => ['category:w']]);
        $violations = $validator->validate($category);
        if ($violations->count() > 0)
            return $this->json(["message" => " Invalid data"], Response::HTTP_UNPROCESSABLE_ENTITY);

        $manager->persist($category);
        $manager->flush();

        return $this->json([$category], Response::HTTP_CREATED, ['groups' => ['category:r']]);
    }

    #[Route(path: '/{id}', name: 'update', requirements: ['id' => '\d+'], methods: ["PUT", "PATCH"])]
    public function update(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator, Category $category): Response {

        /** @var  Category $newCategory */
        $newCategory = $serializer->deserialize($request->getContent(), Category::class, 'json', ['groups' => ['category:w']]);
        $violations = $validator->validate($newCategory);
        if ($violations->count() > 0)
            return $this->json(["message" => " Invalid data"], Response::HTTP_UNPROCESSABLE_ENTITY);
        $category->setName($newCategory->getName());

        $manager->flush();

        return $this->json(["message" => "resource updated successfully"], Response::HTTP_OK);
    }

    #[Route(path: '/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ["DELETE"])]
    public function delete(Category $category, EntityManagerInterface $manager): JsonResponse {
        $manager->remove($category);
        $manager->flush();
        return $this->json([], Response::HTTP_NO_CONTENT, []);
    }
}
