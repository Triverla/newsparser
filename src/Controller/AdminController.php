<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdminController extends AbstractController
{
    private $paginator;
    private $em;

    public function __construct(EntityManagerInterface $em,
                                PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->paginator = $paginator;
    }
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function index(): Response
    {

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/news", name="news_index", methods={"GET"})
     */
    public function getAllPosts(PostRepository $postRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $em = $this->em;
        $query = "SELECT a FROM App\Entity\Post a";
        $entities = $em->createQuery($query);
        $pagination = $this->paginator->paginate(
            $entities,
            //$this->get('request')->query->get('page',1),
            $request->query->get('page',1),
            10
        );
        return $this->render('admin/news.html.twig', [
            'posts' => $postRepository->findAll(),
            'pagination'=>$pagination
        ]);
    }

    /**
     * @Route("/admin/news/{id}/delete", name="news_delete", methods={"POST"})
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('news_index');
    }
}
