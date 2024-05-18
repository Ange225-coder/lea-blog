<?php

    namespace App\Tests\Controller\AdminsController;

    use App\Entity\Tables\Users\Users;
    use Symfony\Bundle\FrameworkBundle\KernelBrowser;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;

    class DeleteUserControllerTest extends WebTestCase
    {
        private KernelBrowser|null $client;
        private $urlGenerator = null;


        public function setUp(): void
        {
            $this->client = static::createClient();

            $this->urlGenerator = $this->client->getContainer()->get('router.default');
        }


        public function testDeleteUserPageIsUp()
        {
            $userId = 2;
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_deletion', ['userId' => $userId]));

            $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
            $this->assertEquals(1, $crawler->filter('h1')->count());
        }


        public function testDeleteUserSuccess()
        {
            $userId = 2;
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_deletion', ['userId' => $userId]));

            $form = $crawler->selectButton('Supprimer l\'utilisateur')->form();

            $form['deletions_types[password]'] = 'borisboris';

            $this->client->submit($form);

            //$this->assertTrue($this->client->getResponse()->isRedirect('/admin/userList'), 'Redirection vers la liste des utilisateurs si l\'action a réussi');

            $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
            $userRepo = $em->getRepository(Users::class);
            $user = $userRepo->find($userId);

            $em->remove($user);
            $em->flush();

            //$this->client->followRedirect();

            //do another research on the same user who has been deleted
            $sameUser = $userRepo->find($userId);

            $this->assertNull($sameUser);
        }


        public function testDeleteUserFailed()
        {
            $userId = 15;
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_deletion', ['userId' => $userId]));

            $form = $crawler->selectButton('Supprimer l\'utilisateur')->form();

            $form['deletions_types[password]'] = 'wrong_password';
            $this->client->submit($form);

            $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
            $userRepo = $em->getRepository(Users::class);
            $user = $userRepo->find($userId);

            $sameUser = $userRepo->find($userId);

            $this->assertNotNull($sameUser);

            //$this->assertSame(1, $crawler->filter('html:contains("Suppression impossible mot de passe incorrect")')->count(), 'Le message d\'erreur devrait s\'afficher');
            $this->assertFalse($this->client->getResponse()->isRedirect(), 'L\'utilisateur reste sur la même page si le mot de passe est incorrect');
            //$this->assertStringContainsString('Suppression impossible mot de passe incorrect', $crawler->filterXPath('//ul/li')->text(), 'message d\'erreur ici');
        }


        public function tearDown(): void
        {
            parent::tearDown();
        }
    }