Installing CCDNForum ForumBundle 1.2
====================================


## Dependencies:

1. [FOSUserBundle](http://github.com/FriendsOfSymfony/FOSUserBundle).
2. [EWZTimeBundle](http://github.com/excelwebzone/EWZRecaptchaBundle).
3. [PagerFanta](http://github.com/whiteoctober/Pagerfanta).
4. [PagerFantaBundle](http://github.com/whiteoctober/WhiteOctoberPagerfantaBundle).
5. [lib-geshi](http://github.com/codeconsortium/lib-geshi).
6. [CCDNComponent CommonBundle](http://github.com/codeconsortium/CommonBundle/tree/v1.2).
7. [CCDNComponent BBCodeBundle](http://github.com/codeconsortium/BBCodeBundle/tree/v1.2).
8. [CCDNComponent CrumbTrailBundle](http://github.com/codeconsortium/CrumbTrailBundle/tree/v1.2).
9. [CCDNComponent DashboardBundle](http://github.com/codeconsortium/DashboardBundle/tree/v1.2).
10. [CCDNComponent AttachmentBundle](http://github.com/codeconsortium/AttachmentBundle/tree/v1.2).
11. [CCDNForum AdminBundle](http://github.com/codeconsortium/CCDNForumForumBundle/tree/v1.2).
12. [CCDNForum KarmaBundle](http://github.com/codeconsortium/CCDNForumKarmaBundle/tree/v1.2).

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

Append the following to end of your deps file (found in the root of your Symfony2 installation):

``` ini
[CCDNForum_ForumBundle]
    git=http://github.com/codeconsortium/CCDNForumForumBundle.git
    target=/bundles/CCDNForum/ForumBundle
    version=v1.2
```

### Step 2: Register bundles with autoload.php.

Add the following to the registerNamespaces array in the method by appending it to the end of the array.

``` php
// app/autoload.php
$loader->registerNamespaces(array(
    'CCDNForum'        => __DIR__.'/../vendor/bundles',	
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
		new CCDNForum\ForumBundle\CCDNForumForumBundle(),
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
CCDNForumForumBundle:
    resource: "@CCDNForumForumBundle/Resources/config/routing.yml"
    prefix: /

ccdn_forum_forum_home_root:
    pattern: /
    defaults: { _controller: CCDNForumForumBundle:Category:index, _locale: en }
```

You can change the route of the standalone route to any route you like, it is included for convenience.

### Step 6: Update your app/config/config.yml.

In your app/config/config.yml add:    

``` yml
#
# for CCDNForum ForumBundle    
#
ccdn_forum_forum:
    user:
        profile_route: ccdn_user_profile_show_by_id
    template:
        engine: twig

```

**Warning:**

>Set the appropriate layout templates you want under the sections 'layout_templates' and the 
route to a users profile if you are not using the [CCDNUser\ProfileBundle](http://github.com/codeconsortium/CCDNUserProfileBundle). Otherwise use defaults.

### Step 7: Update your database schema.

From your projects root Symfony directory on the command line run:

``` bash
$ php app/console doctrine:schema:update --dump-sql
```

Take the SQL that is output and update your database manually.

**Warning:**

> Please take care when updating your database, check the output SQL before applying it.

### Step 8: Symlink assets to your public web directory.

From your projects root Symfony directory on the command line run:

``` bash
$ php app/console assets:install --symlink web/
```

### Step 9: Warmup the cache.

From your projects root Symfony directory on the command line run:

``` bash
$ php app/console cache:warmup
```

Change the layout template you wish to use for each page by changing the configs under the labelled section 'layout_templates'.

## Next Steps.

If you want to have the forum appear as your home page, add this route to your app/config/routing.yml:

``` yaml
ccdn_forum_forum_home_root:
    pattern: /
    defaults: { _controller: CCDNForumForumBundle:Category:index, _locale: en }
```

Installation should now be complete!

If you need further help/support, have suggestions or want to contribute please join the community at [Code Consortium](http://www.codeconsortium.com)

- [Return back to the docs index](index.md).
- [Configuration Reference](configuration_reference.md).
