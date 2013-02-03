CCDNForum ForumBundle README.
=============================


## Notes: 

This bundle is for the symfony framework and requires Symfony 2.1.x and PHP 5.3.6
  
This project uses Doctrine 2.0.x and so does not require any specific database.
  

This file is part of the CCDNForum Bundle(s)

(c) CCDN (c) CodeConsortium <http://www.codeconsortium.com/> 

Available on github <http://www.github.com/codeconsortium/>

Theme and Sprite graphics courtesy of [twitter bootstrap](http://twitter.github.com/bootstrap/index.html) and [GLYPHICONS](http://glyphicons.com/).

Other graphics are works of CodeConsortium.

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.

## Description:

This is a ForumBundle for Symfony (2.0.11) for building a bulletin board forum community where users can create and reply to discussions.

## Features.

ForumBundle Provides the following features:

1. Create/Reply to Topics.
2. Edit Topics/Posts.
3. Make Topics sticky.
4. Smileys and BBCode support through [CommonBundle](http://github.com/codeconsortium/CommonBundle) and [BBCodeBundle](http://github.com/codeconsortium/BBCodeBundle).
5. Board and Topics are paginated.
6. Topics and Posts can be soft-deleted for recovery by Admin or hard-delete.
7. Topics can be closed and Posts locked from editing.
8. Topics and Posts can be Previewed before posting.
9. Topics and Posts can be saved as a Draft which can be later published.
10. Topics can be subscribed to and followed in the Topic subscription page.
11. Optional integration with [DashboardBundle](http://github.com/codeconsortium/DashboardBundle) for easy site navigation.
12. [AdminBundle](http://github.com/codeconsortium/CCDNForumAdminBundle) to allow full forum moderation and administration.
13. Complimentary [KarmaBundle](http://github.com/codeconsortium/CCDNForumKarmaBundle) provides Post ratings giving a user an overall Karma rating.
14. Complimentary [AttachmentBundle](http://github.com/codeconsortium/AttachmentBundle) allows file attachments to Posts.
15. Complimentary [CrumbTrailBundle](http://github.com/codeconsortium/CrumbTrailBundle) allows easy backtracking and logical hierarchical navigation.
16. Utilises Twitter-Bootstrap interface by default.

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

To upgrade to version 1.1.2 please read the [upgrading to version 1.1.2 guide](http://github.com/codeconsortium/CCDNForumForumBundle/blob/v1.1.2/Resources/doc/upgrading_to_1_1_2.md).

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
