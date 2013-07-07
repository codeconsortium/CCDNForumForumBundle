Feature: Forum Board Management
	In order to manage boards
	As an administrator
	I want to be able to list, create, edit and delete boards.

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
