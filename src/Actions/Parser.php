<?php

namespace App\Actions;

use App\Entity\Post;
use App\Interfaces\ResourceInterface;
use App\Message\ParseNews;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Panther\Client;

class Parser
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus) {
        $this->bus = $bus;
    }
    public function parse(): array
    {
        $collection = [];
//        $client = Client::createChromeClient();
//        $crawler = $client->request('GET', 'https://highload.today/category/novosti/');
        $html = file_get_contents('https://highload.today/category/novosti/');
        $crawler = new Crawler($html);
        $crawler->filter('div.col.sidebar-center .lenta-item')->each(function (Crawler $c) use (&$collection) {
            $post = new Post();

            if($c->filter('h1')->count() > 0){
                return;
            }

            /// Find and filter the title
            $title = $c->filter('a h2')->text();
            $post->setTitle($title);

            /// some websites using datetime attribute in <time> tag to store the full
            /// date time, here we first checked if this attribute exists, otherwise we fetch the
            /// text inside the tag.
            $dateTime = $c->filter('span.meta-datetime')->attr('datetime');
            if (!$dateTime) {
                $dateTime = $c->filter('span.meta-datetime')->text();
            }
            $dateTime = $this->cleanupDate($dateTime);
            $post->setCreated($dateTime);

            $description = ($c->filter('p')->text());
            $post->setDescription($description);

            $image = $c->filter('a .lenta-image img')->extract(['_text', 'src']);
            $post->setImage($image[1][1]);

            $this->bus->dispatch(new ParseNews($post));
            //$collection[] = $post;
        });


       // return $collection;
    }

    /**
     * In order to make DateTime work, we need to clean up the input.
     *
     * @throws \Exception
     */
    private function cleanupDate(string $dateTime): \DateTime
    {
        $convertDateTime = substr($dateTime, 0, 1);

        return new \DateTime(-$convertDateTime . ' days');
    }
}