Feature: Admin Board Management
	In order to manage boards
	As an administrator
	I want to be able to list, create, edit and delete boards.

    Background:
        Given I am logged in as "admin"
        And there are following users defined:
          | email          | password | enabled  | role                |
          | admin@foo.com  | root     | 1        | ROLE_SUPER_ADMIN    |
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
          | test_board_fn_cn_b1       | testing board 1      | 1       |                       |
          | test_board_fn_cn_b2       | testing board 2      | 2       |                       |
          | test_board_fn_cn_b3       | testing board 3      | 3       |                       |
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

	Scenario: See Board list
        Given I am on "/en/forum/admin/manage-boards/"
          And I should see "test_board_fn_cn_b1"
          And I should see "test_board_fn_cn_b2"
          And I should see "test_board_fn_cn_b3"

	Scenario: See Board list filtered by category parametric filter
        Given I am on "/en/forum/admin/manage-boards/"
          And I should see "test_category_fn_c1"
          And I should see "test_category_fn_c2"
          And I should see "test_category_fn_c3"
		  And I should not see "test_category_f1_c1"
		  And I should not see "test_category_f1_c2"
		  And I should not see "test_category_f1_c3"
		  And I should not see "test_category_f2_c1"
		  And I should not see "test_category_f2_c2"
		  And I should not see "test_category_f2_c3"
		  And I follow "test_forum_f1"
          And I should not see "test_category_fn_c1"
          And I should not see "test_category_fn_c2"
          And I should not see "test_category_fn_c3"
		  And I should see "test_category_f1_c1"
		  And I should see "test_category_f1_c2"
		  And I should see "test_category_f1_c3"
		  And I should not see "test_category_f2_c1"
		  And I should not see "test_category_f2_c2"
		  And I should not see "test_category_f2_c3"
		  And I follow "test_forum_f2"
          And I should not see "test_category_fn_c1"
          And I should not see "test_category_fn_c2"
          And I should not see "test_category_fn_c3"
		  And I should not see "test_category_f1_c1"
		  And I should not see "test_category_f1_c2"
		  And I should not see "test_category_f1_c3"
		  And I should see "test_category_f2_c1"
		  And I should see "test_category_f2_c2"
		  And I should see "test_category_f2_c3"

	Scenario: See Category list filtered by forum and category parametric filter
        Given I am on "/en/forum/admin/manage-boards/"
		  And I should see "test_board_fn_cn_b1"
		  And I should see "test_board_fn_cn_b2"
		  And I should see "test_board_fn_cn_b3"
		  And I should not see "test_board_fn_c1_b1"
		  And I should not see "test_board_fn_c1_b2"
		  And I should not see "test_board_fn_c1_b3"
		  And I should not see "test_board_fn_c2_b1"
		  And I should not see "test_board_fn_c2_b2"
		  And I should not see "test_board_fn_c2_b3"
		  And I should not see "test_board_fn_c3_b1"
		  And I should not see "test_board_fn_c3_b2"
		  And I should not see "test_board_fn_c3_b3"
          And I follow "test_category_fn_c1"
		  And I should not see "test_board_fn_cn_b1"
		  And I should not see "test_board_fn_cn_b2"
		  And I should not see "test_board_fn_cn_b3"
		  And I should see "test_board_fn_c1_b1"
		  And I should see "test_board_fn_c1_b2"
		  And I should see "test_board_fn_c1_b3"
		  And I should not see "test_board_fn_c2_b1"
		  And I should not see "test_board_fn_c2_b2"
		  And I should not see "test_board_fn_c2_b3"
		  And I should not see "test_board_fn_c3_b1"
		  And I should not see "test_board_fn_c3_b2"
		  And I should not see "test_board_fn_c3_b3"
		  And I follow "test_forum_f1"
		  And I follow "test_category_f1_c1"
		  And I should not see "test_board_fn_cn_b1"
		  And I should not see "test_board_fn_cn_b2"
		  And I should not see "test_board_fn_cn_b3"
		  And I should not see "test_board_fn_c1_b1"
		  And I should not see "test_board_fn_c1_b2"
		  And I should not see "test_board_fn_c1_b3"
		  And I should see "test_board_f1_c1_b1"
		  And I should see "test_board_f1_c1_b2"
		  And I should see "test_board_f1_c1_b3"
		  And I should not see "test_board_f1_c2_b1"
		  And I should not see "test_board_f1_c2_b2"
		  And I should not see "test_board_f1_c2_b3"
		  And I should not see "test_board_f1_c3_b1"
		  And I should not see "test_board_f1_c3_b2"
		  And I should not see "test_board_f1_c3_b3"
		  And I follow "test_forum_f2"
		  And I follow "test_category_f2_c1"
		  And I should not see "test_board_f2_cn_b1"
		  And I should not see "test_board_f2_cn_b2"
		  And I should not see "test_board_f2_cn_b3"
		  And I should see "test_board_f2_c1_b1"
		  And I should see "test_board_f2_c1_b2"
		  And I should see "test_board_f2_c1_b3"
		  And I should not see "test_board_f2_c2_b1"
		  And I should not see "test_board_f2_c2_b2"
		  And I should not see "test_board_f2_c2_b3"
		  And I should not see "test_board_f2_c3_b1"
		  And I should not see "test_board_f2_c3_b2"
		  And I should not see "test_board_f2_c3_b3"

    Scenario: Create a new Board (Unassigned)
        Given I am on "/en/forum/admin/manage-boards/create"
		  And I should see "Create New Board"
          And I fill in "Forum_BoardCreate[name]" with "Test Board"
		  And I fill in "Forum_BoardCreate[description]" with "Some description"
          And I press "submit[post]"
          And I should see "Test Board" for the query "table#admin-boards-list tr td:nth-child(2)"

    Scenario: Create a new Board (Assigned)
        Given I am on "/en/forum/admin/manage-boards/create"
		  And I select "test_category_f1_c1" from "Forum_BoardCreate[category]"
          And I fill in "Forum_BoardCreate[name]" with "New Test Board"
		  And I fill in "Forum_BoardCreate[description]" with "Some description"
          And I press "submit[post]"
          And I should see "New Test Board" for the query "table#admin-boards-list tr td:nth-child(2)"
		Given I am on "/en/forum/admin/manage-boards/"
          And I should not see "New Test Board" for the query "table#admin-boards-list tr td:nth-child(2)"

    Scenario: Update existing Board (Assign)
	    Given I am on "/en/forum/admin/manage-boards/"
		  And I follow "update_board[test_board_fn_cn_b1]"
		  And I should see "test_board_fn_cn_b1"
		  And I select "test_category_f1_c1" from "Forum_BoardUpdate[category]"
          And I fill in "Forum_BoardUpdate[name]" with "Testing Board update form"
		  And I fill in "Forum_BoardUpdate[description]" with "new board description"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-boards/"
		  And I should not see "test_board_fn_cn_b1" for the query "table#admin-boards-list tr td:nth-child(2)"
		  And I should see "test_forum_f1"
		  And I follow "test_forum_f1"
		  And I should see "test_category_f1_c1"
		  And I follow "test_category_f1_c1"
          And I should see "Testing Board update form" for the query "table#admin-boards-list tr td:nth-child(2)"

    Scenario: Update existing Board (Unassign)
	    Given I am on "/en/forum/admin/manage-boards/"
		  And I should see "test_forum_f1"
		  And I follow "test_forum_f1"
		  And I should see "test_category_f1_c2"
		  And I follow "test_category_f1_c2"
		  And I follow "update_board[test_board_f1_c2_b1]"
		  And I should see "test_board_f1_c2_b1"
		  And I select "" from "Forum_BoardUpdate[category]"
          And I fill in "Forum_BoardUpdate[name]" with "Testing Board update form"
		  And I fill in "Forum_BoardUpdate[description]" with "new board description"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-boards/"
          And I should see "Testing Board update form" for the query "table#admin-boards-list tr td:nth-child(2)"
		  And I should see "test_forum_f1"
		  And I follow "test_forum_f1"
		  And I should see "test_category_f1_c2"
		  And I follow "test_category_f1_c2"
		  And I should not see "test_board_f1_c2_b1" for the query "table#admin-boards-list tr td:nth-child(2)"

    Scenario: Delete existing Board
	    Given I am on "/en/forum/admin/manage-boards/"
		  And I should see "test_forum_f1"
		  And I follow "test_forum_f1"
		  And I should see "test_category_f1_c3"
		  And I follow "test_category_f1_c3"
		  And I follow "delete_board[test_board_f1_c3_b1]"
		  And I should see "test_board_f1_c3_b1"
		  And I check "Forum_BoardDelete[confirm_delete]"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-boards/"
		  And I should see "test_forum_f1"
		  And I follow "test_forum_f1"
		  And I should see "test_category_f1_c3"
		  And I follow "test_category_f1_c3"
          And I should not see "test_board_f1_c3_b1" for the query "table#admin-boards-list tr td:nth-child(2)"

	Scenario: Reorder boards
		Given I am on "/en/forum/admin/manage-boards/"
		  And I follow "test_forum_f2"
          And I follow "test_category_f2_c3"
		  And "test_board_f2_c3_b1" should precede "test_board_f2_c3_b2" for the query "table#admin-boards-list tr td:nth-child(2)"
          And "test_board_f2_c3_b2" should precede "test_board_f2_c3_b3" for the query "table#admin-boards-list tr td:nth-child(2)"
		  And I follow "reorder_up_board[test_board_f2_c3_b1]"
          And "test_board_f2_c3_b2" should precede "test_board_f2_c3_b3" for the query "table#admin-boards-list tr td:nth-child(2)"
          And "test_board_f2_c3_b3" should precede "test_board_f2_c3_b1" for the query "table#admin-boards-list tr td:nth-child(2)"
		  And I follow "reorder_up_board[test_board_f2_c3_b1]"
          And "test_board_f2_c3_b2" should precede "test_board_f2_c3_b1" for the query "table#admin-boards-list tr td:nth-child(2)"
          And "test_board_f2_c3_b1" should precede "test_board_f2_c3_b3" for the query "table#admin-boards-list tr td:nth-child(2)"
		  And I follow "reorder_up_board[test_board_f2_c3_b1]"
          And "test_board_f2_c3_b1" should precede "test_board_f2_c3_b2" for the query "table#admin-boards-list tr td:nth-child(2)"
          And "test_board_f2_c3_b2" should precede "test_board_f2_c3_b3" for the query "table#admin-boards-list tr td:nth-child(2)"
		  And I follow "reorder_down_board[test_board_f2_c3_b1]"
          And "test_board_f2_c3_b2" should precede "test_board_f2_c3_b1" for the query "table#admin-boards-list tr td:nth-child(2)"
          And "test_board_f2_c3_b1" should precede "test_board_f2_c3_b3" for the query "table#admin-boards-list tr td:nth-child(2)"
		  And I follow "reorder_down_board[test_board_f2_c3_b1]"
          And "test_board_f2_c3_b2" should precede "test_board_f2_c3_b3" for the query "table#admin-boards-list tr td:nth-child(2)"
          And "test_board_f2_c3_b3" should precede "test_board_f2_c3_b1" for the query "table#admin-boards-list tr td:nth-child(2)"
		  And I follow "reorder_down_board[test_board_f2_c3_b1]"
          And "test_board_f2_c3_b1" should precede "test_board_f2_c3_b2" for the query "table#admin-boards-list tr td:nth-child(2)"
          And "test_board_f2_c3_b2" should precede "test_board_f2_c3_b3" for the query "table#admin-boards-list tr td:nth-child(2)"
