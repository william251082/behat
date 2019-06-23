<?php

use AppBundle\Entity\User;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\RawMinkContext;

require_once __DIR__.'/../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context, SnippetAcceptingContext
{
    private static $container;

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
        $manager = self::$container->get('doctrine')->getManager();
        $manager->createQuery('DELETE FROM AppBundle:Product')->execute();
        $manager->createQuery('DELETE FROM AppBundle:User')->execute();
    }

    /**
     * @BeforeSuite
     */
    public static function bootstrapSymfony()
    {
        require __DIR__.'/../../app/autoload.php';
        require __DIR__.'/../../app/AppKernel.php';

        $kernel = new AppKernel('test', true);
        $kernel->boot();

        self::$container = $kernel->getContainer();
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

        $manager = self::$container->get('doctrine')
            ->getManager();
        $manager->persist($user);
        $manager->flush();
    }


    /**
     * @return \Behat\Mink\Element\DocumentElement
     */
    private function getPage()
    {
        return $this->getSession()->getPage();
    }
}
