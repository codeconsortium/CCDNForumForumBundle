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
		  
	Scenario: See Category list
        Given I am on "/en/forum/admin/manage-categories/" 
          And I should see "test_category_1"
          And I should see "test_category_2"
          And I should see "test_category_3"
		  And I should not see "test_category_f1_1"
		  And I should not see "test_category_f1_2"
		  And I should not see "test_category_f1_3"
		  And I should not see "test_category_f2_1"
		  And I should not see "test_category_f2_2"
		  And I should not see "test_category_f2_3"

	Scenario: See Category list filtered by forum
        Given I am on "/en/forum/admin/manage-categories/"
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

    Scenario: Create a new Category (Unassigned)
        Given I am on "/en/forum/admin/manage-categories/create"
		  And I should see "Create New Category"
          And I fill in "Forum_CategoryCreate[name]" with "My_New_Category_1"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-categories/"
          And I should see "My_New_Category_1"

    Scenario: Create a new Category (Assigned)
        Given I am on "/en/forum/admin/manage-categories/create"
		  And I should see "Create New Category"
		  And I select "test_forum_1" from "Forum_CategoryCreate[forum]"
          And I fill in "Forum_CategoryCreate[name]" with "My_New_Category_2"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-categories/"
		  And I should not see "My_New_Category_2"
		 Then I follow "test_forum_1"
          And I should see "My_New_Category_2"

    Scenario: Abort Create a new Category
        Given I am on "/en/forum/admin/manage-categories/create"
		  And I should see "Create New Category"
          And I follow "Cancel"
		 Then I should be on "/en/forum/admin/manage-categories/"

    Scenario: Update existing Category (Assign)
	    Given I am on "/en/forum/admin/manage-categories/"
		  And I follow "update_category[test_category_1]"
		  And I should see "Update Category"
		  And I should see "test_category_1"
          And I fill in "Forum_CategoryUpdate[name]" with "UpdatedCategoryName_1"
		  And I select "test_forum_1" from "Forum_CategoryUpdate[forum]"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-categories/"
		  And I should not see "test_category_1"
		  And I follow "test_forum_1"
		  And I should not see "test_category_1"
          And I should see "UpdatedCategoryName_1"

    Scenario: Update existing Category (Unassign)
	    Given I am on "/en/forum/admin/manage-categories/"
		  And I follow "test_forum_1"
		  And I follow "update_category[test_category_f1_2]"
		  And I should see "Update Category"
		  And I should see "test_category_f1_2"
          And I fill in "Forum_CategoryUpdate[name]" with "UpdatedCategoryName_2"
		  And I select "" from "Forum_CategoryUpdate[forum]"
          And I press "submit[post]"
		 Then I should be on "/en/forum/admin/manage-categories/"
		  And I should not see "test_category_f1_2"
          And I should see "UpdatedCategoryName_2"
		  And I follow "test_forum_1"
		  And I should not see "test_category_f1_2"
		  And I should not see "UpdatedCategoryName_2"

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

    Scenario: Abort deleting existing Category
	    Given I am on "/en/forum/admin/manage-categories/"
		  And I follow "delete_category[test_category_3]"
		  And I should see "Delete Category"
		  And I should see "test_category_3"
		  And I follow "Cancel"
		 Then I should be on "/en/forum/admin/manage-categories/"
          And I should see "test_category_3"
