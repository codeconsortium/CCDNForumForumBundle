Feature: Forum Topic Management
	In order to manage topics
	As an administrator
	I want to be able to list, close/open, delete and restore topics.

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
