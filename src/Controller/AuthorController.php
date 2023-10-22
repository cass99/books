<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;

class AuthorController extends AbstractController
{
    /**
     * Create author
     *
     * @Route("/api/doc/author", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Adds author"
     * )
     * @OA\Parameter(
     *     name="fullName",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="country",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * 
     * @OA\Tag(name="author")
     * @Security(name="Bearer")
     */
    public function add(Request $request, AuthorRepository $authors): JsonResponse
    {
        $fullName = $request->query->get('fullName');
        $country = $request->query->get('country');

        $author = new Author(); 
        $author->setFullName($fullName);
        $author->setCountry($country);
        $authors->save($author, true);
            
        return $this->json([]);
    }

    /**
     * Remove author
     *
     * @Route("/api/doc/author", methods={"DELETE"})
     * @OA\Response(
     *     response=200,
     *     description="Removes author"
     * )
     * @OA\Parameter(
     *     name="authorId",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * 
     * @OA\Tag(name="author")
     * @Security(name="Bearer")
     */
    public function delete(Request $request, AuthorRepository $authors)
    {
        $authorId = $request->query->get('authorId');
        
        if ($author = $authors->find($authorId)) {
            $authors->remove($author, true);

            return $this->json([]);
        }

        return $this->json(['error' => 'Author not found']);
    }
}
