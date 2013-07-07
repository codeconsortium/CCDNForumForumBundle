Feature: Forum Category Management
	In order to manage categories
	As an administrator
	I want to be able to list, create, edit and delete categories.

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
