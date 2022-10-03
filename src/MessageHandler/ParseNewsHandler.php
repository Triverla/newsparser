<?php

namespace App\MessageHandler;

use App\Entity\Post;
use App\Message\ParseNews;
use App\Repository\PostRepository;

class ParseNewsHandler
{
    public function __invoke(PostRepository $postRepository, ParseNews $parseNews)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entryExists = $postRepository->findBy(['title' => $parseNews->getPost()->getTitle()]) != null;

        if($entryExists) {
            $post = $postRepository->findBy(['title' => $parseNews->getPost()->getTitle()]);
            $post->setUpdated($parseNews->getPost()->getCreated());
            $entityManager->flush();
        }

        $post = new Post();
        $post->setTitle($parseNews->getPost()->getTitle());
        $post->setDescription($parseNews->getPost()->getDescription());
        $post->setImage($parseNews->getPost()->getImage());
        $post->setCreated($parseNews->getPost()->getCreated());

        $entityManager->persist($post);
        $entityManager->flush();
    }
}