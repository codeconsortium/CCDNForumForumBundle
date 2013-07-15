Feature: Category Management
	In order to manage categories
	As an administrator
	I want to be able to list, create, edit and delete categories.

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

	Scenario: See Category list
        Given I am on "/en/forum/admin/manage-categories/" 
          And I should see "test_category_1"
          And I should see "test_category_2"
          And I should see "test_category_3"

    Scenario: Create a new Category
        Given I am on "/en/forum/admin/manage-categories/create"
		  And I should see "Create New Category"
          And I fill in "Forum_CategoryCreate[name]" with "FooBar"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-categories/"
          And I should see "FooBar"

    Scenario: Abort Create a new Category
        Given I am on "/en/forum/admin/manage-categories/create"
		  And I should see "Create New Category"
          And I follow "Cancel"
		 Then I should be on "/en/forum/admin/manage-categories/"

    Scenario: Update existing Category
	    Given I am on "/en/forum/admin/manage-categories/"
		  And I follow "update_category[test_category_1]"
		  And I should see "Update Category"
		  And I should see "test_category_1"
          And I fill in "Forum_CategoryUpdate[name]" with "FooBaz"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-categories/"
		  And I should not see "test_category_1"
          And I should see "FooBaz"

    Scenario: Abort Update existing Category
	    Given I am on "/en/forum/admin/manage-categories/"
		  And I follow "update_category[test_category_1]"
		  And I should see "Update Category"
		  And I should see "test_category_1"
          And I follow "Cancel"
		 Then I should be on "/en/forum/admin/manage-categories/"
		  And I should see "test_category_1"

    Scenario: Delete existing Category
	    Given I am on "/en/forum/admin/manage-categories/"
		  And I follow "delete_category[test_category_3]"
		  And I should see "Delete Category"
		  And I should see "test_category_3"
		  And I check "Forum_CategoryDelete[confirm_delete][]"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-categories/"
          And I should not see "test_category_3"

    Scenario: Abort existing Category
	    Given I am on "/en/forum/admin/manage-categories/"
		  And I follow "delete_category[test_category_3]"
		  And I should see "Delete Category"
		  And I should see "test_category_3"
		  And I follow "Cancel"
		 Then I should be on "/en/forum/admin/manage-categories/"
          And I should see "test_category_3"
