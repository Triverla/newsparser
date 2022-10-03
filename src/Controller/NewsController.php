<?php

namespace App\Controller;

use App\Actions\Parser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    private Parser $parser;


    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @Route("/fetch", name="fetch")
     */
    public function fetch()
    {
        $posts = $this->parser->parse();

        return $this->json($posts);
    }

}
