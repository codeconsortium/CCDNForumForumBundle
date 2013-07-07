Feature: Forum Post Management
	In order to manage posts
	As an administrator
	I want to be able to list, lock/unlock, delete and restore posts.

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