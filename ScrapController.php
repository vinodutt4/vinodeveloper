<?php

namespace App\Http\Controllers;

use IlluminateHttpRequest;
use Sunra\PhpSimple\HtmlDomParser;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Image;
use DB;

class ScrapController extends Controller
{
    public function index()
    {   
       $client = new Client(HttpClient::create(['timeout' => 60]));
       $crawler = $client->request('GET', 'https://www.viprealestate.com/idx/sabor/?p=10');
        global $properties;
         $properties = [];
          $i = 0;
        $crawler->filter('article')->each(function ($node) {
               
           $properties['title'] = $node->filter('h3')->text();
           $properties['propert_type'] = $node->filter('.property-type')->text();
           $properties['area'] = $node->filter('.mediaBodyStats')->text(); 
           $properties['office'] = $node->filter('.office')->text(); 
           $properties['image'] = $node->filter('.mediaImg > a > img')->attr('data-src'); 
            
           // $properties[]['title'] = $node->filterXpath('.mediaImg > a > img')->extract('src');
            //print_r($properties);
           $this->store_article($properties);
            
        });

        print_r($properties);
    }

    public function store_article($article){
        DB::table('articles')->insert(
            ['title' => $article['title'], 'propert_type' => $article['propert_type'] , 'area' => $article['area'], 'office' => $article['office'], 'image' => $article['image'] ]
        );
    }
}
