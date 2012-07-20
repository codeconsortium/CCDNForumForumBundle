CCDNForum ForumBundle Configuration Reference.
==============================================

All available configuration options are listed below with their default values.

``` yml
#
# for CCDNForum ForumBundle    
#
ccdn_forum_forum:
    user:
        profile_route: cc_profile_show_by_id
    template:
        engine: twig
	seo:
		title_length: 67
    category:
        last_post_datetime_format: "d-m-Y - H:i"
		enable_bb_parser: true
        index:
            layout_template: CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
        show:
            layout_template: CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
    board:
        show:
            layout_template: CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            topics_per_page: 4
            topic_title_truncate: 50
            first_post_datetime_format: "d-m-Y - H:i"
            last_post_datetime_format: "d-m-Y - H:i"
    topic:
        show:
            layout_template: CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            posts_per_page: 5        
            topic_closed_datetime_format: "d-m-Y - H:i"
            topic_deleted_datetime_format: "d-m-Y - H:i"
        create:
            layout_template: CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            form_theme: CCDNForumForumBundle:Form:fields.html.twig
			enable_bb_editor: true
        reply:
            layout_template: CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            form_theme: CCDNForumForumBundle:Form:fields.html.twig
			enable_bb_editor: true
    post:
        show:
            layout_template: CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            topic_closed_datetime_format: "d-m-Y - H:i"
            topic_deleted_datetime_format: "d-m-Y - H:i"
        flag:
            layout_template: CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            form_theme: CCDNForumForumBundle:Form:fields.html.twig
			enable_bb_editor: true
        edit_topic:
            layout_template: CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            form_theme: CCDNForumForumBundle:Form:fields.html.twig
			enable_bb_parser: true
        edit_post:
            layout_template: CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            form_theme: CCDNForumForumBundle:Form:fields.html.twig
			enable_bb_editor: true
        delete_post:
            layout_template: CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
    item_post:
        post_created_datetime_format: "d-m-Y - H:i"
        post_edited_datetime_format: "d-m-Y - H:i"
        post_locked_datetime_format: "d-m-Y - H:i"
        post_deleted_datetime_format: "d-m-Y - H:i"
		enable_bb_parser: true
	item_signature:
		enable_bb_parser: true
    draft:
        list:
            layout_template: CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            drafts_per_page: 10
            topic_title_truncate: 80
            creation_datetime_format: "d-m-Y - H:i"
    subscription:
        list:
            layout_template: CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            topics_per_page: 40
            topic_title_truncate: 50
            first_post_datetime_format: "d-m-Y - H:i"
            last_post_datetime_format: "d-m-Y - H:i"
    transcript:
        post_creation_datetime_format: "d-m-Y - H:i"
        post_deleted_datetime_format: "d-m-Y - H:i"

```

- [Return back to the docs index](index.md).
