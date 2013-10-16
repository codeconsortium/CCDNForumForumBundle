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

namespace CCDNForum\ForumBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use CCDNForum\ForumBundle\Entity\Category;
use CCDNForum\ForumBundle\Entity\Board;
use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;

/**
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
class LoadForumData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function checkCategory($categoryName)
    {
        return $this->container->get('ccdn_forum_forum.repository.category')->findOneCategoryByName($categoryName);
    }

    protected function checkBoard($boardName)
    {
        return $this->container->get('ccdn_forum_forum.repository.board')->findOneCategoryByName($boardName);
    }

    protected function createCategory($name, $order)
    {
        $category = new Category();

        $category->setName($name);
        $category->setListOrderPriority($order);

        return $category;
    }

    protected function createBoard($category, $order, $name, $description, $cachedTopicCount, $cachedPostCount, array $boardReadRoles = array(), array $topicCreateRoles = array(), array $topicReplyRoles = array())
    {
        $board = new Board();

        $board->setCategory($category);
        $board->setName($name);
        $board->setDescription($description);
        $board->setCachedTopicCount($cachedTopicCount);
        $board->setCachedPostCount($cachedPostCount);
        $board->setListOrderPriority($order);
        $board->setReadAuthorisedRoles($boardReadRoles);
        $board->setTopicCreateAuthorisedRoles($topicCreateRoles);
        $board->setTopicReplyAuthorisedRoles($topicReplyRoles);

        return $board;
    }

    protected function createTopic($board, $title)
    {
        $topic = new Topic();

        $topic->setBoard($board);
        $topic->setTitle($title);
        $topic->setCachedViewCount(0);
        $topic->setCachedReplyCount(0);
        $topic->setSticky(false);
        $topic->setClosed(false);
        $topic->setDeleted(false);

        return $topic;
    }

    protected function createPost($topic, $body, $author)
    {
        $post = new Post();

        $post->setTopic($topic);
        $post->setCreatedBy($author);
        $post->setCreatedDate(new \DateTime());
        $post->setDeleted(false);
        $post->setBody($body);

        return $post;
    }

    /**
     *
     * @access public
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $referencedUserAdmin = $this->getReference($this->container->getParameter('ccdn_forum_forum.fixtures.user_admin'));

        if (null == $this->checkCategory('General')) {
            $categoryGeneral = $this->createCategory('General', 0);

            $manager->persist($categoryGeneral);
            $manager->flush();

            if (null == $this->checkBoard('Introductions')) {
                $boardIntroductions = $this->createBoard($categoryGeneral, 0, 'Introductions', 'Say hello and introduce yoursel!' . "\n" . 'Tell us a little about yourself.', 1, 1);
                $topic = $this->createTopic($boardIntroductions, 'Welcome to CCDNForum.');
                $post = $this->createPost($topic, 'Welcome to the CodeConsortium Forum.' . "\n" . 'Please introduce yourself and make yourself at home. :)', $manager->merge($referencedUserAdmin));

                $manager->persist($boardIntroductions);
                $manager->persist($topic);
                $manager->persist($post);
                $manager->flush();

                // Set last post references.
                $boardIntroductions->setLastPost($post);
                $topic->setFirstPost($post);
                $topic->setLastPost($post);

                $manager->persist($topic, $boardIntroductions);
                $manager->flush();

                $this->addReference('forum-post', $post);
            }
        }
    }

    /**
     *
     * @access public
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}
