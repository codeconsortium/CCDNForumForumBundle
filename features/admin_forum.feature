Feature: Admin Forum Management
	In order to manage forums
	As an administrator
	I want to be able to list, create, edit and delete forums.

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

	Scenario: See Forum list
        Given I am on "/en/forum/admin/manage-forums/" 
          And I should see "test_forum_f1"
          And I should see "test_forum_f2"
          And I should see "test_forum_f3"

    Scenario: Create a new Forum
        Given I am on "/en/forum/admin/manage-forums/create"
		  And I should see "Create New Forum"
          And I fill in "Forum_ForumCreate[name]" with "FooBar"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-forums/"
          And I should see "FooBar" for the query "table#admin-forums-list tr td:nth-child(2)"

    Scenario: Update existing Forum
	    Given I am on "/en/forum/admin/manage-forums/"
		  And I follow "update_forum[test_forum_f1]"
		  And I should see "test_forum_f1"
          And I fill in "Forum_ForumUpdate[name]" with "FooBaz"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-forums/"
		  And I should not see "test_forum_f1" for the query "table#admin-forums-list tr td:nth-child(2)"
          And I should see "FooBaz" for the query "table#admin-forums-list tr td:nth-child(2)"
		  
    Scenario: Delete existing Forum
	    Given I am on "/en/forum/admin/manage-forums/"
		  And I follow "delete_forum[test_forum_f3]"
		  And I should see "test_forum_f3"
		  And I check "Forum_ForumDelete[confirm_delete]"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-forums/"
          And I should not see "test_forum_f3" for the query "table#admin-forums-list tr td:nth-child(2)"
