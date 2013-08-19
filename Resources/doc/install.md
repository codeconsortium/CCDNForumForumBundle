Installing CCDNForum ForumBundle 1.x
====================================


## Dependencies:

1. [PagerFantaBundle](http://github.com/whiteoctober/WhiteOctoberPagerfantaBundle).
2. [CCDNComponent CommonBundle](http://github.com/codeconsortium/CCDNComponentCommonBundle).
3. [CCDNComponent BBCodeBundle](http://github.com/codeconsortium/CCDNComponentBBCodeBundle).
4. [CCDNComponent CrumbTrailBundle](http://github.com/codeconsortium/CCDNComponentCrumbTrailBundle).
5. [CCDNComponent DashboardBundle](http://github.com/codeconsortium/CCDNComponentDashboardBundle).
6. [CCDNForum AdminBundle](http://github.com/codeconsortium/CCDNForumAdminBundle).

## Installation:

Installation takes only 4 steps:

1. Download and install dependencies via Composer.
2. Register bundles with AppKernel.php.
3. Update your app/config/routing.yml.
4. Update your database schema.

### Step 1: Download and install dependencies via Composer.

Append the following to end of your applications composer.json file (found in the root of your Symfony2 installation):

``` js
// composer.json
{
    // ...
    "require": {
        // ...
        "codeconsortium/ccdn-forum-bundle": "dev-master"
    }
}
```

NOTE: Please replace ``dev-master`` in the snippet above with the latest stable branch, for example ``2.0.*``.

Then, you can install the new dependencies by running Composer's ``update``
command from the directory where your ``composer.json`` file is located:

``` bash
$ php composer.phar update
```

### Step 2: Register bundles with AppKernel.php.

Now, Composer will automatically download all required files, and install them
for you. All that is left to do is to update your ``AppKernel.php`` file, and
register the new bundle:

``` php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
		new CCDNForum\ForumBundle\CCDNForumForumBundle(),
		...
	);
}
```

### Step 3: Update your app/config/routing.yml.

In your app/config/routing.yml add:

``` yml
CCDNForumForumBundle-Admin:
    resource: "@CCDNForumForumBundle/Resources/config/routing/admin.yml"
    prefix: /{_locale}/forum/admin

CCDNForumForumBundle-Admin-Forum:
    resource: "@CCDNForumForumBundle/Resources/config/routing/admin-forum.yml"
    prefix: /{_locale}/forum/admin/manage-forums

CCDNForumForumBundle-Admin-Category:
    resource: "@CCDNForumForumBundle/Resources/config/routing/admin-category.yml"
    prefix: /{_locale}/forum/admin/manage-categories

CCDNForumForumBundle-Admin-Board:
    resource: "@CCDNForumForumBundle/Resources/config/routing/admin-board.yml"
    prefix: /{_locale}/forum/admin/manage-boards

CCDNForumForumBundle-Admin-Topic:
    resource: "@CCDNForumForumBundle/Resources/config/routing/admin-topic.yml"
    prefix: /{_locale}/forum/admin/manage-topics

CCDNForumForumBundle-Admin-Post:
    resource: "@CCDNForumForumBundle/Resources/config/routing/admin-post.yml"
    prefix: /{_locale}/forum/admin/manage-posts



CCDNForumForumBundle-Moderator-Topic:
    resource: "@CCDNForumForumBundle/Resources/config/routing/moderator-topic.yml"
    prefix: /{_locale}/forum/moderator/manage-topics

CCDNForumForumBundle-Moderator-Post:
    resource: "@CCDNForumForumBundle/Resources/config/routing/moderator-post.yml"
    prefix: /{_locale}/forum/moderator/manage-posts



CCDNForumForumBundle-User-Category:
    resource: "@CCDNForumForumBundle/Resources/config/routing/user-category.yml"
    prefix: /{_locale}/forum/category

CCDNForumForumBundle-User-Board:
    resource: "@CCDNForumForumBundle/Resources/config/routing/user-board.yml"
    prefix: /{_locale}/forum/board

CCDNForumForumBundle-User-Topic:
    resource: "@CCDNForumForumBundle/Resources/config/routing/user-topic.yml"
    prefix: /{_locale}/forum/topic

CCDNForumForumBundle-User-Post:
    resource: "@CCDNForumForumBundle/Resources/config/routing/user-post.yml"
    prefix: /{_locale}/forum/post

CCDNForumForumBundle-User-Subscription:
    resource: "@CCDNForumForumBundle/Resources/config/routing/user-subscription.yml"
    prefix: /{_locale}/forum/subscription

```

You can change the route of the standalone route to any route you like, it is included for convenience.

### Step 4: Update your database schema.

Make sure to add the ForumBundle to doctrines mapping configuration:

```
# app/config/config.yml
# Doctrine Configuration
doctrine:
    orm:
        default_entity_manager: default
        auto_generate_proxy_classes: "%kernel.debug%"
        resolve_target_entities:
            Symfony\Component\Security\Core\User\UserInterface: FOS\UserBundle\Entity\User
        entity_managers:
            default:
                mappings:
                    FOSUserBundle: ~
                    CCDNForumForumBundle:
                        mapping:              true
                        type:                 yml
                        dir:                  "Resources/config/doctrine"
                        alias:                ~
                        prefix:               CCDNForum\ForumBundle\Entity
                        is_bundle:            true
```

> FOSUserBundle is noted as an additional example, you can add multiple bundles here. You should however choose a UserBundle of your own and change the user entity that UserInterface will resolve to.

From your projects root Symfony directory on the command line run:

``` bash
$ php app/console doctrine:schema:update --dump-sql
```

Take the SQL that is output and update your database manually.

**Warning:**

> Please take care when updating your database, check the output SQL before applying it.

### Translations

If you wish to use default texts provided in this bundle, you have to make sure you have translator enabled in your config.

``` yaml
# app/config/config.yml

framework:
    translator: ~
```

## Next Steps.

Change the layout template you wish to use for each page by changing the configs under the labelled section 'layout_templates'.

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
