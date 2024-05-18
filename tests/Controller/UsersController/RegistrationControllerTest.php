<?php

    namespace App\Tests\Controller\UsersController;

    use App\Entity\Tables\Users\Users;
    use Symfony\Bundle\FrameworkBundle\KernelBrowser;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class RegistrationControllerTest extends WebTestCase
    {
        private KernelBrowser|null $client = null;
        private $urlGenerator = null;


        public function setUp(): void
        {
            $this->client = static::createClient();
            $this->urlGenerator = $this->client->getContainer()->get('router.default');
        }


        public function testRegistrationPageIsUp()
        {
            $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_registration'));

            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        }


        public function testRegistrationForm()
        {
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_registration'));

            $form = $crawler->selectButton('S\'enregistrer')->form();

            $form['registration[pseudonyme]'] = 'LucLuc';
            $form['registration[email]'] = 'luc@free.fr';
            $form['registration[password]'] = 'password';

            $this->client->submit($form);

            $this->assertTrue($this->client->getResponse()->isRedirect('/user/dashboard'));

            $crawlerForRedirectPage = $this->client->followRedirect();

            $this->assertSame(1, $crawlerForRedirectPage->filter('html:contains("Tableau de bord")')->count());

            //dump($this->client->getResponse());
        }


        public function tearDown(): void
        {
            parent::tearDown();
        }
    }