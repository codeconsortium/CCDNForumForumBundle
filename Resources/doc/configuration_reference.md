CCDNForum ForumBundle Configuration Reference.
==============================================

All available configuration options are listed below with their default values.

``` yml
#
# for CCDNForum ForumBundle    
#
ccdn_forum_forum:
    template:
        engine:               twig
        pager_theme:          CCDNForumForumBundle:Common:Paginator/twitter_bootstrap.html.twig
    entity:
        forum:
            class:                CCDNForum\ForumBundle\Entity\Forum
        category:
            class:                CCDNForum\ForumBundle\Entity\Category
        board:
            class:                CCDNForum\ForumBundle\Entity\Board
        topic:
            class:                CCDNForum\ForumBundle\Entity\Topic
        post:
            class:                CCDNForum\ForumBundle\Entity\Post
        subscription:
            class:                CCDNForum\ForumBundle\Entity\Subscription
        registry:
            class:                CCDNForum\ForumBundle\Entity\Registry
    gateway:
        forum:
            class:                CCDNForum\ForumBundle\Model\Component\Gateway\ForumGateway
        category:
            class:                CCDNForum\ForumBundle\Model\Component\Gateway\CategoryGateway
        board:
            class:                CCDNForum\ForumBundle\Model\Component\Gateway\BoardGateway
        topic:
            class:                CCDNForum\ForumBundle\Model\Component\Gateway\TopicGateway
        post:
            class:                CCDNForum\ForumBundle\Model\Component\Gateway\PostGateway
        subscription:
            class:                CCDNForum\ForumBundle\Model\Component\Gateway\SubscriptionGateway
        registry:
            class:                CCDNForum\ForumBundle\Model\Component\Gateway\RegistryGateway
    repository:
        forum:
            class:                CCDNForum\ForumBundle\Model\Component\Repository\ForumRepository
        category:
            class:                CCDNForum\ForumBundle\Model\Component\Repository\CategoryRepository
        board:
            class:                CCDNForum\ForumBundle\Model\Component\Repository\BoardRepository
        topic:
            class:                CCDNForum\ForumBundle\Model\Component\Repository\TopicRepository
        post:
            class:                CCDNForum\ForumBundle\Model\Component\Repository\PostRepository
        subscription:
            class:                CCDNForum\ForumBundle\Model\Component\Repository\SubscriptionRepository
        registry:
            class:                CCDNForum\ForumBundle\Model\Component\Repository\RegistryRepository
    manager:
        forum:
            class:                CCDNForum\ForumBundle\Model\Component\Manager\ForumManager
        category:
            class:                CCDNForum\ForumBundle\Model\Component\Manager\CategoryManager
        board:
            class:                CCDNForum\ForumBundle\Model\Component\Manager\BoardManager
        topic:
            class:                CCDNForum\ForumBundle\Model\Component\Manager\TopicManager
        post:
            class:                CCDNForum\ForumBundle\Model\Component\Manager\PostManager
        subscription:
            class:                CCDNForum\ForumBundle\Model\Component\Manager\SubscriptionManager
        registry:
            class:                CCDNForum\ForumBundle\Model\Component\Manager\RegistryManager
    model:
        forum:
            class:                CCDNForum\ForumBundle\Model\FrontModel\ForumModel
        category:
            class:                CCDNForum\ForumBundle\Model\FrontModel\CategoryModel
        board:
            class:                CCDNForum\ForumBundle\Model\FrontModel\BoardModel
        topic:
            class:                CCDNForum\ForumBundle\Model\FrontModel\TopicModel
        post:
            class:                CCDNForum\ForumBundle\Model\FrontModel\PostModel
        subscription:
            class:                CCDNForum\ForumBundle\Model\FrontModel\SubscriptionModel
        registry:
            class:                CCDNForum\ForumBundle\Model\FrontModel\RegistryModel
    form:
        handler:
            forum_create:
                class:                CCDNForum\ForumBundle\Form\Handler\Admin\Forum\ForumCreateFormHandler
            forum_update:
                class:                CCDNForum\ForumBundle\Form\Handler\Admin\Forum\ForumUpdateFormHandler
            forum_delete:
                class:                CCDNForum\ForumBundle\Form\Handler\Admin\Forum\ForumDeleteFormHandler
            category_create:
                class:                CCDNForum\ForumBundle\Form\Handler\Admin\Category\CategoryCreateFormHandler
            category_update:
                class:                CCDNForum\ForumBundle\Form\Handler\Admin\Category\CategoryUpdateFormHandler
            category_delete:
                class:                CCDNForum\ForumBundle\Form\Handler\Admin\Category\CategoryDeleteFormHandler
            board_create:
                class:                CCDNForum\ForumBundle\Form\Handler\Admin\Board\BoardCreateFormHandler
            board_update:
                class:                CCDNForum\ForumBundle\Form\Handler\Admin\Board\BoardUpdateFormHandler
            board_delete:
                class:                CCDNForum\ForumBundle\Form\Handler\Admin\Board\BoardDeleteFormHandler
            topic_create:
                class:                CCDNForum\ForumBundle\Form\Handler\User\Topic\TopicCreateFormHandler
            topic_update:
                class:                CCDNForum\ForumBundle\Form\Handler\User\Topic\TopicUpdateFormHandler
            topic_delete:
                class:                CCDNForum\ForumBundle\Form\Handler\Moderator\Topic\TopicDeleteFormHandler
            change_topics_board:
                class:                CCDNForum\ForumBundle\Form\Handler\Moderator\Topic\TopicChangeBoardFormHandler
            post_create:
                class:                CCDNForum\ForumBundle\Form\Handler\User\Post\PostCreateFormHandler
            post_update:
                class:                CCDNForum\ForumBundle\Form\Handler\User\Post\PostUpdateFormHandler
            post_delete:
                class:                CCDNForum\ForumBundle\Form\Handler\User\Post\PostDeleteFormHandler
            post_unlock:
                class:                CCDNForum\ForumBundle\Form\Handler\Moderator\Post\PostUnlockFormHandler
        type:
            forum_create:
                class:                CCDNForum\ForumBundle\Form\Type\Admin\Forum\ForumCreateFormType
            forum_update:
                class:                CCDNForum\ForumBundle\Form\Type\Admin\Forum\ForumUpdateFormType
            forum_delete:
                class:                CCDNForum\ForumBundle\Form\Type\Admin\Forum\ForumDeleteFormType
            category_create:
                class:                CCDNForum\ForumBundle\Form\Type\Admin\Category\CategoryCreateFormType
            category_update:
                class:                CCDNForum\ForumBundle\Form\Type\Admin\Category\CategoryUpdateFormType
            category_delete:
                class:                CCDNForum\ForumBundle\Form\Type\Admin\Category\CategoryDeleteFormType
            board_create:
                class:                CCDNForum\ForumBundle\Form\Type\Admin\Board\BoardCreateFormType
            board_update:
                class:                CCDNForum\ForumBundle\Form\Type\Admin\Board\BoardUpdateFormType
            board_delete:
                class:                CCDNForum\ForumBundle\Form\Type\Admin\Board\BoardDeleteFormType
            topic_create:
                class:                CCDNForum\ForumBundle\Form\Type\User\Topic\TopicCreateFormType
            topic_update:
                class:                CCDNForum\ForumBundle\Form\Type\User\Topic\TopicUpdateFormType
            topic_delete:
                class:                CCDNForum\ForumBundle\Form\Type\Moderator\Topic\TopicDeleteFormType
            change_topics_board:
                class:                CCDNForum\ForumBundle\Form\Type\Moderator\Topic\TopicChangeBoardFormType
            post_create:
                class:                CCDNForum\ForumBundle\Form\Type\User\Post\PostCreateFormType
            post_update:
                class:                CCDNForum\ForumBundle\Form\Type\User\Post\PostUpdateFormType
            post_delete:
                class:                CCDNForum\ForumBundle\Form\Type\User\Post\PostDeleteFormType
            post_unlock:
                class:                CCDNForum\ForumBundle\Form\Type\Moderator\Post\PostUnlockFormType
    component:
        dashboard:
            integrator:
                class:                CCDNForum\ForumBundle\Component\Dashboard\DashboardIntegrator
        crumb_factory:
            class:                CCDNForum\ForumBundle\Component\Crumbs\Factory\CrumbFactory
        crumb_builder:
            class:                CCDNForum\ForumBundle\Component\Crumbs\CrumbBuilder
        security:
            authorizer:
                class:                CCDNForum\ForumBundle\Component\Security\Authorizer
        helper:
            pagination_config:
                class:                CCDNForum\ForumBundle\Component\Helper\PaginationConfigHelper
            post_lock:
                class:                CCDNForum\ForumBundle\Component\Helper\PostLockHelper
            role:
                class:                CCDNForum\ForumBundle\Component\Helper\RoleHelper
        flood_control:
            class:                CCDNForum\ForumBundle\Component\FloodControl
        twig_extension:
            board_list:
                class:                CCDNForum\ForumBundle\Component\TwigExtension\BoardListExtension
            authorizer:
                class:                CCDNForum\ForumBundle\Component\TwigExtension\AuthorizerExtension
        event_listener:
            flash:
                class:                CCDNForum\ForumBundle\Component\Dispatcher\Listener\FlashListener
            subscriber:
                class:                CCDNForum\ForumBundle\Component\Dispatcher\Listener\SubscriberListener
            stats:
                class:                CCDNForum\ForumBundle\Component\Dispatcher\Listener\StatListener
    forum:
        admin:
            create:
                layout_template:      CCDNForumForumBundle::base.html.twig
                form_theme:           CCDNForumForumBundle:Common:Form/fields.html.twig
            delete:
                layout_template:      CCDNForumForumBundle::base.html.twig
                form_theme:           CCDNForumForumBundle:Common:Form/fields.html.twig
            edit:
                layout_template:      CCDNForumForumBundle::base.html.twig
                form_theme:           CCDNForumForumBundle:Common:Form/fields.html.twig
            list:
                layout_template:      CCDNForumForumBundle::base.html.twig
    category:
        admin:
            create:
                layout_template:      CCDNForumForumBundle::base.html.twig
                form_theme:           CCDNForumForumBundle:Common:Form/fields.html.twig
            delete:
                layout_template:      CCDNForumForumBundle::base.html.twig
                form_theme:           CCDNForumForumBundle:Common:Form/fields.html.twig
            edit:
                layout_template:      CCDNForumForumBundle::base.html.twig
                form_theme:           CCDNForumForumBundle:Common:Form/fields.html.twig
            list:
                layout_template:      CCDNForumForumBundle::base.html.twig
        user:
            last_post_datetime_format:  d-m-Y - H:i
            index:
                layout_template:      CCDNForumForumBundle::base.html.twig
            show:
                layout_template:      CCDNForumForumBundle::base.html.twig
    board:
        admin:
            create:
                layout_template:      CCDNForumForumBundle::base.html.twig
                form_theme:           CCDNForumForumBundle:Common:Form/fields.html.twig
            delete:
                layout_template:      CCDNForumForumBundle::base.html.twig
                form_theme:           CCDNForumForumBundle:Common:Form/fields.html.twig
            edit:
                layout_template:      CCDNForumForumBundle::base.html.twig
                form_theme:           CCDNForumForumBundle:Common:Form/fields.html.twig
            list:
                layout_template:      CCDNForumForumBundle::base.html.twig
        user:
            show:
                layout_template:      CCDNForumForumBundle::base.html.twig
                topics_per_page:      50
                topic_title_truncate:  50
                first_post_datetime_format:  d-m-Y - H:i
                last_post_datetime_format:  d-m-Y - H:i
    topic:
        moderator:
            change_board:
                layout_template:      CCDNForumForumBundle::base.html.twig
                form_theme:           CCDNForumForumBundle:Common:Form/fields.html.twig
            delete:
                layout_template:      CCDNForumForumBundle::base.html.twig
                form_theme:           CCDNForumForumBundle:Common:Form/fields.html.twig
        user:
            flood_control:
                post_limit:           4
                block_for_minutes:    1
            show:
                layout_template:      CCDNForumForumBundle::base.html.twig
                posts_per_page:       20
                closed_datetime_format:  d-m-Y - H:i
                deleted_datetime_format:  d-m-Y - H:i
            create:
                layout_template:      CCDNForumForumBundle::base.html.twig
                form_theme:           CCDNForumForumBundle:Common:Form/fields.html.twig
            reply:
                layout_template:      CCDNForumForumBundle::base.html.twig
                form_theme:           CCDNForumForumBundle:Common:Form/fields.html.twig
    post:
        moderator:
            unlock:
                layout_template:      CCDNForumForumBundle::base.html.twig
                form_theme:           CCDNForumForumBundle:Common:Form/fields.html.twig
        user:
            show:
                layout_template:      CCDNForumForumBundle::base.html.twig
            edit:
                layout_template:      CCDNForumForumBundle::base.html.twig
                form_theme:           CCDNForumForumBundle:Common:Form/fields.html.twig
            delete:
                layout_template:      CCDNForumForumBundle::base.html.twig
                form_theme:           CCDNForumForumBundle:Common:Form/fields.html.twig
            lock:
                enable:               true
                after_days:           7
    item_post:
        created_datetime_format:  d-m-Y - H:i
        edited_datetime_format:  d-m-Y - H:i
        locked_datetime_format:  d-m-Y - H:i
        deleted_datetime_format:  d-m-Y - H:i
    subscription:
        list:
            layout_template:      CCDNForumForumBundle::base.html.twig
            topics_per_page:      50
            topic_title_truncate:  50
            first_post_datetime_format:  d-m-Y - H:i
            last_post_datetime_format:  d-m-Y - H:i
    fixtures:
        user_admin:           user-admin
    seo:
        title_length:         67
```

- [Return back to the docs index](index.md).
