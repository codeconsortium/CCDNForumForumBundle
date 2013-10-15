Feature: User Category Traversal
	In order to list and view categories
	As an User
	I want to be able to list and view categories.

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
          | test_category_fn_c1       | 1        |                     |
		  | test_category_fn_c2       | 2        |                     |
		  | test_category_fn_c3       | 3        |                     |
          | test_category_f1_c1       | 1        | test_forum_f1       |
		  | test_category_f1_c2       | 2        | test_forum_f1       |
		  | test_category_f1_c3       | 3        | test_forum_f1       |
          | test_category_f2_c1       | 1        | test_forum_f2       |
		  | test_category_f2_c2       | 2        | test_forum_f2       |
		  | test_category_f2_c3       | 3        | test_forum_f2       |
          | test_category_f3_c1       | 1        | test_forum_f3       |
		  | test_category_f3_c2       | 2        | test_forum_f3       |
		  | test_category_f3_c3       | 3        | test_forum_f3       |
        And there are following boards defined:
          | name                      | description          | order   | category              |
          | test_board_fn_c1_b1       | testing board 1      | 1       | test_category_fn_c1   |
          | test_board_fn_c1_b2       | testing board 2      | 2       | test_category_fn_c1   |
          | test_board_fn_c1_b3       | testing board 3      | 3       | test_category_fn_c1   |
          | test_board_fn_c2_b1       | testing board 1      | 1       | test_category_fn_c2   |
          | test_board_fn_c2_b2       | testing board 2      | 2       | test_category_fn_c2   |
          | test_board_fn_c2_b3       | testing board 3      | 3       | test_category_fn_c2   |
          | test_board_fn_c3_b1       | testing board 1      | 1       | test_category_fn_c3   |
          | test_board_fn_c3_b2       | testing board 2      | 2       | test_category_fn_c3   |
          | test_board_fn_c3_b3       | testing board 3      | 3       | test_category_fn_c3   |
          | test_board_f1_c1_b1       | testing board 1      | 1       | test_category_f1_c1   |
          | test_board_f1_c1_b2       | testing board 2      | 2       | test_category_f1_c1   |
          | test_board_f1_c1_b3       | testing board 3      | 3       | test_category_f1_c1   |
          | test_board_f1_c2_b1       | testing board 1      | 1       | test_category_f1_c2   |
          | test_board_f1_c2_b2       | testing board 2      | 2       | test_category_f1_c2   |
          | test_board_f1_c2_b3       | testing board 3      | 3       | test_category_f1_c2   |
          | test_board_f1_c3_b1       | testing board 1      | 1       | test_category_f1_c3   |
          | test_board_f1_c3_b2       | testing board 2      | 2       | test_category_f1_c3   |
          | test_board_f1_c3_b3       | testing board 3      | 3       | test_category_f1_c3   |
          | test_board_f2_c1_b1       | testing board 1      | 1       | test_category_f2_c1   |
          | test_board_f2_c1_b2       | testing board 2      | 2       | test_category_f2_c1   |
          | test_board_f2_c1_b3       | testing board 3      | 3       | test_category_f2_c1   |
          | test_board_f2_c2_b1       | testing board 1      | 1       | test_category_f2_c2   |
          | test_board_f2_c2_b2       | testing board 2      | 2       | test_category_f2_c2   |
          | test_board_f2_c2_b3       | testing board 3      | 3       | test_category_f2_c2   |
          | test_board_f2_c3_b1       | testing board 1      | 1       | test_category_f2_c3   |
          | test_board_f2_c3_b2       | testing board 2      | 2       | test_category_f2_c3   |
          | test_board_f2_c3_b3       | testing board 3      | 3       | test_category_f2_c3   |
          | test_board_f3_c1_b1       | testing board 1      | 1       | test_category_f3_c1   |
          | test_board_f3_c1_b2       | testing board 2      | 2       | test_category_f3_c1   |
          | test_board_f3_c1_b3       | testing board 3      | 3       | test_category_f3_c1   |
          | test_board_f3_c2_b1       | testing board 1      | 1       | test_category_f3_c1   |
          | test_board_f3_c2_b2       | testing board 2      | 2       | test_category_f3_c1   |
          | test_board_f3_c2_b3       | testing board 3      | 3       | test_category_f3_c1   |
          | test_board_f3_c3_b1       | testing board 1      | 1       | test_category_f3_c1   |
          | test_board_f3_c3_b2       | testing board 2      | 2       | test_category_f3_c1   |
          | test_board_f3_c3_b3       | testing board 3      | 3       | test_category_f3_c1   |

	Scenario: See Category list filtered by forum
        Given I am on "/en/forum/test_forum_f1" 
          And I should not see category named "test_category_fn_c1" on category list
		  And I should not see category named "test_category_fn_c2" on category list
          And I should see category named "test_category_f1_c1" on category list
		  And I should see category named "test_category_f1_c2" on category list
          And I should not see category named "test_category_f2_c1" on category list
		  And I should not see category named "test_category_f2_c2" on category list
          And I should not see category named "test_category_f3_c1" on category list
		  And I should not see category named "test_category_f3_c2" on category list
        Given I am on "/en/forum/test_forum_f2" 
          And I should not see category named "test_category_fn_c1" on category list
		  And I should not see category named "test_category_fn_c2" on category list
          And I should not see category named "test_category_f1_c1" on category list
		  And I should not see category named "test_category_f1_c2" on category list
          And I should see category named "test_category_f2_c1" on category list
		  And I should see category named "test_category_f2_c2" on category list
          And I should not see category named "test_category_f3_c1" on category list
		  And I should not see category named "test_category_f3_c2" on category list
        Given I am on "/en/forum/test_forum_f3" 
          And I should not see category named "test_category_fn_c1" on category list
		  And I should not see category named "test_category_fn_c2" on category list
          And I should not see category named "test_category_f1_c1" on category list
		  And I should not see category named "test_category_f1_c2" on category list
          And I should not see category named "test_category_f2_c1" on category list
		  And I should not see category named "test_category_f2_c2" on category list
          And I should see category named "test_category_f3_c1" on category list
		  And I should see category named "test_category_f3_c2" on category list

	Scenario: See Category list filtered by forum and category show
        Given I am on "/en/forum/test_forum_f1"
		  And I follow "test_category_f1_c1"
		  And I should see category named "test_category_f1_c1" on category list
          And I should see board named "test_board_f1_c1_b1" on category list
          And I should see board named "test_board_f1_c1_b2" on category list
          And I should not see board named "test_board_f1_c2_b1" on category list
          And I should not see board named "test_board_f1_c2_b2" on category list
          And I should not see board named "test_board_f1_c3_b1" on category list
          And I should not see board named "test_board_f1_c3_b2" on category list
          And I should not see board named "test_board_f2_c1_b1" on category list
          And I should not see board named "test_board_f2_c1_b2" on category list
          And I should not see board named "test_board_f2_c2_b1" on category list
          And I should not see board named "test_board_f2_c2_b2" on category list
          And I should not see board named "test_board_f2_c3_b1" on category list
          And I should not see board named "test_board_f2_c3_b2" on category list
        Given I am on "/en/forum/test_forum_f1" 
		  And I follow "test_category_f1_c2"
		  And I should see category named "test_category_f1_c2" on category list
          And I should not see board named "test_board_f1_c1_b1" on category list
          And I should not see board named "test_board_f1_c1_b2" on category list
          And I should see board named "test_board_f1_c2_b1" on category list
          And I should see board named "test_board_f1_c2_b2" on category list
          And I should not see board named "test_board_f1_c3_b1" on category list
          And I should not see board named "test_board_f1_c3_b2" on category list
          And I should not see board named "test_board_f2_c1_b1" on category list
          And I should not see board named "test_board_f2_c1_b2" on category list
          And I should not see board named "test_board_f2_c2_b1" on category list
          And I should not see board named "test_board_f2_c2_b2" on category list
          And I should not see board named "test_board_f2_c3_b1" on category list
          And I should not see board named "test_board_f2_c3_b2" on category list
        Given I am on "/en/forum/test_forum_f1" 
		  And I follow "test_category_f1_c3"
		  And I should see category named "test_category_f1_c3" on category list
          And I should not see board named "test_board_f1_c1_b1" on category list
          And I should not see board named "test_board_f1_c1_b2" on category list
          And I should not see board named "test_board_f1_c2_b1" on category list
          And I should not see board named "test_board_f1_c2_b2" on category list
          And I should see board named "test_board_f1_c3_b1" on category list
          And I should see board named "test_board_f1_c3_b2" on category list
          And I should not see board named "test_board_f2_c1_b1" on category list
          And I should not see board named "test_board_f2_c1_b2" on category list
          And I should not see board named "test_board_f2_c2_b1" on category list
          And I should not see board named "test_board_f2_c2_b2" on category list
          And I should not see board named "test_board_f2_c3_b1" on category list
          And I should not see board named "test_board_f2_c3_b2" on category list
        Given I am on "/en/forum/test_forum_f2" 
		  And I follow "test_category_f2_c1"
		  And I should see category named "test_category_f2_c1" on category list
          And I should not see board named "test_board_f1_c1_b1" on category list
          And I should not see board named "test_board_f1_c1_b2" on category list
          And I should not see board named "test_board_f1_c2_b1" on category list
          And I should not see board named "test_board_f1_c2_b2" on category list
          And I should not see board named "test_board_f1_c3_b1" on category list
          And I should not see board named "test_board_f1_c3_b2" on category list
          And I should see board named "test_board_f2_c1_b1" on category list
          And I should see board named "test_board_f2_c1_b2" on category list
          And I should not see board named "test_board_f2_c2_b1" on category list
          And I should not see board named "test_board_f2_c2_b2" on category list
          And I should not see board named "test_board_f2_c3_b1" on category list
          And I should not see board named "test_board_f2_c3_b2" on category list
        Given I am on "/en/forum/test_forum_f2" 
		  And I follow "test_category_f2_c2"
		  And I should see category named "test_category_f2_c2" on category list
          And I should not see board named "test_board_f1_c1_b1" on category list
          And I should not see board named "test_board_f1_c1_b2" on category list
          And I should not see board named "test_board_f1_c2_b1" on category list
          And I should not see board named "test_board_f1_c2_b2" on category list
          And I should not see board named "test_board_f1_c3_b1" on category list
          And I should not see board named "test_board_f1_c3_b2" on category list
          And I should not see board named "test_board_f2_c1_b1" on category list
          And I should not see board named "test_board_f2_c1_b2" on category list
          And I should see board named "test_board_f2_c2_b1" on category list
          And I should see board named "test_board_f2_c2_b2" on category list
          And I should not see board named "test_board_f2_c3_b1" on category list
          And I should not see board named "test_board_f2_c3_b2" on category list
        Given I am on "/en/forum/test_forum_f2" 
		  And I follow "test_category_f2_c3"
		  And I should see category named "test_category_f2_c3" on category list
          And I should not see board named "test_board_f1_c1_b1" on category list
          And I should not see board named "test_board_f1_c1_b2" on category list
          And I should not see board named "test_board_f1_c2_b1" on category list
          And I should not see board named "test_board_f1_c2_b2" on category list
          And I should not see board named "test_board_f1_c3_b1" on category list
          And I should not see board named "test_board_f1_c3_b2" on category list
          And I should not see board named "test_board_f2_c1_b1" on category list
          And I should not see board named "test_board_f2_c1_b2" on category list
          And I should not see board named "test_board_f2_c2_b1" on category list
          And I should not see board named "test_board_f2_c2_b2" on category list
          And I should see board named "test_board_f2_c3_b1" on category list
          And I should see board named "test_board_f2_c3_b2" on category list
