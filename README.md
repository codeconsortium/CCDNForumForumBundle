CCDNForum Forum Bundle(s) README.
=================================


Notes: 
------

This bundle is for the symfony framework and thusly requires Symfony 2.0.x and PHP 5.3.6
  
This project uses Doctrine 2.0.x and so does not require any specific database.
  

This file is part of the CCDNForum Bundle(s)

(c) CCDN (c) CodeConsortium <http://www.codeconsortium.com/> 

Available on github <http://www.github.com/codeconsortium/>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.


Dependencies:
-------------

1. [PagerFanta](https://github.com/whiteoctober/Pagerfanta).

2. [CCDNComponent BBCodeBundle](https://github.com/codeconsortium/BBCodeBundle).
3. [CCDNComponent CrumbTrailBundle](https://github.com/codeconsortium/CrumbTrailBundle).
4. [CCDNComponent CommonBundle](https://github.com/codeconsortium/CommonBundle).

5. [CCDNForum AdminBundle](https://github.com/codeconsortium/CCDNForumAdminBundle).
6. [CCDNForum ModeratorBundle](https://github.com/codeconsortium/CCDNForumModeratorBundle).


Installation:
-------------
 
1) Add this to your dependencies:

	[FOSUserBundle]
	    git=http://github.com/FriendsOfSymfony/FOSUserBundle.git
	    target=/bundles/FOS/UserBundle

	[EWZTimeBundle]
	    git=http://github.com/excelwebzone/EWZRecaptchaBundle.git
	    target=/bundles/EWZ/Bundle/RecaptchaBundle

	[pagerfanta]
	    git=http://github.com/whiteoctober/Pagerfanta.git

	[PagerfantaBundle]
	    git=http://github.com/whiteoctober/WhiteOctoberPagerfantaBundle.git
	    target=/bundles/WhiteOctober/PagerfantaBundle

	[CommonBundle]
	    git=http://github.com/codeconsortium/CommonBundle.git
	    target=/bundles/CCDNComponent/CommonBundle

	[BBCodeBundle]
	    git=http://github.com/codeconsortium/BBCodeBundle.git
	    target=/bundles/CCDNComponent/BBCodeBundle

	[CrumbTrailBundle]
	    git=http://github.com/codeconsortium/CrumbTrailBundle.git
	    target=/bundles/CCDNComponent/CrumbTrailBundle

	[CrumbTrailBundle]
	    git=http://github.com/codeconsortium/CrumbTrailBundle.git
	    target=/bundles/CCDNComponent/CrumbTrailBundle

	[CCDNForumForumBundle]
		git=http://github.com/codeconsortium/CCDNForumForumBundle.git
		target=/bundles/CCDNForum/ForumBundle

	[CCDNForumAdminBundle]
		git=http://github.com/codeconsortium/CCDNForumAdminBundle.git
		target=/bundles/CCDNForum/AdminBundle

	[CCDNForumModeratorBundle]
		git=http://github.com/codeconsortium/CCDNForumModeratorBundle.git
		target=/bundles/CCDNForum/ModeratorBundle

2) Download and install the dependencies by running this from the command line

	php bin/vendors install
   
3) In your AppKernel.php add the following bundles to the registerBundles method array:  

	new CCDNForum\ForumBundle\CCDNForumForumBundle(),
	new CCDNForum\AdminBundle\CCDNForumAdminBundle(),
	new CCDNForum\ModeratorBundle\CCDNForumModeratorBundle(), 
	
4) In your app/config/config.yml add (this is configs for all 3 forum bundles):    


	ccdn_forum_forum:
	    user:
	        profile_route: cc_profile_show_by_id
	    template:
	        engine: twig
	        theme: CCDNForumForumBundle:Form:fields.html.twig
	    category:
	        layout_templates:
	            index: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
	            show: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
	    board:
	        topics_per_page: 40
	        layout_templates:
	            show: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
	    topic:
	        posts_per_page: 10
	        layout_templates:
	            create: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
	            reply: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
	            show: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
	    post:
	        layout_templates:
	            show: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
	            flag: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
	            edit_post: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
	            edit_topic: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
	            delete_post: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig


Set the appropriate layout templates you want under the sections 'layout_templates' and the 
route to a users profile if you are not using the CCDNUser\ProfileBundle. Otherwise use defaults.
	  
5) In your app/config/routing.yml add:  

	forum:  
	    resource: "@CCDNForumForumBundle/Resources/config/routing.yml"  
	    resource: "@CCDNForumAdminBundle/Resources/config/routing.yml"  
	    resource: "@CCDNForumModeratorBundle/Resources/config/routing.yml"  

6) Symlink assets to your public web directory by running this in the command line:

	php app/console assets:install --symlink web/
	
Then your done, if you need further help/support, have suggestions or want to contribute please join the community at [www.codeconsortium.com](http://www.codeconsortium.com)
