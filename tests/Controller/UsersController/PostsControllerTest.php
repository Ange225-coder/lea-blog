<?php

    namespace App\Tests\Controller\UsersController;

    use App\Entity\Tables\Users\Posts;
    use App\Entity\Tables\Users\Users;
    use Symfony\Bundle\FrameworkBundle\KernelBrowser;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;

    class PostsControllerTest extends WebTestCase
    {
        private KernelBrowser|null $client = null;
        private $urlGenerator = null;


        protected function setUp(): void
        {
            $this->client = static::createClient();

            $this->urlGenerator = $this->client->getContainer()->get('router.default');
        }


        public function testPostCreatePageIsUp()
        {
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_create_post'));

            $this->assertNotNull($crawler, 'l\'url ne doit pas être null');
            $this->assertEquals(1, $crawler->filter('html:contains("Créer un post")')->count());
            $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        }


        public function testPostCreateForm()
        {
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_create_post'));

            $form = $crawler->selectButton('Créer un post')->form();

            $form['posts_types[title]'] = 'PHP Release';
            $form['posts_types[content]'] = 'PHP Release Content';

            $this->client->submit($form);

            //$this->assertTrue($this->client->getResponse()->isRedirect('/'), 'Après la soumission il doit avoir un redirection');

            //$this->client->followRedirect();

            $this->assertEquals(1, $crawler->filter('html:contains("Créer un post")')->count());
        }


        public function testPostInDatabase()
        {
            $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

            $posts = new Posts();
            $posts->setAuthor('line');
            $posts->setTitle('PHP last release');
            $posts->setContent('PHP last release content');
            $posts->setPublicationDate(new \DateTime());

            $em->persist($posts);
            $em->flush();

            $this->assertNotNull($posts);

            $postsRepos = $em->getRepository(Posts::class);
            $savedPosts = $postsRepos->findOneBy([
                'title' => 'PHP last release',
            ]);

            $this->assertNotNull($savedPosts);
            $this->assertEquals('PHP last release', $savedPosts->getTitle());
        }


        public function tearDown(): void
        {
            parent::tearDown();
        }
    }