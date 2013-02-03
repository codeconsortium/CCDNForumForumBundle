Installing CCDNForum ForumBundle 1.2
====================================


## Dependencies:

1. [PagerFantaBundle](http://github.com/whiteoctober/WhiteOctoberPagerfantaBundle).
2. [CCDNComponent CommonBundle](http://github.com/codeconsortium/CommonBundle/tree/v1.2).
3. [CCDNComponent BBCodeBundle](http://github.com/codeconsortium/BBCodeBundle/tree/v1.2).
4. [CCDNComponent CrumbTrailBundle](http://github.com/codeconsortium/CrumbTrailBundle/tree/v1.2).
5. [CCDNComponent DashboardBundle](http://github.com/codeconsortium/DashboardBundle/tree/v1.2).
6. [CCDNForum AdminBundle](http://github.com/codeconsortium/CCDNForumForumBundle/tree/v1.2).

## Installation:

Installation takes only 4 steps:

1. Download and install dependencies via Composer.
2. Register bundles with AppKernel.php.
3. Update your app/config/routing.yml.
4. Update your database schema.

### Step 1: Download and install dependencies via Composer.

Append the following to end of your deps file (found in the root of your Symfony2 installation):

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
CCDNForumForumBundle:
    resource: "@CCDNForumForumBundle/Resources/config/routing.yml"
    prefix: /
```

You can change the route of the standalone route to any route you like, it is included for convenience.

**Warning:**

>Set the appropriate layout templates you want under the sections 'layout_templates' and the
route to a users profile if you are not using the [CCDNUser\ProfileBundle](http://github.com/codeconsortium/CCDNUserProfileBundle). Otherwise use defaults.

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
