<?php

    namespace App\Tests\Controller\UsersController;

    use Symfony\Bundle\FrameworkBundle\KernelBrowser;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;

    class PostSearchesControllerTest extends WebTestCase
    {
        private KernelBrowser|null $client = null;
        private $urlGenerator = null;


        protected function setUp(): void
        {
            $this->client = static::createClient();

            $this->urlGenerator = $this->client->getContainer()->get('router.default');
        }


        public function testPostsSearchesIsUp()
        {
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_post_searches'));

            $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
            $this->assertEquals(1, $crawler->filter('html:contains("Rechercher un post")')->count());
        }


        public function testPostsSearchesForm()
        {
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_post_searches'));

            $form = $crawler->selectButton('Rechercher')->form();

            $form['search_bar_types[keyword]'] = 'php';

            $this->client->submit($form);

            $this->assertFalse($this->client->getResponse()->isRedirect());
            $this->assertEquals(1, $crawler->filter('html:contains("Rechercher un post")')->count());
        }


        protected function tearDown(): void
        {
            parent::tearDown();
        }
    }