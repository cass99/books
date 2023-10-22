<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;

class BookController extends AbstractController
{
    /**
     * List of all books.
     *
     * @Route("/api/doc/book", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns all books"
     * )
     * @OA\Tag(name="book")
     * @Security(name="Bearer")
     */
    public function index(BookRepository $books): JsonResponse
    {
        $allBooks = $books->findAllWithAuthor();
        
        $booksData = [];
        foreach ($allBooks as $book) {
            $booksData[] = $book->getBookData();
        }
        
        return $this->json($booksData);
    }

    /**
     * Create book
     *
     * @Route("/api/doc/book", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Adds book"
     * )
     * @OA\Parameter(
     *     name="name",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="publishingHouse",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="pages",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="authorId",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * @OA\Tag(name="book")
     * @Security(name="Bearer")
     */
    public function add(Request $request, BookRepository $books, AuthorRepository $authors): JsonResponse
    {
        $name = $request->query->get('name');
        $publishingHouse = $request->query->get('publishingHouse');
        $pages = $request->query->get('pages');
        $authorId = $request->query->get('authorId');

        if ($author = $authors->find($authorId)) {
            $book = new Book(); 
            $book->setName($name);
            $book->setPublishingHouse($publishingHouse);
            $book->setPages($pages);
            $book->setAuthor($author);
            $books->save($book, true);
            
            return $this->json([]);
        }

        return $this->json(['error' => 'Author not found']);
    }

    /**
     * Modify book
     *
     * @Route("/api/doc/book", methods={"PUT"})
     * @OA\Response(
     *     response=200,
     *     description="Modify book"
     * )
     * @OA\Parameter(
     *     name="bookId",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="name",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="publishingHouse",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="pages",
     *     in="query",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="authorId",
     *     in="query",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Tag(name="book")
     * @Security(name="Bearer")
     */
    public function update(Request $request, BookRepository $books, AuthorRepository $authors): JsonResponse 
    {
        $bookId = $request->query->get('bookId');
        $name = $request->query->get('name');
        $publishingHouse = $request->query->get('publishingHouse');
        $pages = $request->query->get('pages');
        $authorId = $request->query->get('authorId');

        if ($book = $books->find($bookId)) {
            if (!empty($name)) {
                $book->setName($name);
            }

            if (!empty($publishingHouse)) {
                $book->setPublishingHouse($publishingHouse);
            }

            if (!empty($pages)) {
                $book->setPages($pages);
            }

            if (!empty($authorId)) {
                $author = $authors->find($authorId);

                if (empty($author)) {
                    return $this->json(['error' => 'Author not found']);
                }

                $book->setAuthor($author);
            }

            $books->save($book, true);

            return $this->json([]);
        }

        return $this->json(['error' => 'Book not found']);
    }

     /**
     * Remove book
     *
     * @Route("/api/doc/book", methods={"DELETE"})
     * @OA\Response(
     *     response=200,
     *     description="Removes book"
     * )
     * @OA\Parameter(
     *     name="bookId",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * @OA\Tag(name="book")
     * @Security(name="Bearer")
     */
    public function delete(Request $request, BookRepository $books)
    {
        $bookId = $request->query->get('bookId');
        
        if ($book = $books->find($bookId)) {
            $books->remove($book, true);

            return $this->json([]);
        }

        return $this->json(['error' => 'Book not found']);
    }
}
