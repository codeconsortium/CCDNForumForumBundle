Feature: Forum Topic Management
	In order to manage topics
	As an administrator
	I want to be able to list, close/open, delete and restore topics.

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

	Scenario: See topics deleted list
	    Given I am on "/en/forum/admin/manage-topics/deleted"

	Scenario: Close topics that are deleted
	    Given I am on "/en/forum/admin/manage-topics/deleted/bulk-action"

	Scenario: Open topics that are deleted
	    Given I am on "/en/forum/admin/manage-topics/deleted/bulk-action"

	Scenario: Hard-delete topics that are soft-deleted
	    Given I am on "/en/forum/admin/manage-topics/deleted/bulk-action"

	Scenario: Restore deleted topics
	    Given I am on "/en/forum/admin/manage-topics/deleted/bulk-action"


	Scenario: See topics closed list
	    Given I am on "/en/forum/admin/manage-topics/closed"

	Scenario: Open closed topics
	    Given I am on "/en/forum/admin/manage-topics/closed/bulk-action"

	Scenario: Soft-Delete closed topics
	    Given I am on "/en/forum/admin/manage-topics/closed/bulk-action"
