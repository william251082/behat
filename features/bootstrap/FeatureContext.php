<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\RawMinkContext;

require_once __DIR__.'/../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context, SnippetAcceptingContext
{
    private $output;
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
    public function moveIntoTestDir()
    {
        if (!is_dir('test')) {
            mkdir('test');
        }
        chdir('test');
    }

    /**
     * @AfterScenario
     */
    public function moveOutOfTestDir()
    {
        chdir('..');
        if (is_dir('test')) {
            system('rm -r '.realpath('test'));
        }
    }

    /**
     * @Given there is a file named :filename
     */
    public function thereIsAFileNamed($filename)
    {
        touch($filename);
    }

    /**
     * @When I run :command
     */
    public function iRun($command)
    {
        $this->output = shell_exec($command);
    }

    /**
     * @Then I should see :string in the output
     */
    public function iShouldSeeInTheOutput($string)
    {
        assertContains(
            $string,
            $this->output,
            sprintf('Did not see %s in the output %s', $string, $this->output)
        );
    }

    /**
     * @Given there is a dir named :dir
     */
    public function thereIsADirNamed($dir)
    {
        mkdir($dir);
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
     * @return \Behat\Mink\Element\DocumentElement
     */
    private function getPage()
    {
        return $this->getSession()->getPage();
    }
}
