Feature: Forum Board Management
	In order to manage boards
	As an administrator
	I want to be able to list, create, edit and delete boards.

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

	Scenario: See board list
	    Given I am on "/en/forum/admin/manage-boards/list"

	Scenario: See board create
	    Given I am on "/en/forum/admin/manage-boards/create"

	Scenario: See board read
	    Given I am on "/en/forum/admin/manage-boards/show"

	Scenario: See board update
	    Given I am on "/en/forum/admin/manage-boards/update"

	Scenario: See board delete
	    Given I am on "/en/forum/admin/manage-boards/delete"
