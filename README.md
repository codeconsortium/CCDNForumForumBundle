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

1. [FOSUserBundle](http://github.com/FriendsOfSymfony/FOSUserBundle).
2. [EWZTimeBundle](http://github.com/excelwebzone/EWZRecaptchaBundle).
3. [PagerFanta](https://github.com/whiteoctober/Pagerfanta).
4. [PagerFantaBundle](http://github.com/whiteoctober/WhiteOctoberPagerfantaBundle).
5. [CCDNComponent CommonBundle](https://github.com/codeconsortium/CommonBundle).
6. [CCDNComponent BBCodeBundle](https://github.com/codeconsortium/BBCodeBundle).
7. [CCDNComponent CrumbTrailBundle](https://github.com/codeconsortium/CrumbTrailBundle).
8. [CCDNComponent DashboardBundle](https://github.com/codeconsortium/DashboardBundle).
9. [CCDNComponent AttachmentBundle](https://github.com/codeconsortium/AttachmentBundle).
	
Installation:
-------------
 
1) Download and install the dependencies.
   
   You can set deps to include:

```sh
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

[CCDNComponentCommonBundle]
    git=http://github.com/codeconsortium/CommonBundle.git
    target=/bundles/CCDNComponent/CommonBundle

[CCDNComponentBBCodeBundle]
    git=http://github.com/codeconsortium/BBCodeBundle.git
    target=/bundles/CCDNComponent/BBCodeBundle

[CCDNComponentCrumbTrailBundle]
    git=http://github.com/codeconsortium/CrumbTrailBundle.git
    target=/bundles/CCDNComponent/CrumbTrailBundle

[CCDNComponentDashboardBundle]
    git=http://github.com/codeconsortium/DashboardBundle.git
    target=/bundles/CCDNComponent/DashboardBundle

[CCDNComponentAttachmentBundle]
	git=http://github.com/codeconsortium/AttachmentBundle.git
	target=/bundles/CCDNComponent/AttachmentBundle
	
[CCDNForum]
    git=http://github.com/codeconsortium/CCDNForum.git
    target=/bundles/CCDNForum
```
add to your autoload:

```php
    'CCDNComponent'    => __DIR__.'/../vendor/bundles',
    'CCDNForum'        => __DIR__.'/../vendor/bundles',
```
and then run `bin/vendors install` script.

2) In your AppKernel.php add the following bundles to the registerBundles method array:  

```php
	new CCDNComponent\CommonBundle\CCDNComponentCommonBundle(),
	new CCDNComponent\BBCodeBundle\CCDNComponentBBCodeBundle(),
	new CCDNComponent\CrumbTrailBundle\CCDNComponentCrumbTrailBundle(),
	new CCDNComponent\DashboardBundle\CCDNComponentDashboardBundle(),
	new CCDNComponent\AttachmentBundle\CCDNComponentAttachmentBundle(),
	new CCDNForum\ForumBundle\CCDNForumForumBundle(),
	new CCDNForum\ForumAdminBundle\CCDNForumForumAdminBundle(),
	new CCDNForum\ForumModeratorBundle\CCDNForumForumModeratorBundle(),
```
	
3) In your app/config/config.yml add (this is configs for all 3 forum bundles):    

```sh
# for CCDNForum ForumBundle    
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
        posts_per_page: 5
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
    draft:
        drafts_per_page: 10
        layout_templates:
            list: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
ccdn_forum_admin:
    user:
        profile_route: cc_profile_show_by_id
    template:
        engine: twig
        theme: CCDNForumAdminBundle:Form:fields.html.twig
    category:
        layout_templates:
            create: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
            delete_category: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
            edit: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
            index: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
    board:
        topics_per_page: 40
        layout_templates:
            create: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
            delete_board: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
            edit: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig

ccdn_forum_moderator:
    user:
        profile_route: cc_profile_show_by_id
    template:
        engine: twig
        theme: CCDNForumModeratorBundle:Form:fields.html.twig
    flag:
        flags_per_page: 40
        layout_templates:
            flag_mark: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
            show_flag: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
            show_flagged: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
    topic:
        topics_per_page: 40
        posts_per_page: 20
        layout_templates:
            change_board: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
            show_closed: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
            delete_topic: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
    post:
        posts_per_page: 40
        layout_templates:
            show_locked: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig

# for CCDNComponent AttachmentBundle
ccdn_component_attachment:
    user:
        profile_route: cc_profile_show_by_id
    template:
        engine: twig
        theme: CCDNComponentAttachmentBundle:Form:fields.html.twig
    store:
        dir: %ccdn_attachment_file_store%
    quota_per_user:
        max_files_quantity: 20
        max_filesize_per_file: 300KiB
        max_total_quota: 1000KiB
    attachment:
        layout_templates:
            list: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
            upload: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
```

Set the appropriate layout templates you want under the sections 'layout_templates' and the 
route to a users profile if you are not using the CCDNUser\ProfileBundle. Otherwise use defaults.

and in your app/config/parameters.ini add and set the value to the directory where you want to keep your attachment files:

```sh
ccdn_attachment_file_store= "/your/folder/where/you/want/to/store/attachments"
```
 
4) In your app/config/routing.yml add:  

```sh
CCDNComponentDashboardBundle:
	resource: @"CCDNComponentDashboardBundle/Resources/config/routing.yml"
	prefix: /

CCDNComponentAttachmentBundle:
    resource: "@CCDNComponentAttachmentBundle/Resources/config/routing.yml"
    prefix: /

CCDNForumModeratorBundle:
    resource: "@CCDNForumModeratorBundle/Resources/config/routing.yml"
    prefix: /

CCDNForumAdminBundle:
    resource: "@CCDNForumAdminBundle/Resources/config/routing.yml"
    prefix: /

CCDNForumForumBundle:
    resource: "@CCDNForumForumBundle/Resources/config/routing.yml"
    prefix: /
```

5) Symlink assets to your public web directory by running this in the command line:

```sh
	php app/console assets:install --symlink web/
```
	
Then your done, if you need further help/support, have suggestions or want to contribute please join the community at [www.codeconsortium.com](http://www.codeconsortium.com)
