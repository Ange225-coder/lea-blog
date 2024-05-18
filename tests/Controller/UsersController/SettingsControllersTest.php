<?php

    namespace App\Tests\Controller\UsersController;

    use App\Entity\Tables\Users\Users;
    use Symfony\Bundle\FrameworkBundle\KernelBrowser;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class SettingsControllersTest extends WebTestCase
    {
        private KernelBrowser|null $client;
        private $urlGenerator = null;


        protected function setUp(): void
        {
            $this->client = static::createClient();

            $this->urlGenerator = $this->client->getContainer()->get('router.default');
        }


        public function testSettingPageIsUp()
        {
            $id = 35;
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_settings', ['id' => $id]));

            //$this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

            //echo $crawler->html();
            //dump($this->client->getResponse()->getStatusCode());
            $this->assertNotNull($crawler);
            $this->assertEquals(1, $crawler->filter('html:contains("Paramètres utilisateur")')->count());
        }


        public function testDatasIsRetrieveWell()
        {
            $id = 35;
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_settings', ['id' => $id]));

           $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
           $userRepos = $em->getRepository(Users::class);
           $user = $userRepos->find($id);

           $this->assertNotNull($user);
        }


        public function testUpdatingIsDone()
        {
            $id = 35;
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_settings', ['id' => $id]));

            //$user = new Users();
            $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
            $userRepos = $em->getRepository(Users::class);
            $newUser = $userRepos->find($id);

            $newUser->setPseudonyme('New Line');
            $newUser->setEmail('newLine@free.fr');
            $newUser->setPassword('new_password');

            $em->flush();

            $userUpdated = $newUser;

            $this->assertNotNull($userUpdated);
            $this->assertSame('New Line', $userUpdated->getPseudonyme());
            $this->assertSame('newLine@free.fr', $userUpdated->getEmail());
            $this->assertSame('new_password', $userUpdated->getPassword());
        }


        public function tearDown(): void
        {
            parent::tearDown();
        }
    }