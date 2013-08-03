<?php

/*
 * This file is part of the CCDNForum ForumBundle
 *
 * (c) CCDN (c) CodeConsortium <http://www.codeconsortium.com/>
 *
 * Available on github <http://www.github.com/codeconsortium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCDNForum\ForumBundle\features\bootstrap;

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\RawMinkContext;

use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 *
 * Features context.
 *
 * @category CCDNForum
 * @package  ForumBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNForumForumBundle
 *
 */
class FeatureContext extends RawMinkContext implements KernelAwareInterface
{
    /**
     * 
     * Kernel.
     *
     * @var KernelInterface
     */
    private $kernel;

    /**
     * 
     * Parameters.
     *
     * @var array
     */
    private $parameters;

    /**
     * 
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
        $this->parameters = $parameters;

        // Web user context.
        $this->useContext('web-user', new WebUser());

        //Request::enableHttpMethodParameterOverride();
    }

    /**
     * 
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * 
     * @BeforeScenario
     */
    public function purgeDatabase()
    {
        $entityManager = $this->kernel->getContainer()->get('doctrine.orm.entity_manager');

        $purger = new ORMPurger($entityManager);
        $purger->purge();
    }

	private function getPage()
	{
		return $this->getMainContext()->getSession()->getPage();
	}

    /**
     * 
     * @Given /^I am logged in as "([^"]*)"$/
     */
    public function iAmLoggedInAs($user)
    {
		$session = $this->getMainContext()->getSession();
		$session->setBasicAuth($user . '@foo.com', 'root');
    }

    /**
     * 
     * @Then /^"([^"]*)" should precede "([^"]*)" for the query "([^"]*)"$/
     */
    public function shouldPrecedeForTheQuery($textBefore, $textAfter, $cssQuery)
    {
		// http://neverstopbuilding.net/simple-method-for-checking-for-order-with-behat/
        $items = array_map(
            function ($element) {
                return $element->getText();
            },
            $this->getPage()->findAll('css', $cssQuery)
        );

		WebTestCase::assertTrue(in_array($textBefore, $items), 'The before text was not found!');
		WebTestCase::assertTrue(in_array($textAfter,  $items), 'The after text was not found!');
			
        WebTestCase::assertGreaterThan(
            array_search($textBefore, $items),
            array_search($textAfter, $items),
            "$textBefore does not proceed $textAfter"
        );
    }

    /**
     * 
     * @Given /^I should see "([^"]*)" for the query "([^"]*)"$/
     */
    public function shouldSeeForTheQuery($text, $cssQuery)
    {
		// http://neverstopbuilding.net/simple-method-for-checking-for-order-with-behat/
        $items = array_map(
            function ($element) {
                return $element->getText();
            },
            $this->getPage()->findAll('css', $cssQuery)
        );

        WebTestCase::assertTrue(
            in_array($text, $items),
            "$text was not found."
        );
    }

    /**
     * 
     * @Given /^I should not see "([^"]*)" for the query "([^"]*)"$/
     */
    public function shouldNotSeeForTheQuery($text, $cssQuery)
    {
		// http://neverstopbuilding.net/simple-method-for-checking-for-order-with-behat/
        $items = array_map(
            function ($element) {
                return $element->getText();
            },
            $this->getPage()->findAll('css', $cssQuery)
        );

        WebTestCase::assertFalse(
            in_array($text, $items),
            "$text was found but should not."
        );
    }
}
