CCDNForum ForumBundle README.
=============================

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5ad1db2a-0342-4716-8d29-0c056f784c79/mini.png)](https://insight.sensiolabs.com/projects/5ad1db2a-0342-4716-8d29-0c056f784c79) [![Build Status](https://secure.travis-ci.org/codeconsortium/CCDNForumForumBundle.png)](https://travis-ci.org/codeconsortium/CCDNForumForumBundle) [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/codeconsortium/CCDNForumForumBundle/badges/quality-score.png?s=76b9ab276cb72457064e0a4a5ca30d1c98acdf7f)](https://scrutinizer-ci.com/g/codeconsortium/CCDNForumForumBundle/) [![Code Coverage](https://scrutinizer-ci.com/g/codeconsortium/CCDNForumForumBundle/badges/coverage.png?s=156a3d8a1dd2a79d465ea564ab991fe91a842fce)](https://scrutinizer-ci.com/g/codeconsortium/CCDNForumForumBundle/) [![Latest Stable Version](https://poser.pugx.org/codeconsortium/ccdn-forum-bundle/v/stable.png)](https://packagist.org/packages/codeconsortium/ccdn-forum-bundle) [![Total Downloads](https://poser.pugx.org/codeconsortium/ccdn-forum-bundle/downloads.png)](https://packagist.org/packages/codeconsortium/ccdn-forum-bundle)

## Notes: 

This bundle is for the symfony framework and requires Symfony >= 2.1.x and PHP >= 5.3.2
  
This project uses Doctrine >= 2.1.x and so does not require any specific database.
  

&copy; CCDN &copy; [CodeConsortium](http://www.codeconsortium.com/)

Available on:
* [Github](http://www.github.com/codeconsortium/CCDNForumForumBundle)
* [Packagist](https://packagist.org/packages/codeconsortium/ccdn-forum-bundle)
* [KnpBundles](http://knpbundles.com/codeconsortium/CCDNForumForumBundle)

Theme and Sprite graphics courtesy of [twitter bootstrap](http://twitter.github.com/bootstrap/index.html) and [GLYPHICONS](http://glyphicons.com/).

Other graphics are works of CodeConsortium.

For the full copyright and license information, please view the [LICENSE](http://github.com/codeconsortium/CCDNForumForumBundle/blob/master/Resources/meta/LICENSE) file that was distributed with this source code.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5ad1db2a-0342-4716-8d29-0c056f784c79/big.png)](https://insight.sensiolabs.com/projects/5ad1db2a-0342-4716-8d29-0c056f784c79)
[![knpbundles.com](http://knpbundles.com/codeconsortium/CCDNForumForumBundle/badge-short)](http://knpbundles.com/codeconsortium/CCDNForumForumBundle) 

## Description:

This is a ForumBundle for Symfony (>= 2.1.x) for building a bulletin board forum community where users can create and reply to discussions.

## Features.

ForumBundle Provides the following features:

1. Forum Management Create/Edit/Delete Forums.
2. Category Management Create/Edit/Delete Categories.
3. Board Management Create/Edit/Delete Boards.
4. Create and Reply to Topics.
5. Edit Topics/Posts.
6. Support for sticky topics.
7. Built in pagination using KnpPaginator.
8. Supports soft-deleting of Topics and Posts for recovery by Admin or later hard-deletion.
9. Topics can be closed and Posts locked from editing by forum moderators.
10. Topics and Posts can be Previewed before posting.
11. Topics can be subscribed to and followed in the Topic subscription page.
12. Optional integration with [DashboardBundle](http://github.com/codeconsortium/CCDNComponentDashboardBundle) for easy site navigation.
13. Utilises Twitter-Bootstrap interface by default.
14. Unit tested with PHPUnit and Behat.

You will need the complimentary bundles for this bundle to work, which are listed in the dependencies of the installation documentation.

Before installation of this bundle, you can download the [Sandbox](https://github.com/codeconsortium/CCDNSandBox) for testing/development and feature review, or alternatively see the product in use at [CodeConsortium](http://www.codeconsortium.com).

## Documentation.

Documentation can be found in the `Resources/doc/index.md` file in this bundle:

[Read the Documentation](http://github.com/codeconsortium/CCDNForumForumBundle/blob/master/Resources/doc/index.md).

## Installation.

All the installation instructions are located in [documentation](http://github.com/codeconsortium/CCDNForumForumBundle/blob/master/Resources/doc/install.md).

## Upgrading.

**Warning:**

> Do NOT use the CLI doctrine/symfony console utility to force an update, data will be lost if you do!
> Manually run the sql update in the upgrading docs. Always backup your database before updating.

To upgrade to version 2.0 please read the [upgrading to version 1.1.2 guide](http://github.com/codeconsortium/CCDNForumForumBundle/blob/2.0.x/Resources/doc/upgrading_to_2_0.md).

## License.

This software is licensed under the MIT license. See the complete license file in the bundle:

	Resources/meta/LICENSE

[Read the License](http://github.com/codeconsortium/CCDNForumForumBundle/blob/master/Resources/meta/LICENSE).

## About.

[CCDNForum ForumBundle](http://github.com/codeconsortium/CCDNForumForumBundle) is free software as part of the CCDNForum from [Code Consortium](http://www.codeconsortium.com). 
See also the list of [contributors](http://github.com/codeconsortium/CCDNForumForumBundle/contributors).

## Reporting an issue or feature request.

Issues and feature requests are tracked in the [Github issue tracker](http://github.com/codeconsortium/CCDNForumForumBundle/issues).

Discussions and debates on the project can be further discussed at [Code Consortium](http://www.codeconsortium.com).
