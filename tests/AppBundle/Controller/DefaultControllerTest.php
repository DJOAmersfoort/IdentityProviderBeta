<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Tests\Support\PersistentKernelTestCase;

class DefaultControllerTest extends PersistentKernelTestCase
{
    /**
     * Assume we're not yet logged in, and test if we get a redirect to login
     */
    public function testIndexAnon()
    {
        // Get kernel and HTTP client
        $kernel = static::bootKernel();
        $client = static::createClient();

        // Get router and login URL
        $router = $kernel->getContainer()->get('router');
        $loginUrl = $router->generate('login', [], UrlGeneratorInterface::ABSOLUTE_URL);

        // Crawl login page
        $crawler = $client->request('GET', '/');
        $response = $client->getResponse();

        // Perform checks
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($loginUrl, $response->getTargetUrl());
    }
}
