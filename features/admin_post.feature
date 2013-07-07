Feature: Forum Post Management
	In order to manage posts
	As an administrator
	I want to be able to list, lock/unlock, delete and restore posts.

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

	Scenario: See deleted posts list
	    Given I am on "/en/forum/admin/manage-posts/deleted"

	Scenario: Restore soft-deleted posts
	    Given I am on "/en/forum/admin/manage-posts/deleted/bulk-action"

	Scenario: Hard-delete posts
	    Given I am on "/en/forum/admin/manage-posts/deleted/bulk-action"


	Scenario: See locked posts list
	    Given I am on "/en/forum/admin/manage-posts/locked"

	Scenario: Unlock selected posts
	    Given I am on "/en/forum/admin/manage-posts/locked/bulk-action"

	Scenario: Soft-Delete selected posts
	    Given I am on "/en/forum/admin/manage-posts/locked/bulk-action"