<?php

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;

require_once __DIR__.'/../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context, SnippetAcceptingContext
{
    use KernelDictionary;

    private $currentUser;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @BeforeScenario
     */
    public function clearData()
    {
        $manager = $this->getContainer()->get('doctrine')->getManager();
        $purger = new ORMPurger($manager);
        $purger->purge();
    }

    /**
     * @When I fill in search box with :term
     */
    public function iFillInSearchBoxWith($term)
    {
        //name="searchTerm"
        $searchBox = $this->getPage()
            ->find('css', '[name="searchTerm"]');

        assertNotNull($searchBox, 'The search box was not found');

        $searchBox->setValue($term);
    }

    /**
     * @When I press search button
     */
    public function iPressSearchButton()
    {
        $button = $this->getPage()
            ->find('css', '#search_submit');

        assertNotNull($button, 'The search button could not be found');

        $button->press();
    }

    /**
     * @Given there is an admin user :username with password :password
     */
    public function thereIsAnAdminUserWithPassword($username, $password)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setPlainPassword($password);
        $user->setRoles(['ROLE_ADMIN']);

        $manager = $this->getContainer()->get('doctrine')
            ->getManager();
        $manager->persist($user);
        $manager->flush();

        return $user;
    }

    /**
     * @Given There are :count products
     */
    public function thereAreProducts($count)
    {
        $this->createProducts($count);
    }

    /**
     * @Given I author :count products
     */
    public function iAuthorProducts($count)
    {
        $this->createProducts($count, $this->currentUser);
    }

    /**
     * @When I click :linkText
     */
    public function iClick($linkText)
    {
        $this->getPage()->clickLink($linkText);
    }

    /**
     * @When I wait for the modal to load
     */
    public function iWaitForTheModalToLoad()
    {
        $this->getSession()->wait(
            5000,
            "$('.modal:visible').length"
        );
    }

    /**
     * Pauses the scenario until the user presses a key. Useful when debugging a scenario.
     *
     * @Then (I )break
     */
    public function iPutABreakpoint()
    {
        fwrite(STDOUT, "\033[s    \033[93m[Breakpoint] Press \033[1;93m[RETURN]\033[0;93m to continue...\033[0m");
        while (fgets(STDIN, 1024) == '') {}
        fwrite(STDOUT, "\033[u");
        return;
    }

    /**
     * Saving a screenshot
     *
     * @When I save a screenshot to :filename
     */
    public function iSaveAScreenshotIn($filename)
    {
        sleep(1);
        $this->saveScreenshot($filename, __DIR__.'/../..');
    }

    /**
     * @Then I should see :count products
     */
    public function iShouldSeeProducts($count)
    {
        $table = $this->getPage()->find('css', 'table.table');
        assertNotNull($table, 'Could not find a table');

        assertCount(intval($count), $table->findAll('css', 'tbody tr'));
    }

    /**
     * @Given I am logged in as an admin
     */
    public function iAmLoggedInAsAnAdmin()
    {
        $this->currentUser = $this->thereIsAnAdminUserWithPassword('admin', 'admin');
        $this->visitPath('/login');
        $this->getPage()->fillField('Username', 'admin');
        $this->getPage()->fillField('Password', 'admin');
        $this->getPage()->pressButton('Login');
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
    /**
     * @return \Behat\Mink\Element\DocumentElement
     */
    private function getPage()
    {
        return $this->getSession()->getPage();
    }

    private function createProducts($count, User $author = null)
    {
        $manager = $this->getEntityManager();

        for ($i = 0; $i < $count; $i++) {
            $product = new Product();
            $product->setName('Product '.$i);
            $product->setPrice(rand(10, 1000));
            $product->setDescription('lorem');

            if ($author) {
                $product->setAuthor($author);
            }

            $manager->persist($product);
        }

        $manager->flush();
    }
}
