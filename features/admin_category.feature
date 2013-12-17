Feature: Admin Category Management
	In order to manage categories
	As an administrator
	I want to be able to list, create, edit and delete categories.

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

	Scenario: See Category list
        Given I am on "/en/forum/admin/manage-categories/" 
          And I should see "test_category_fn_c1"
          And I should see "test_category_fn_c2"
          And I should see "test_category_fn_c3"
		  And I should not see "test_category_f1_c1"
		  And I should not see "test_category_f1_c2"
		  And I should not see "test_category_f1_c3"
		  And I should not see "test_category_f2_c1"
		  And I should not see "test_category_f2_c2"
		  And I should not see "test_category_f2_c3"

	Scenario: See Category list filtered by forum
        Given I am on "/en/forum/admin/manage-categories/"
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

    Scenario: Create a new Category (Unassigned)
        Given I am on "/en/forum/admin/manage-categories/create"
		  And I should see "Create New Category"
          And I fill in "Forum_CategoryCreate[name]" with "My_New_Category_1"
          And I press "submit[post]"
		 Then I am on "/en/forum/admin/manage-categories/"
          And I should see "My_New_Category_1" for the query "table#admin-categories-list tr td:nth-child(2)"
		 Then I follow "test_forum_f1"
          And I should not see "My_New_Category_1" for the query "table#admin-categories-list tr td:nth-child(2)"
		 Then I follow "test_forum_f2"
          And I should not see "My_New_Category_1" for the query "table#admin-categories-list tr td:nth-child(2)"
		 Then I follow "test_forum_f3"
          And I should not see "My_New_Category_1" for the query "table#admin-categories-list tr td:nth-child(2)"

    Scenario: Create a new Category (Assigned)
        Given I am on "/en/forum/admin/manage-categories/create"
		  And I should see "Create New Category"
		  And I select "test_forum_f1" from "Forum_CategoryCreate[forum]"
          And I fill in "Forum_CategoryCreate[name]" with "My_New_Category_2"
          And I press "submit[post]"
		  And I should see "My_New_Category_2" for the query "table#admin-categories-list tr td:nth-child(2)"
        Given I am on "/en/forum/admin/manage-categories/"
          And I should not see "My_New_Category_2" for the query "table#admin-categories-list tr td:nth-child(2)"
		 Then I follow "test_forum_f1"
          And I should see "My_New_Category_2" for the query "table#admin-categories-list tr td:nth-child(2)"

    Scenario: Update existing Category (Assign)
	    Given I am on "/en/forum/admin/manage-categories/"
		  And I follow "update_category[test_category_fn_c1]"
		  And I should see "test_category_fn_c1"
          And I fill in "Forum_CategoryUpdate[name]" with "UpdatedCategoryName_1"
		  And I select "test_forum_f1" from "Forum_CategoryUpdate[forum]"
          And I press "submit[post]"
		  And I should not see "test_category_fn_c1"
          And I should see "UpdatedCategoryName_1"
		Given I am on "/en/forum/admin/manage-categories/"
		  And I should not see "test_category_fn_c1" for the query "table#admin-categories-list tr td:nth-child(2)"
          And I should not see "UpdatedCategoryName_1" for the query "table#admin-categories-list tr td:nth-child(2)"

    Scenario: Update existing Category (Unassign)
	    Given I am on "/en/forum/admin/manage-categories/"
		  And I follow "test_forum_f1"
		  And I follow "update_category[test_category_f1_c2]"
		  And I should see "test_category_f1_c2"
          And I fill in "Forum_CategoryUpdate[name]" with "UpdatedCategoryName_2"
		  And I select "" from "Forum_CategoryUpdate[forum]"
          And I press "submit[post]"
		  And I should not see "test_category_f1_c2" for the query "table#admin-categories-list tr td:nth-child(2)"
          And I should see "UpdatedCategoryName_2" for the query "table#admin-categories-list tr td:nth-child(2)"
		  And I follow "test_forum_f1"
		  And I should not see "test_category_f1_c2" for the query "table#admin-categories-list tr td:nth-child(2)"
		  And I should not see "UpdatedCategoryName_2" for the query "table#admin-categories-list tr td:nth-child(2)"
		  And I follow "test_forum_f2"
		  And I should not see "test_category_f1_c2" for the query "table#admin-categories-list tr td:nth-child(2)"
		  And I should not see "UpdatedCategoryName_2" for the query "table#admin-categories-list tr td:nth-child(2)"
		  And I follow "test_forum_f3"
		  And I should not see "test_category_f1_c2" for the query "table#admin-categories-list tr td:nth-child(2)"
		  And I should not see "UpdatedCategoryName_2" for the query "table#admin-categories-list tr td:nth-child(2)"

    Scenario: Delete existing Category
	    Given I am on "/en/forum/admin/manage-categories/"
		  And I follow "delete_category[test_category_fn_c3]"
		  And I should see "test_category_fn_c3"
		  And I check "Forum_CategoryDelete[confirm_delete]"
          And I press "submit[post]"
		  And I should not see "Delete Category"
          And I should not see "test_category_fn_c3" for the query "table#admin-categories-list tr td:nth-child(2)"

	Scenario: Reorder categories
		Given I am on "/en/forum/admin/manage-categories/"
		  And I follow "test_forum_f1"
          And I should see "test_category_f1_c1"
          And "test_category_f1_c1" should precede "test_category_f1_c2" for the query "table#admin-categories-list tr td:nth-child(2)"
          And "test_category_f1_c2" should precede "test_category_f1_c3" for the query "table#admin-categories-list tr td:nth-child(2)"
		  And I follow "reorder_up_category[test_category_f1_c1]"
          And "test_category_f1_c2" should precede "test_category_f1_c3" for the query "table#admin-categories-list tr td:nth-child(2)"
          And "test_category_f1_c3" should precede "test_category_f1_c1" for the query "table#admin-categories-list tr td:nth-child(2)"
		  And I follow "reorder_up_category[test_category_f1_c1]"
          And "test_category_f1_c2" should precede "test_category_f1_c1" for the query "table#admin-categories-list tr td:nth-child(2)"
          And "test_category_f1_c1" should precede "test_category_f1_c3" for the query "table#admin-categories-list tr td:nth-child(2)"
		  And I follow "reorder_up_category[test_category_f1_c1]"
          And "test_category_f1_c1" should precede "test_category_f1_c2" for the query "table#admin-categories-list tr td:nth-child(2)"
          And "test_category_f1_c2" should precede "test_category_f1_c3" for the query "table#admin-categories-list tr td:nth-child(2)"
		  And I follow "reorder_down_category[test_category_f1_c1]"
          And "test_category_f1_c2" should precede "test_category_f1_c1" for the query "table#admin-categories-list tr td:nth-child(2)"
          And "test_category_f1_c1" should precede "test_category_f1_c3" for the query "table#admin-categories-list tr td:nth-child(2)"
		  And I follow "reorder_down_category[test_category_f1_c1]"
          And "test_category_f1_c2" should precede "test_category_f1_c3" for the query "table#admin-categories-list tr td:nth-child(2)"
          And "test_category_f1_c3" should precede "test_category_f1_c1" for the query "table#admin-categories-list tr td:nth-child(2)"
		  And I follow "reorder_down_category[test_category_f1_c1]"
          And "test_category_f1_c1" should precede "test_category_f1_c2" for the query "table#admin-categories-list tr td:nth-child(2)"
          And "test_category_f1_c2" should precede "test_category_f1_c3" for the query "table#admin-categories-list tr td:nth-child(2)"
