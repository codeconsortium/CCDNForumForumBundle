Installing CCDNForum ForumBundle 1.0
====================================


## Dependencies:

1. [FOSUserBundle](http://github.com/FriendsOfSymfony/FOSUserBundle).
2. [EWZTimeBundle](http://github.com/excelwebzone/EWZRecaptchaBundle).
3. [PagerFanta](http://github.com/whiteoctober/Pagerfanta).
4. [PagerFantaBundle](http://github.com/whiteoctober/WhiteOctoberPagerfantaBundle).
5. [CCDNComponent CommonBundle](http://github.com/codeconsortium/CommonBundle).
6. [CCDNComponent BBCodeBundle](http://github.com/codeconsortium/BBCodeBundle).
7. [CCDNComponent CrumbTrailBundle](http://github.com/codeconsortium/CrumbTrailBundle).
8. [CCDNComponent DashboardBundle](http://github.com/codeconsortium/DashboardBundle).
9. [CCDNComponent AttachmentBundle](http://github.com/codeconsortium/AttachmentBundle).
10. [CCDNForum AdminBundle](http://github.com/codeconsortium/CCDNForumForumBundle).
11. [CCDNForum KarmaBundle](http://github.com/codeconsortium/CCDNForumKarmaBundle).
12. [CCDNForum ModeratorBundle](http://github.com/codeconsortium/CCDNForumModeratorBundle).

## Installation:

Installation takes only 9 steps:

1. Download and install the dependencies.
2. Register bundles with autoload.php.
3. Register bundles with AppKernel.php.  
4. Run vendors install script.
5. Update your app/config/routing.yml. 
6. Update your app/config/config.yml. 
7. Update your database schema.
8. Symlink assets to your public web directory.
9. Warmup the cache.


### Step 1: Download and install the dependencies.

You can set deps to include:

``` ini
[pagerfanta]
    git=http://github.com/whiteoctober/Pagerfanta.git

[PagerfantaBundle]
    git=http://github.com/whiteoctober/WhiteOctoberPagerfantaBundle.git
    target=/bundles/WhiteOctober/PagerfantaBundle


[CCDNComponentAttachmentBundle]
    git=http://github.com/codeconsortium/AttachmentBundle.git
    target=/bundles/CCDNComponent/AttachmentBundle

[lib-geshi]
	git=http://github.com/codeconsortium/lib-geshi.git
	target=/geshi

[CCDNComponentBBCodeBundle]
    git=http://github.com/codeconsortium/BBCodeBundle.git
    target=/bundles/CCDNComponent/BBCodeBundle

[CCDNComponentCommonBundle]
    git=http://github.com/codeconsortium/CommonBundle.git
    target=/bundles/CCDNComponent/CommonBundle

[CCDNComponentCrumbTrailBundle]
    git=http://github.com/codeconsortium/CrumbTrailBundle.git
    target=/bundles/CCDNComponent/CrumbTrailBundle

[CCDNComponentDashboardBundle]
    git=http://github.com/codeconsortium/DashboardBundle.git
    target=/bundles/CCDNComponent/DashboardBundle


[CCDNForum_ForumBundle]
    git=http://github.com/codeconsortium/CCDNForumForumBundle.git
    target=/bundles/CCDNForum/ForumBundle

[CCDNForum_KarmaBundle]
    git=http://github.com/codeconsortium/CCDNForumKarmaBundle.git
    target=/bundles/CCDNForum/KarmaBundle

[CCDNForum_ModeratorBundle]
    git=http://github.com/codeconsortium/CCDNForumModeratorBundle.git
    target=/bundles/CCDNForum/ModeratorBundle

[CCDNForum_AdminBundle]
    git=http://github.com/codeconsortium/CCDNForumAdminBundle.git
    target=/bundles/CCDNForum/AdminBundle
```

### Step 2: Register bundles with autoload.php.

Add the following to the registerNamespaces array in the method by appending it to the end of the array.

``` php
// app/autoload.php
$loader->registerNamespaces(array(
	'WhiteOctober\PagerfantaBundle' => __DIR__.'/../vendor/bundles',
	'Pagerfanta'                    => __DIR__.'/../vendor/pagerfanta/src',
    'CCDNComponent'    => __DIR__.'/../vendor/bundles',
    'CCDNForum'        => __DIR__.'/../vendor/bundles',	
	...
));
```

Add the following to the registerPrefixes array in the method by appending it to the end of the array.

``` php
// app/autoload.php
$loader->registerPrefixes(array(
	'Geshi_'		   => __DIR__.'/../vendor/geshi/lib',
	**...**
));
```

### Step 3: Register bundles with AppKernel.php.

In your AppKernel.php add the following bundles to the registerBundles method array:  

``` php
	// app/AppKernel.php
	public function registerBundles()
	{
	    $bundles = array(
			new CCDNComponent\CommonBundle\CCDNComponentCommonBundle(),
			new CCDNComponent\BBCodeBundle\CCDNComponentBBCodeBundle(),
			new CCDNComponent\CrumbTrailBundle\CCDNComponentCrumbTrailBundle(),
			new CCDNComponent\DashboardBundle\CCDNComponentDashboardBundle(),
			new CCDNComponent\AttachmentBundle\CCDNComponentAttachmentBundle(),
	
			new CCDNForum\ForumBundle\CCDNForumForumBundle(),
			new CCDNForum\AdminBundle\CCDNForumAdminBundle(),
			new CCDNForum\ModeratorBundle\CCDNForumModeratorBundle(),
			new CCDNForum\KarmaBundle\CCDNForumKarmaBundle(),
			**...**
		);
	}
```

### Step 4: Run vendors install script.

From your projects root Symfony directory on the command line run:

``` bash
$ php bin/vendors install
```

### Step 5: Update your app/config/routing.yml.

In your app/config/routing.yml add:  

``` yml
CCDNComponentDashboardBundle:
	resource: @"CCDNComponentDashboardBundle/Resources/config/routing.yml"
	prefix: /

CCDNComponentAttachmentBundle:
    resource: "@CCDNComponentAttachmentBundle/Resources/config/routing.yml"
    prefix: /

CCDNForumForumBundle:
    resource: "@CCDNForumForumBundle/Resources/config/routing.yml"
    prefix: /

CCDNForumKarmaBundle:
    resource: "@CCDNForumKarmaBundle/Resources/config/routing.yml"
    prefix: /

CCDNForumModeratorBundle:
    resource: "@CCDNForumModeratorBundle/Resources/config/routing.yml"
    prefix: /

CCDNForumAdminBundle:
    resource: "@CCDNForumAdminBundle/Resources/config/routing.yml"
    prefix: /

```
	
### Step 6: Update your app/config/config.yml.

In your app/config/config.yml add (this is configs for all 3 forum bundles):    

``` yml
#
# for CCDNForum ForumBundle    
#
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
    draft:
        drafts_per_page: 10
        layout_templates:
            list: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig

#
# for CCDNForum AdminBundle
#		
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

#
# for CCDNForum ModeratorBundle
#
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

#
# for CCDNForum KarmaBundle
#
ccdn_forum_karma:
    user:
        profile_route: cc_profile_show_by_id 
    template:
        engine: twig
        theme: CCDNForumKarmaBundle:Form:fields.html.twig
    review:
        reviews_per_page: 40
        layout_templates:
            review_all: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
    rate:
        layout_templates:
            rate: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig

#
# for CCDNComponent AttachmentBundle.
#
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

### Step 7: Update your database schema.

From your projects root Symfony directory on the command line run:

``` bash
$ php app/console doctrine:schema:update --dump-sql
```

Take the SQL that is output and update your database manually.

**Warning:**

> Please take care when updating your database, check the output SQL before applying it.

### Step 8: Symlink assets to your public web directory.

Symlink assets to your public web directory by running this in the command line:

``` bash
$ php app/console assets:install --symlink web/
```

### Step 9: Warmup the cache.

``` bash
$ php app/console cache:warmup
```

Change the layout template you wish to use for each page by changing the configs under the labelled section 'layout_templates'.

## Next Steps.

Now your done!

If you need further help/support, have suggestions or want to contribute please join the community at [Code Consortium](http://www.codeconsortium.com)
