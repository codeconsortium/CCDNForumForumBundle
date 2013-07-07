Feature: Forum Category Management
	In order to manage categories
	As an administrator
	I want to be able to list, create, edit and delete categories.

    Background:
        Given I am logged in as admin
        And there are following users defined:
          | email          | password | enabled | role          |
          | admin@foo.com  | root     | 1       | ROLE_ADMIN    |
          | user@foo.com   | root     | 1       | ROLE_USER     |
        And there are following forums defined:
          | name           | order    |
		  | test_f_1       | 1        |
		  | test_f_2       | 2        |
		  | test_f_3       | 3        |
        And there are following categories defined:
          | name           | order    |
          | test_c_1       | 1        |
		  | test_c_2       | 2        |
		  | test_c_3       | 3        |
        And there are following boards defined:
          | name           | description          |
          | test_b_1       | testing board 1      |
          | test_b_2       | testing board 2      |
          | test_b_3       | testing board 3      |

	Scenario: See category list
	    Given I am on "/en/forum/admin/manage-categories/list"

	Scenario: See category create
	    Given I am on "/en/forum/admin/manage-categories/create"

	Scenario: See category read
	    Given I am on "/en/forum/admin/manage-categories/show"

	Scenario: See category update
	    Given I am on "/en/forum/admin/manage-categories/update"

	Scenario: See category delete
	    Given I am on "/en/forum/admin/manage-categories/delete"
