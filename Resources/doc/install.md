Installing CCDNForum ForumBundle 2.x
====================================

## Dependencies:

> Note you will need a User Bundle so that you can map the UserInterface to your own User entity. You can use whatecer User Bundle you prefer. FOSUserBundle is highly rated.

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
# app/config/routing.yml
CCDNForumForumBundle:
    resource: "@CCDNForumForumBundle/Resources/config/routing.yml"
```

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
            Symfony\Component\Security\Core\User\UserInterface: Acme\YourUserBundle\Entity\User
        entity_managers:
            default:
                mappings:
                    CCDNForumForumBundle:
                        mapping:              true
                        type:                 yml
                        dir:                  "Resources/config/doctrine"
                        alias:                ~
                        prefix:               CCDNForum\ForumBundle\Entity
                        is_bundle:            true
```

Replace Acme\YourUserBundle\Entity\User with the user class of your chosen user bundle.

From your projects root Symfony directory on the command line run:

``` bash
$ php app/console doctrine:schema:update --dump-sql
```

Take the SQL that is output and update your database manually.

**Warning:**

> Please take care when updating your database, check the output SQL before applying it.

[Upgrading to 2.0 from previous install](upgrading_to_2_0.md).

### Translations

If you wish to use default texts provided in this bundle, you have to make sure you have translator enabled in your config.

``` yaml
# app/config/config.yml
framework:
    translator: ~
```

# Add a default forum

In order to use the forum bundle you will first need to have setup a default forum.

To do this, navigate to en/forum/admin/manage-forums/ and click on 'Create Forum', then add a new forum calls 'default', then you are ready to go! Enjoy!

## Next Steps.

Change the layout template you wish to use for each page by changing the configs under the labelled section 'layout_templates'.

If you want to have the forum appear as your home page, add this route to your app/config/routing.yml:

``` yaml
# app/config/routing.yml
ccdn_homepage:
    pattern: /
    defaults: { _controller: CCDNForumForumBundle:UserCategory:index, _locale: en, forumName: default }
```

Installation should now be complete!

If you need further help/support, have suggestions or want to contribute please join the community at [Code Consortium](http://www.codeconsortium.com)

- [Return back to the docs index](index.md).
- [Configuration Reference](configuration_reference.md).
