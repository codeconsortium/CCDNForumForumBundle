Feature: Forum Management
	In order to manage forums
	As an administrator
	I want to be able to list, create, edit and delete forums.

	Scenario: See forum list
	    Given I am on "/en/forum/admin/manage-forums/list" 

	Scenario: See forum create
	    Given I am on "/en/forum/admin/manage-forums/create"

	Scenario: See forum read
	    Given I am on "/en/forum/admin/manage-forums/show"

	Scenario: See forum update
	    Given I am on "/en/forum/admin/manage-forums/update"

	Scenario: See forum delete
	    Given I am on "/en/forum/admin/manage-forums/delete"
