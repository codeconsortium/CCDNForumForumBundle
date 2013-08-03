Feature: Board Management
	In order to manage boards
	As an administrator
	I want to be able to list, create, edit and delete boards.

    Background:
        Given I am logged in as "admin"
        And there are following users defined:
          | email          | password | enabled  | role                |
          | admin@foo.com  | root     | 1        | ROLE_SUPER_ADMIN    |
          | user@foo.com   | root     | 1        | ROLE_USER           |
        And there are following forums defined:
          | name                      | order    |
		  | test_forum_1              | 1        |
		  | test_forum_2              | 2        |
		  | test_forum_3              | 3        |
        And there are following categories defined:
          | name                      | order    | forum               |
          | test_category_1           | 1        |                     |
		  | test_category_2           | 2        |                     |
		  | test_category_3           | 3        |                     |
          | test_category_f1_1        | 1        | test_forum_1        |
		  | test_category_f1_2        | 2        | test_forum_1        |
		  | test_category_f1_3        | 3        | test_forum_1        |
          | test_category_f2_1        | 1        | test_forum_2        |
		  | test_category_f2_2        | 2        | test_forum_2        |
		  | test_category_f2_3        | 3        | test_forum_2        |
        And there are following boards defined:
          | name                      | description          | order | category              |
          | test_board_1              | testing board 1      | 1     |                       |
          | test_board_2              | testing board 2      | 2     |                       |
          | test_board_3              | testing board 3      | 3     |                       |
          | test_board_c1_1           | testing board 1      | 1     | test_category_1       |
          | test_board_c1_2           | testing board 2      | 2     | test_category_1       |
          | test_board_c1_3           | testing board 3      | 3     | test_category_1       |
          | test_board_c2_1           | testing board 1      | 1     | test_category_f1_1    |
          | test_board_c2_2           | testing board 2      | 2     | test_category_f1_1    |
          | test_board_c2_3           | testing board 3      | 3     | test_category_f1_1    |
          | test_board_c3_1           | testing board 1      | 1     | test_category_f2_1    |
          | test_board_c3_2           | testing board 2      | 2     | test_category_f2_1    |
          | test_board_c3_3           | testing board 3      | 3     | test_category_f2_1    |

	Scenario: See Board list
        Given I am on "/en/forum/admin/manage-boards/" 
          And I should see "test_board_1"
          And I should see "test_board_2"
          And I should see "test_board_3"

	Scenario: See Board list filtered by category parametric filter
        Given I am on "/en/forum/admin/manage-boards/"
          And I should see "test_category_1"
          And I should see "test_category_2"
          And I should see "test_category_3"
		  And I should not see "test_category_f1_1"
		  And I should not see "test_category_f1_2"
		  And I should not see "test_category_f1_3"
		  And I should not see "test_category_f2_1"
		  And I should not see "test_category_f2_2"
		  And I should not see "test_category_f2_3"
		  And I follow "test_forum_1"
          And I should not see "test_category_1"
          And I should not see "test_category_2"
          And I should not see "test_category_3"
		  And I should see "test_category_f1_1"
		  And I should see "test_category_f1_2"
		  And I should see "test_category_f1_3"
		  And I should not see "test_category_f2_1"
		  And I should not see "test_category_f2_2"
		  And I should not see "test_category_f2_3"
		  And I follow "test_forum_2"
          And I should not see "test_category_1"
          And I should not see "test_category_2"
          And I should not see "test_category_3"
		  And I should not see "test_category_f1_1"
		  And I should not see "test_category_f1_2"
		  And I should not see "test_category_f1_3"
		  And I should see "test_category_f2_1"
		  And I should see "test_category_f2_2"
		  And I should see "test_category_f2_3"

	Scenario: See Category list filtered by forum and category parametric filter
        Given I am on "/en/forum/admin/manage-boards/"
		  And I should see "test_board_1"
		  And I should see "test_board_2"
		  And I should see "test_board_3"
		  And I should not see "test_board_c1_1"
		  And I should not see "test_board_c1_2"
		  And I should not see "test_board_c1_3"
		  And I should not see "test_board_c2_1"
		  And I should not see "test_board_c2_2"
		  And I should not see "test_board_c2_3"
		  And I should not see "test_board_c3_1"
		  And I should not see "test_board_c3_2"
		  And I should not see "test_board_c3_3"
          And I follow "test_category_1"
		  And I should not see "test_board_1"
		  And I should not see "test_board_2"
		  And I should not see "test_board_3"
		  And I should see "test_board_c1_1"
		  And I should see "test_board_c1_2"
		  And I should see "test_board_c1_3"
		  And I should not see "test_board_c2_1"
		  And I should not see "test_board_c2_2"
		  And I should not see "test_board_c2_3"
		  And I should not see "test_board_c3_1"
		  And I should not see "test_board_c3_2"
		  And I should not see "test_board_c3_3"
		  And I follow "test_forum_1"
		  And I follow "test_category_f1_1"
		  And I should not see "test_board_1"
		  And I should not see "test_board_2"
		  And I should not see "test_board_3"
		  And I should not see "test_board_c1_1"
		  And I should not see "test_board_c1_2"
		  And I should not see "test_board_c1_3"
		  And I should see "test_board_c2_1"
		  And I should see "test_board_c2_2"
		  And I should see "test_board_c2_3"
		  And I should not see "test_board_c3_1"
		  And I should not see "test_board_c3_2"
		  And I should not see "test_board_c3_3"
		  And I follow "test_forum_2"
		  And I follow "test_category_f2_1"
		  And I should not see "test_board_1"
		  And I should not see "test_board_2"
		  And I should not see "test_board_3"
		  And I should not see "test_board_c1_1"
		  And I should not see "test_board_c1_2"
		  And I should not see "test_board_c1_3"
		  And I should not see "test_board_c2_1"
		  And I should not see "test_board_c2_2"
		  And I should not see "test_board_c2_3"
		  And I should see "test_board_c3_1"
		  And I should see "test_board_c3_2"
		  And I should see "test_board_c3_3"

    Scenario: Create a new Board (Unassigned)
        Given I am on "/en/forum/admin/manage-boards/create"
		  And I should see "Create New Board"
          And I fill in "Forum_BoardCreate[name]" with "Test Board"
		  And I fill in "Forum_BoardCreate[description]" with "Some description"
          And I press "submit[post]"
          And I should see "Test Board" for the query "table#admin-boards-list tr td:nth-child(2)"

    Scenario: Create a new Board (Assigned)
        Given I am on "/en/forum/admin/manage-boards/create"
		  And I should see "Create New Board"
		  And I select "test_category_f1_1" from "Forum_BoardCreate[category]"
          And I fill in "Forum_BoardCreate[name]" with "New Test Board"
		  And I fill in "Forum_BoardCreate[description]" with "Some description"
          And I press "submit[post]"
          And I should see "New Test Board" for the query "table#admin-boards-list tr td:nth-child(2)"
		Given I am on "/en/forum/admin/manage-boards/"
          And I should not see "New Test Board" for the query "table#admin-boards-list tr td:nth-child(2)"

    Scenario: Abort Create a new Board
        Given I am on "/en/forum/admin/manage-boards/create"
		  And I should see "Create New Board"
          And I follow "Cancel"
		  And I should not see "Create New Board"

    Scenario: Update existing Board (Assign)
	    Given I am on "/en/forum/admin/manage-boards/"
		  And I follow "update_board[test_board_1]"
		  And I should see "Update Board"
		  And I should see "test_board_1"
		  And I select "test_category_f1_1" from "Forum_BoardUpdate[category]"
          And I fill in "Forum_BoardUpdate[name]" with "Testing Board update form"
		  And I fill in "Forum_BoardUpdate[description]" with "new board description"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-boards/"
		  And I should not see "test_board_1" for the query "table#admin-boards-list tr td:nth-child(2)"
          And I should see "Testing Board update form" for the query "table#admin-boards-list tr td:nth-child(2)"

    Scenario: Update existing Board (Unassign)
	    Given I am on "/en/forum/admin/manage-boards/"
		  And I follow "test_forum_1"
		  And I follow "test_category_f1_1"
		  And I follow "update_board[test_board_c2_1]"
		  And I should see "Update Board"
		  And I should see "test_board_c2_1"
		  And I select "" from "Forum_BoardUpdate[category]"
          And I fill in "Forum_BoardUpdate[name]" with "Testing Board update form"
		  And I fill in "Forum_BoardUpdate[description]" with "new board description"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-boards/"
		  And I should not see "test_board_c2_1" for the query "table#admin-boards-list tr td:nth-child(2)"
          And I should see "Testing Board update form" for the query "table#admin-boards-list tr td:nth-child(2)"

    Scenario: Abort Update existing Board
	    Given I am on "/en/forum/admin/manage-boards/"
		  And I follow "update_board[test_board_1]"
		  And I should see "Update Board"
		  And I should see "test_board_1"
          And I follow "Cancel"
		 Then I should be on "/en/forum/admin/manage-boards/"
		  And I should see "test_board_1" for the query "table#admin-boards-list tr td:nth-child(2)"

    Scenario: Delete existing Board
	    Given I am on "/en/forum/admin/manage-boards/"
		  And I follow "delete_board[test_board_3]"
		  And I should see "Delete Board"
		  And I should see "test_board_3"
		  And I check "Forum_BoardDelete[confirm_delete][]"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-boards/"
          And I should not see "test_board_3" for the query "table#admin-boards-list tr td:nth-child(2)"

    Scenario: Abort deleting existing Board
	    Given I am on "/en/forum/admin/manage-boards/"
		  And I follow "delete_board[test_board_3]"
		  And I should see "Delete Board"
		  And I should see "test_board_3"
		  And I follow "Cancel"
		 Then I should be on "/en/forum/admin/manage-boards/"
          And I should see "test_board_3" for the query "table#admin-boards-list tr td:nth-child(2)"

	Scenario: Reorder boards
		Given I am on "/en/forum/admin/manage-boards/"
		  And I follow "test_forum_1"
          And I follow "test_category_f1_1"
		  And "test_board_c2_1" should precede "test_board_c2_2" for the query "table#admin-boards-list tr td:nth-child(2)"
          And "test_board_c2_2" should precede "test_board_c2_3" for the query "table#admin-boards-list tr td:nth-child(2)"
		  And I follow "reorder_up_board[test_board_c2_1]"
          And "test_board_c2_2" should precede "test_board_c2_3" for the query "table#admin-boards-list tr td:nth-child(2)"
          And "test_board_c2_3" should precede "test_board_c2_1" for the query "table#admin-boards-list tr td:nth-child(2)"
		  And I follow "reorder_up_board[test_board_c2_1]"
          And "test_board_c2_2" should precede "test_board_c2_1" for the query "table#admin-boards-list tr td:nth-child(2)"
          And "test_board_c2_1" should precede "test_board_c2_3" for the query "table#admin-boards-list tr td:nth-child(2)"
		  And I follow "reorder_up_board[test_board_c2_1]"
          And "test_board_c2_1" should precede "test_board_c2_2" for the query "table#admin-boards-list tr td:nth-child(2)"
          And "test_board_c2_2" should precede "test_board_c2_3" for the query "table#admin-boards-list tr td:nth-child(2)"
		  And I follow "reorder_down_board[test_board_c2_1]"
          And "test_board_c2_2" should precede "test_board_c2_1" for the query "table#admin-boards-list tr td:nth-child(2)"
          And "test_board_c2_1" should precede "test_board_c2_3" for the query "table#admin-boards-list tr td:nth-child(2)"
		  And I follow "reorder_down_board[test_board_c2_1]"
          And "test_board_c2_2" should precede "test_board_c2_3" for the query "table#admin-boards-list tr td:nth-child(2)"
          And "test_board_c2_3" should precede "test_board_c2_1" for the query "table#admin-boards-list tr td:nth-child(2)"
		  And I follow "reorder_down_board[test_board_c2_1]"
          And "test_board_c2_1" should precede "test_board_c2_2" for the query "table#admin-boards-list tr td:nth-child(2)"
          And "test_board_c2_2" should precede "test_board_c2_3" for the query "table#admin-boards-list tr td:nth-child(2)"
