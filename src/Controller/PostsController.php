<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Repository\PostsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

/**
 * @Route("/api/posts", name="posts_")
 */
class PostsController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Returns all posts, or post with a specific channel ",    
     * )
     * @SWG\Parameter(
     *     name="channel",
     *     in="query",
     *     type="string",
     *      required=false,
     *     description="The field used to posts channels"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",    
     * )
     */
    public function index(Request $request, PostsRepository $postsRepository): Response
    {
        try {
            $data = $request->query->all();

            // $posts = $this->getDoctrine()->getRepository(Posts::class)->findAll();
            $posts = $postsRepository->findByExampleFieldOrAll($data);

            $jsonObject = $this->makeSerialize($posts);

            return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
        } catch (\Throwable $th) {
            return $this->json([
                'message' => 'Server error',
                // 'data' => $th,
            ], 404);
        }

    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Returns a post",    
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="Post  not found",    
     * )
     * 
     */
    public function show($id): Response
    {
        try {
            $post = $this->getDoctrine()->getRepository(Posts::class)->find($id);

            return $this->json([
                'data' => $post,
            ], 200);
        } catch (\Throwable $th) {
            return $this->json([
                'message' => 'Post '. $id . ' not exist',
                // 'data' => $th
            ], 404);
        }

    }

    /**
     * @Route("/", name="create", methods={"POST"})
     * 
     * @SWG\Response(
     *     response=201,
     *     description="Post created with success",    
     * )
     * SWG\Response(
     *     response=409,
     *     description="Post already exists.",    
     * )
     * @SWG\Parameter(
     *     name="title",
     *     in="formData",
     *     type="string",
     *     required=true,
     *     description="The field used to posts title"
     * )
     * @SWG\Parameter(
     *     name="description",
     *     in="formData",
     *     type="string",
     *     required=true,
     *     description="The field used to posts description"
     * )
     * @SWG\Parameter(
     *     name="content",
     *     in="formData",
     *     type="string",
     *     required=true,
     *     description="The field used to posts content"
     * )
     * @SWG\Parameter(
     *     name="status",
     *     in="formData",
     *     type="boolean",
     *     required=true,
     *     description="The field used to posts status"
     * )
     * @SWG\Response(
     *     response=409,
     *     description="Post already exists",    
     * )
     */
    public function create(Request $request): Response
    {
        try {
            $data = $request->request->all();

            $post = new Posts();
            $post->setTitle($data['title']);
            $post->setDescription($data['description']);
            $post->setContent($data['content']);
            $post->setStatus($data['status']);
            $post->setCreatedAt(new \DateTime('now', new \DateTimeZone('Europe/Lisbon')));
            $post->setUpdatedAt(new \DateTime('now', new \DateTimeZone('Europe/Lisbon')));

            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($post);
            $doctrine->flush();

            return $this->json([
                'message' => 'Post created with success',
                'data' => $post,
            ], 201);
        } catch (\Throwable $th) {
            return $this->json([
                'message' => 'Post not created with success',
                // 'data' => $th,
            ], 409);
        }

    }

    /**
     * @Route("/{id}", name="update", methods={"PUT", "PATCH"})
     * 
     * @SWG\Response(
     *     response=201,
     *     description="Post created with success",    
     * )
     * SWG\Response(
     *     response=409,
     *     description="Post already exists.",    
     * )
     * @SWG\Parameter(
     *     name="title",
     *     in="formData",
     *     type="string",
     *     required=false,
     *     description="The field used to posts title"
     * )
     * @SWG\Parameter(
     *     name="description",
     *     in="formData",
     *     type="string",
     *     required=false,
     *     description="The field used to posts description"
     * )
     * @SWG\Parameter(
     *     name="content",
     *     in="formData",
     *     type="string",
     *     required=false,
     *     description="The field used to posts content"
     * )
     * @SWG\Parameter(
     *     name="status",
     *     in="formData",
     *     type="boolean",
     *     required=false,
     *     description="The field used to posts status"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Post not found",    
     * )
     */
    public function update($id, Request $request): Response
    {
        try {
            $data = $request->request->all();

            $doctrine = $this->getDoctrine();

            $post = $doctrine->getRepository(Posts::class)->find($id);

            if ($request->request->has('title')) {$post->setTitle($data['title']);}
            if ($request->request->has('description')) {$post->setDescription($data['description']);}
            if ($request->request->has('content')) {$post->setContent($data['content']);}
            if ($request->request->has('status')) {$post->setStatus($data['status']);}

            $post->setUpdatedAt(new \DateTime('now', new \DateTimeZone('Europe/Lisbon')));

            $manager = $doctrine->getManager();
            $manager->flush();

            return $this->json([
                'message' => 'Post '. $id . ' updated with success',
                'data' => $post,
            ], 200);
        } catch (\Throwable $th) {
            return $this->json([
                'message' => 'Post '. $id . ' not created with success',
                // 'data' => $th,
            ], 404);
        }
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * 
     * * @SWG\Response(
     *     response=200,
     *     description="Deleted with success",    
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Post not found",    
     * )
     */
    public function delete($id): Response
    {
        try {
            $doctrine = $this->getDoctrine();

            $post = $doctrine->getRepository(Posts::class)->find($id);

            $manager = $doctrine->getManager();
            $manager->remove($post);
            $manager->flush();

            return $this->json([
                'message' => 'Post '. $id . ' removed with success',
            ], 200);
        } catch (\Throwable $th) {
            return $this->json([
                'message' => 'Post '. $id . 'not removed with success',
                // 'data' => $th,
            ], 404);
        }

    }

    private function makeSerialize($toSerialize)
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        // Serialize object in Json
        return $serializer->serialize($toSerialize, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        ]);
    }
}
