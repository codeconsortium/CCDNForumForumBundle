Feature: Board Management
	In order to manage boards
	As an administrator
	I want to be able to list, create, edit and delete boards.

    Background:
        Given I am logged in as admin
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
          | name                      | order    |
          | test_category_1           | 1        |
		  | test_category_2           | 2        |
		  | test_category_3           | 3        |
        And there are following boards defined:
          | name                      | description          |
          | test_board_1              | testing board 1      |
          | test_board_2              | testing board 2      |
          | test_board_3              | testing board 3      |

	Scenario: See Board list
        Given I am on "/en/forum/admin/manage-boards/" 
          And I should see "test_board_1"
          And I should see "test_board_2"
          And I should see "test_board_3"

    Scenario: Create a new Board
        Given I am on "/en/forum/admin/manage-boards/create"
		  And I should see "Create New Board"
          And I fill in "Forum_BoardCreate[name]" with "FooBar"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-boards/"
          And I should see "FooBar"

    Scenario: Abort Create a new Board
        Given I am on "/en/forum/admin/manage-boards/create"
		  And I should see "Create New Board"
          And I follow "Cancel"
		 Then I should be on "/en/forum/admin/manage-boards/"

    Scenario: Update existing Board
	    Given I am on "/en/forum/admin/manage-boards/"
		  And I follow "update_board[test_board_1]"
		  And I should see "Update Board"
		  And I should see "test_board_1"
          And I fill in "Forum_BoardUpdate[name]" with "FooBaz"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-boards/"
		  And I should not see "test_board_1"
          And I should see "FooBaz"

    Scenario: Abort Update existing Board
	    Given I am on "/en/forum/admin/manage-boards/"
		  And I follow "update_category[test_board_1]"
		  And I should see "Update Board"
		  And I should see "test_board_1"
          And I follow "Cancel"
		 Then I should be on "/en/forum/admin/manage-boards/"
		  And I should see "test_board_1"

    Scenario: Delete existing Board
	    Given I am on "/en/forum/admin/manage-boards/"
		  And I follow "delete_category[test_board_3]"
		  And I should see "Delete Board"
		  And I should see "test_board_3"
		  And I check "Forum_BoardDelete[confirm_delete][]"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-boards/"
          And I should not see "test_board_3"

    Scenario: Abort existing Board
	    Given I am on "/en/forum/admin/manage-boards/"
		  And I follow "delete_category[test_board_3]"
		  And I should see "Delete Board"
		  And I should see "test_board_3"
		  And I follow "Cancel"
		 Then I should be on "/en/forum/admin/manage-boards/"
          And I should see "test_board_3"
