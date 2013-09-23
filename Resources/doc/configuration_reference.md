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
            class:                CCDNForum\ForumBundle\Model\Gateway\ForumGateway
        category:
            class:                CCDNForum\ForumBundle\Model\Gateway\CategoryGateway
        board:
            class:                CCDNForum\ForumBundle\Model\Gateway\BoardGateway
        topic:
            class:                CCDNForum\ForumBundle\Model\Gateway\TopicGateway
        post:
            class:                CCDNForum\ForumBundle\Model\Gateway\PostGateway
        subscription:
            class:                CCDNForum\ForumBundle\Model\Gateway\SubscriptionGateway
        registry:
            class:                CCDNForum\ForumBundle\Model\Gateway\RegistryGateway
    repository:
        forum:
            class:                CCDNForum\ForumBundle\Model\Repository\ForumRepository
        category:
            class:                CCDNForum\ForumBundle\Model\Repository\CategoryRepository
        board:
            class:                CCDNForum\ForumBundle\Model\Repository\BoardRepository
        topic:
            class:                CCDNForum\ForumBundle\Model\Repository\TopicRepository
        post:
            class:                CCDNForum\ForumBundle\Model\Repository\PostRepository
        subscription:
            class:                CCDNForum\ForumBundle\Model\Repository\SubscriptionRepository
        registry:
            class:                CCDNForum\ForumBundle\Model\Repository\RegistryRepository
    manager:
        forum:
            class:                CCDNForum\ForumBundle\Model\Manager\ForumManager
        category:
            class:                CCDNForum\ForumBundle\Model\Manager\CategoryManager
        board:
            class:                CCDNForum\ForumBundle\Model\Manager\BoardManager
        topic:
            class:                CCDNForum\ForumBundle\Model\Manager\TopicManager
        post:
            class:                CCDNForum\ForumBundle\Model\Manager\PostManager
        subscription:
            class:                CCDNForum\ForumBundle\Model\Manager\SubscriptionManager
        registry:
            class:                CCDNForum\ForumBundle\Model\Manager\RegistryManager
    model:
        bag:
            class:                CCDNForum\ForumBundle\Model\Model\Bag\ModelBag
        utility_bag:
            class:                CCDNForum\ForumBundle\Model\Utility\Bag\UtilityBag
        utility:
            pagination_config:
                class:                CCDNForum\ForumBundle\Model\Utility\PaginationConfig
        forum:
            class:                CCDNForum\ForumBundle\Model\Model\ForumModel
        category:
            class:                CCDNForum\ForumBundle\Model\Model\CategoryModel
        board:
            class:                CCDNForum\ForumBundle\Model\Model\BoardModel
        topic:
            class:                CCDNForum\ForumBundle\Model\Model\TopicModel
        post:
            class:                CCDNForum\ForumBundle\Model\Model\PostModel
        subscription:
            class:                CCDNForum\ForumBundle\Model\Model\SubscriptionModel
        registry:
            class:                CCDNForum\ForumBundle\Model\Model\RegistryModel
    form:
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
        role_helper:
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
    fixtures:
        user_admin:           user-admin
    seo:
        title_length:         67
    category:
        last_post_datetime_format:  d-m-Y - H:i
        index:
            layout_template:      CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
        show:
            layout_template:      CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
    board:
        show:
            layout_template:      CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            topics_per_page:      50
            topic_title_truncate:  50
            first_post_datetime_format:  d-m-Y - H:i
            last_post_datetime_format:  d-m-Y - H:i
    topic:
        flood_control:
            post_limit:           4
            block_for_minutes:    1
        show:
            layout_template:      CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            posts_per_page:       20
            closed_datetime_format:  d-m-Y - H:i
            deleted_datetime_format:  d-m-Y - H:i
        create:
            layout_template:      CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            form_theme:           CCDNForumForumBundle:Form:fields.html.twig
        reply:
            layout_template:      CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            form_theme:           CCDNForumForumBundle:Form:fields.html.twig
        change_board:
            layout_template:      CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            form_theme:           CCDNForumForumBundle:Form:fields.html.twig
    post:
        show:
            layout_template:      CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
        edit_topic:
            layout_template:      CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            form_theme:           CCDNForumForumBundle:Form:fields.html.twig
        edit_post:
            layout_template:      CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            form_theme:           CCDNForumForumBundle:Form:fields.html.twig
        delete_post:
            layout_template:      CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
    item_post:
        created_datetime_format:  d-m-Y - H:i
        edited_datetime_format:  d-m-Y - H:i
        locked_datetime_format:  d-m-Y - H:i
        deleted_datetime_format:  d-m-Y - H:i
    subscription:
        list:
            layout_template:      CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            topics_per_page:      50
            topic_title_truncate:  50
            first_post_datetime_format:  d-m-Y - H:i
            last_post_datetime_format:  d-m-Y - H:i
```

- [Return back to the docs index](index.md).
