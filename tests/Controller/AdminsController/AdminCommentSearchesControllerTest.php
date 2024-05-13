<?php

/**
 * during executing of these tests, retire ROLE_ADMIN in the controller for that these asserting be true
 */

    namespace App\Tests\Controller\AdminsController;

    use App\Entity\Tables\Users\Users;
    use Symfony\Bundle\FrameworkBundle\KernelBrowser;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class AdminCommentSearchesControllerTest extends WebTestCase
    {
        private KernelBrowser|null $client = null;
        private $urlGenerator = null;


        public function setUp(): void
        {
            $this->client = static::createClient();

            $this->urlGenerator = $this->client->getContainer()->get('router.default');
        }


        public function testAdminCommentSearchesPageIsUp()
        {
            $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('admin_comment_searches'));

            $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        }


        public function testAdminCommentSearchesForm()
        {
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('admin_comment_searches'));

            $form = $crawler->selectButton('Rechercher')->form();

            $form['search_bar_types[keyword]'] = 'php';

            $this->client->submit($form);

            $this->assertSame(1, $crawler->filter('html:contains("Rechercher un commentaire")')->count());
        }


        public function tearDown(): void
        {
            parent::tearDown();
        }
    }