<?php

    namespace App\Tests\Controller\AdminsController;

    use Symfony\Bundle\FrameworkBundle\KernelBrowser;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class AdminPostDetailControllerTest extends WebTestCase
    {
        private KernelBrowser|null $client = null;
        private $urlGenerator = null;


        public function setUp(): void
        {
            $this->client = static::createClient();

            $this->urlGenerator = $this->client->getContainer()->get('router.default');
        }


        /**
         * in this test below, value of $postId must be in database for validate the test
         */
        public function testPostDetailPageIsUp()
        {
            $postId = 23;
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('admin_post_details', ['postId' => $postId]));

            $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
            $this->assertSame(1, $crawler->filter('html:contains("Detail de l\'article")')->count());
        }


        public function testPostDetailPostIdIsNotFound()
        {
            $notFoundPostId = 9999;

            $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('admin_post_details', ['postId' => $notFoundPostId]));

            $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        }


        public function tearDown(): void
        {
            parent::tearDown();
        }
    }