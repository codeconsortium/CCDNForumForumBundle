Feature: Forum Management
	In order to manage forums
	As an administrator
	I want to be able to list, create, edit and delete forums.

    Background:
        Given I am logged in as admin
        And there are following users defined:
          | email          | password | enabled  | role          |
          | admin@foo.com  | root     | 1        | ROLE_ADMIN    |
          | user@foo.com   | root     | 1        | ROLE_USER     |
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

	Scenario: See forum list
        Given I am on "/en/forum/admin/manage-forums/" 
          And I should see "test_forum_1"
          And I should see "test_forum_2"
          And I should see "test_forum_3"

    Scenario: See forum create
        Given I am on "/en/forum/admin/manage-forums/create"
          And I fill in "ForumCreate[name]" with "FooBar"
          And I press "Save"
		 Then I should be on "/en/forum/admin/manage-forums/"
          And I should see "FooBar"

    Scenario: See forum update
	    Given I am on "/en/forum/admin/manage-forums/"
		  And I follow "test_forum_1"
          And I fill in "ForumUpdate[name]" with "FooBaz"
          And I press "Update"
		 Then I should be on "/en/forum/admin/manage-forums/"
          And I should see "FooBaz"

    Scenario: See forum delete
        Given I am on "/en/forum/admin/manage-forums/delete"
