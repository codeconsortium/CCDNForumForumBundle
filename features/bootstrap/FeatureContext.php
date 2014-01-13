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

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Mink\Element\NodeElement;

use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\HttpKernel\KernelInterface;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use CCDNForum\ForumBundle\features\bootstrap\WebUser;

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

    private function getAttributesFromElement(NodeElement $element)
    {
        $attr = array();
        $attr['id']    = strtolower($element->getAttribute('id'));
        $attr['name']  = strtolower($element->getAttribute('name'));
        $attr['label'] = strtolower($element->getAttribute('label'));
        $attr['value'] = strtolower($element->getAttribute('value'));
        $attr['text']  = strtolower($element->getText());
        $attr['title'] = strtolower($element->getAttribute('title'));

        return $attr;
    }

    private function isSubstringInArray($attributes, $searchStr)
    {
        foreach ($attributes as $attribute) {
            if ($attribute == $searchStr) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     * @Given /^I should see category named "([^"]*)" on category list$/
     */
    public function iShouldSeeCategoryNamedOnCategoryList($categoryId)
    {
        $this->iShouldSeeForTheQuery($categoryId, 'span.lead');
    }

    /**
     *
     * @Given /^I should not see category named "([^"]*)" on category list$/
     */
    public function iShouldNotSeeCategoryNamedOnCategoryList($categoryId)
    {
        $this->iShouldNotSeeForTheQuery($categoryId, 'span.lead');
    }

    /**
     *
     * @Given /^I should see board named "([^"]*)" on category list$/
     */
    public function iShouldSeeBoardNamedOnCategoryList($boardId)
    {
        $this->iShouldSeeForTheQuery($boardId, 'table > tbody > tr > td');
    }

    /**
     *
     * @Given /^I should not see board named "([^"]*)" on category list$/
     */
    public function iShouldNotSeeBoardNamedOnCategoryList($boardId)
    {
        $this->iShouldNotSeeForTheQuery($boardId, 'table > tbody > tr > td');
    }


    /**
     *
     * @Given /^I should see board named "([^"]*)" on topic list$/
     */
    public function iShouldSeeBoardNamedOnTopicList($topicId)
    {
        $this->iShouldSeeForTheQuery($topicId, 'span.lead');
    }

    /**
     *
     * @Given /^I should not see board named "([^"]*)" on topic list$/
     */
    public function iShouldNotSeeBoardNamedOnTopicList($topicId)
    {
        $this->iShouldNotSeeForTheQuery($topicId, 'span.lead');
    }

    /**
     *
     * @Given /^I should see topic named "([^"]*)" on topic list$/
     */
    public function iShouldSeeTopicNamedOnTopicList($topicId)
    {
        $this->iShouldSeeForTheQuery($topicId, 'table > tbody > tr > td');
    }

    /**
     *
     * @Given /^I should not see topic named "([^"]*)" on topic list$/
     */
    public function iShouldNotSeeTopicNamedOnTopicList($topicId)
    {
        $this->iShouldNotSeeForTheQuery($topicId, 'table > tbody > tr > td');
    }

    /**
     * @Given /^I follow "([^"]*)" from the links on post "([^"]*)"$/
     */
    public function iFollowFromTheLinksOnPost($linkText, $postId)
    {
        $link = null;
        $items = $this->getPage()->findAll('css', '[id^=' . $postId . '] > header a');

        $didFindIt = false;
        $linkTextLower = strtolower($linkText);
        $whatWeFound = array();
        foreach ($items as $item) {
            $attr = $this->getAttributesFromElement($item);
            $whatWeFound[] = $attr;
            if ($this->isSubstringInArray($attr, $linkTextLower)) {
                $didFindIt = true;
                $link = $item;
                break;
            }
        }

        WebTestCase::assertTrue($didFindIt, 'Could not find the link');
        WebTestCase::assertNotNull($link, 'Could not find the link');

        $link->click();
    }

    /**
     *
     * @Given /^I should see "([^"]*)" from the links on post "([^"]*)"$/
     */
    public function shouldSeeFromTheLinksOnPost($text, $postId)
    {
        // http://neverstopbuilding.net/simple-method-for-checking-for-order-with-behat/
        $items = array_map(
            function ($element) { return strtolower($element->getText()); },
            $this->getPage()->findAll('css', '[id^=' . $postId . '] > header ul li a')
        );

        $didFindIt = false;
        $textLower = strtolower($text);
        foreach ($items as $item) {
            if (strpos($item, $textLower) !== false) {
                $didFindIt = true;
                break;
            }
        }

        WebTestCase::assertTrue($didFindIt, "$text was not found.");
    }

    /**
     *
     * @Given /^I should not see "([^"]*)" from the links on post "([^"]*)"$/
     */
    public function shouldNotSeeFromTheLinksOnPost($text, $postId)
    {
        // http://neverstopbuilding.net/simple-method-for-checking-for-order-with-behat/
        $items = array_map(
            function ($element) { return strtolower($element->getText()); },
            $this->getPage()->findAll('css', '[id^=' . $postId . '] > header ul li a')
        );

        $didFindIt = false;
        $textLower = strtolower($text);
        foreach ($items as $item) {
            if (strpos($item, $textLower) !== false) {
                $didFindIt = true;
                break;
            }
        }

        WebTestCase::assertFalse($didFindIt, "$text was found but should not.");
    }

    /**
     * @Given /^I follow "([^"]*)" for the query "([^"]*)"$/
     */
    public function iFollowForTheQuery($linkText, $cssQuery)
    {
        $items = $this->getPage()->findAll('css', $cssQuery);

        $didFindIt = false;
        $link = null;
        $linkTextLower = strtolower($linkText);
        $whatWeFound = array();
        foreach ($items as $item) {
            $attr = $this->getAttributesFromElement($item);
            $whatWeFound[] = $attr;
            if ($this->isSubstringInArray($attr, $linkTextLower)) {
                $didFindIt = true;
                $link = $item;
                break;
            }
        }

        WebTestCase::assertTrue($didFindIt, 'Could not find the link');
        WebTestCase::assertNotNull($link, 'Could not find the link');

        $link->click();
    }

    /**
     *
     * @Then /^"([^"]*)" should precede "([^"]*)" for the query "([^"]*)"$/
     */
    public function shouldPrecedeForTheQuery($textBefore, $textAfter, $cssQuery)
    {
        // http://neverstopbuilding.net/simple-method-for-checking-for-order-with-behat/
        $items = array_map(
            function ($element) { return $element->getText(); },
            $this->getPage()->findAll('css', $cssQuery)
        );

        WebTestCase::assertTrue(in_array($textBefore, $items), 'The before text was not found!');
        WebTestCase::assertTrue(in_array($textAfter,  $items), 'The after text was not found!');
        WebTestCase::assertGreaterThan(array_search($textBefore, $items), array_search($textAfter, $items), "$textBefore does not proceed $textAfter");
    }

    /**
     *
     * @Given /^I should see "([^"]*)" for the query "([^"]*)"$/
     */
    public function iShouldSeeForTheQuery($text, $cssQuery)
    {
        // http://neverstopbuilding.net/simple-method-for-checking-for-order-with-behat/
        $items = array_map(
            function ($element) { return strtolower($element->getText()); },
            $this->getPage()->findAll('css', $cssQuery)
        );

        $didFindIt = false;
        $textLower = strtolower($text);
        foreach ($items as $item) {
            if (strpos($item, $textLower) !== false) {
                $didFindIt = true;
                break;
            }
        }

        WebTestCase::assertTrue($didFindIt, "$text was not found.");
    }

    /**
     *
     * @Given /^I should not see "([^"]*)" for the query "([^"]*)"$/
     */
    public function iShouldNotSeeForTheQuery($text, $cssQuery)
    {
        // http://neverstopbuilding.net/simple-method-for-checking-for-order-with-behat/
        $items = array_map(
            function ($element) { return strtolower($element->getText()); },
            $this->getPage()->findAll('css', $cssQuery)
        );

        $didFindIt = false;
        $textLower = strtolower($text);
        foreach ($items as $item) {
            if (strpos($item, $textLower) !== false) {
                $didFindIt = true;
                break;
            }
        }

        WebTestCase::assertFalse($didFindIt, "$text was found but should not.");
    }

    /**
     *
     * @Given /^I select from "([^"]*)" a date "([^"]*)"$/
     */
    public function iSelectFromADateDaysFromNow($cssQuery, $diff)
    {
        $items = $this->getPage()->findAll('css', $cssQuery);

        $fields = array();
        foreach ($items as $item) {
            $id = $item->getAttribute('id');

            if (substr($id, strlen($id) - strlen('year'), strlen($id)) == 'year') {
                $fields['year'] = $item;
                continue;
            }

            if (substr($id, strlen($id) - strlen('month'), strlen($id)) == 'month') {
                $fields['month'] = $item;
                continue;
            }

            if (substr($id, strlen($id) - strlen('day'), strlen($id)) == 'day') {
                $fields['day'] = $item;
                continue;
            }
        }

        WebTestCase::assertCount(3, $fields, 'Date fields could not be found!');
        WebTestCase::assertArrayHasKey('year', $fields, 'The year field could not be found!');
        WebTestCase::assertArrayHasKey('month', $fields, 'The month field could not be found!');
        WebTestCase::assertArrayHasKey('day', $fields, 'The day field could not be found!');

        $date = new \Datetime($diff);

        $filterFunc = function ($options, $has) {
            foreach ($options as $option) {
                if ($option->getText() == $has) {
                    return true;
                }
            }

            return false;
        };

        WebTestCase::assertTrue(call_user_func_array($filterFunc, array($fields['year']->findAll('css', 'option'), $date->format('Y'))));
        WebTestCase::assertTrue(call_user_func_array($filterFunc, array($fields['month']->findAll('css', 'option'), $date->format('M'))));
        WebTestCase::assertTrue(call_user_func_array($filterFunc, array($fields['day']->findAll('css', 'option'), $date->format('j'))));

        $fields['year']->selectOption($date->format('Y'));
        $fields['month']->selectOption($date->format('M'));
        $fields['day']->selectOption($date->format('j'));
    }

    /**
     *
     * @Given /^I dump$/
     */
    public function iDump()
    {
        ldd(substr($this->getPage()->getHtml(), 0, 9000));
    }
}
