Feature: User Subscription Traversal And Management
	In order to view subscriptions and list manage them.
	As an User
	I want to be able to view my subscriptions and their topics, subscribe and unsubscribe from them.

    Background:
        Given I am logged in as "user"
        And there are following users defined:
          | email          | password | enabled  | role                |
          | user@foo.com   | root     | 1        | ROLE_USER           |
        And there are following forums defined:
          | name                      | order    |
		  | test_forum_f1             | 1        |
		  | test_forum_f2             | 2        |
		  | test_forum_f3             | 3        |
        And there are following categories defined:
          | name                      | order    | forum               |
          | test_category_f1_c1       | 1        | test_forum_f1       |
		  | test_category_f1_c2       | 2        | test_forum_f1       |
		  | test_category_f1_c3       | 3        | test_forum_f1       |
        And there are following boards defined:
          | name                      | description          | order   | category              |
          | test_board_f1_c1_b1       | testing board 1      | 1       | test_category_f1_c1   |
          | test_board_f1_c1_b2       | testing board 2      | 2       | test_category_f1_c1   |
          | test_board_f1_c1_b3       | testing board 3      | 3       | test_category_f1_c1   |
          | test_board_f1_c2_b1       | testing board 1      | 1       | test_category_f1_c2   |
          | test_board_f1_c2_b2       | testing board 2      | 2       | test_category_f1_c2   |
          | test_board_f1_c2_b3       | testing board 3      | 3       | test_category_f1_c2   |
        And there are following topics defined:
          | title                     | body                           | board                 | user          | subscribed |
          | test_topic_f1_c1_b1_t1    | test_post_f1_c1_b1_t1_p1       | test_board_f1_c1_b1   | user@foo.com  | true       |
		  | test_topic_f1_c1_b1_t2    | test_post_f1_c1_b1_t2_p1       | test_board_f1_c1_b1   | user@foo.com  | true       |
          | test_topic_f1_c1_b1_t3    | test_post_f1_c1_b1_t3_p1       | test_board_f1_c1_b1   | user@foo.com  | true       |
		  | test_topic_f1_c1_b2_t4    | test_post_f1_c1_b2_t4_p1       | test_board_f1_c1_b2   | user@foo.com  | true       |
          | test_topic_f1_c1_b2_t5    | test_post_f1_c1_b2_t5_p1       | test_board_f1_c1_b2   | user@foo.com  | true       |
		  | test_topic_f1_c1_b2_t6    | test_post_f1_c1_b2_t6_p1       | test_board_f1_c1_b2   | user@foo.com  | true       |

	Scenario: See topic subscription list filtered by forum.
        Given I am on "/en/forum/test_forum_f1/subscription"
          And I should see "test_topic_f1_c1_b1_t1"
		  And I should see "test_topic_f1_c1_b1_t2"
		  And I should see "test_topic_f1_c1_b1_t3"
		  And I should see "test_topic_f1_c1_b2_t4"
          And I should see "test_topic_f1_c1_b2_t5"
		  And I should see "test_topic_f1_c1_b2_t6"
        Given I am on "/en/forum/test_forum_f1/subscription/?filter=unread"
          And I should see "test_topic_f1_c1_b1_t1"
		  And I should see "test_topic_f1_c1_b1_t2"
		  And I should see "test_topic_f1_c1_b1_t3"
		  And I should see "test_topic_f1_c1_b2_t4"
          And I should see "test_topic_f1_c1_b2_t5"
		  And I should see "test_topic_f1_c1_b2_t6"
        Given I am on "/en/forum/test_forum_f1/subscription/?filter=read"
          And I should not see "test_topic_f1_c1_b1_t1"
		  And I should not see "test_topic_f1_c1_b1_t2"
		  And I should not see "test_topic_f1_c1_b1_t3"
		  And I should not see "test_topic_f1_c1_b2_t4"
          And I should not see "test_topic_f1_c1_b2_t5"
		  And I should not see "test_topic_f1_c1_b2_t6"

	Scenario: Create new topic and Subscribe
        Given I am on "/en/forum/test_forum_f1"
		  And I follow "test_category_f1_c1"
		  And I follow "test_board_f1_c1_b1"
		  And I follow "Create New Topic"
		  And I should see "Create Topic"
          And I fill in "Post[Topic][title]" with "New Test Topic with Subscription"
          And I fill in "Post[body]" with "Ipsum lorem doler sit amet"
		  And I check "Post[subscribe]"
          And I press "submit[post]"
		  And I should not see "Create Topic"
		  And I should see "New Test Topic with Subscription"
		  And I should see "Ipsum lorem doler sit amet"
        Given I am on "/en/forum/test_forum_f1/subscription"
		  And I should see "New Test Topic with Subscription"

	Scenario: Reply to topic and Subscribe
        Given I am on "/en/forum/test_forum_f1"
		  And I follow "test_category_f1_c1"
		  And I follow "test_board_f1_c1_b1"
		  And I follow "Create New Topic"
		  And I should see "Create Topic"
          And I fill in "Post[Topic][title]" with "New Test Topic with Subscription"
          And I fill in "Post[body]" with "Ipsum lorem doler sit amet"
          And I press "submit[post]"
		  And I should not see "Create Topic"
		  And I should see "New Test Topic"
		  And I should see "Ipsum lorem doler sit amet"
          And I follow "Reply"
          And I fill in "Post[body]" with "my response"
		  And I check "Post[subscribe]"
          And I press "submit[post]"
		  And I should see "New Test Topic with Subscription"
		  And I should see "Ipsum lorem doler sit amet"
		  And I should see "my response"
        Given I am on "/en/forum/test_forum_f1/subscription"
		  And I should see "New Test Topic with Subscription"
