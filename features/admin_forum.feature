Feature: Forum Management
	In order to manage forums
	As an administrator
	I want to be able to list, create, edit and delete forums.

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

	Scenario: See Forum list
        Given I am on "/en/forum/admin/manage-forums/" 
          And I should see "test_forum_1"
          And I should see "test_forum_2"
          And I should see "test_forum_3"

    Scenario: Create a new Forum
        Given I am on "/en/forum/admin/manage-forums/create"
		  And I should see "Create New Forum"
          And I fill in "Forum_ForumCreate[name]" with "FooBar"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-forums/"
          And I should see "FooBar"

    Scenario: Update existing Forum
	    Given I am on "/en/forum/admin/manage-forums/"
		  And I follow "update_forum[test_forum_1]"
		  And I should see "Update Forum"
		  And I should see "test_forum_1"
          And I fill in "Forum_ForumUpdate[name]" with "FooBaz"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-forums/"
		  And I should not see "test_forum_1"
          And I should see "FooBaz"

    Scenario: Delete existing Forum
	    Given I am on "/en/forum/admin/manage-forums/"
		  And I follow "delete_forum[test_forum_3]"
		  And I should see "Delete Forum"
		  And I should see "test_forum_3"
		  And I check "Forum_ForumDelete[confirm_delete][]"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-forums/"
          And I should not see "test_forum_3"
