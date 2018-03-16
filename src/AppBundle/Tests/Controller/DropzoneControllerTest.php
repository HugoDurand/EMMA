<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DropzoneControllerTest extends WebTestCase
{
    public function testAjaxsnippetimagesend()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ajax_snippet_image_send');
    }

}
