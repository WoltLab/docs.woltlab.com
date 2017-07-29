---
title: "Tutorial Series Part 1: Base Structure"
sidebar: sidebar
permalink: tutorial_tutorial-series_part-1-base-structure.html
folder: tutorial/tutorial-series
parent: tutorial_tutorial-series
---

In the first part of this tutorial series, we will lay out what the basic version of package should be able to do and how to implement these functions.


## Package Functionality

The package should provide the following possibilities/functions:

- Sortable list of all people in the ACP
- Ability to add, edit and delete people in the ACP
- Restrict the ability to add, edit and delete people (in short: manage people) in the ACP
- Sortable list of all people in the front end


## Used Components

We will use the following package installation plugins:

- [acpTemplate package installation plugin](package_pip_acp-template.html),
- [acpMenu package installation plugin](package_pip_acp-menu.html),
- [file package installation plugin](package_pip_file.html),
- [language package installation plugin](package_pip_language.html),
- [menuItem package installation plugin](package_pip_menu-item.html),
- [page package installation plugin](package_pip_page.html),
- [sql package installation plugin](package_pip_sql.html),
- [template package installation plugin](package_pip_template.html),
- [userGroupOption package installation plugin](package_pip_user-group-option.html),

use [database objects](php_database-objects.html), create [pages](php_pages.html) and use [templates](view_templates.html).


## Package Structure

The package will have the following file structure:

```
├── acpMenu.xml
├── acptemplates
│   ├── personAdd.tpl
│   └── personList.tpl
├── files
│   └── lib
│       ├── acp
│       │   ├── form
│       │   │   ├── PersonAddForm.class.php
│       │   │   └── PersonEditForm.class.php
│       │   └── page
│       │       └── PersonListPage.class.php
│       ├── data
│       │   └── person
│       │       ├── PersonAction.class.php
│       │       ├── Person.class.php
│       │       ├── PersonEditor.class.php
│       │       └── PersonList.class.php
│       └── page
│           └── PersonListPage.class.php
├── install.sql
├── language
│   ├── de.xml
│   └── en.xml
├── menuItem.xml
├── package.xml
├── page.xml
├── templates
│   └── personList.tpl
└── userGroupOption.xml
```


## Person Modeling

### Database Table

As the first step, we have to model the people we want to manage with this package.
As this is only an introductory tutorial, we will keep things simple and only consider the first and last name of a person.
Thus, the database table we will store the people in only contains three columns:

1. `personID` is the unique numeric identifier of each person created,
1. `firstName` contains the first name of the person,
1. `lastName` contains the last name of the person.

The first file for our package is the `install.sql` file used to create such a database table during package installation:

{% highlight sql %}
{% include tutorial/tutorial-series/part-1/install.sql %}
{% endhighlight %}

### Database Object

#### `Person`

In our PHP code, each person will be represented by an object of the following class:

{% highlight php %}
{% include tutorial/tutorial-series/part-1/files/lib/data/person/Person.class.php %}
{% endhighlight %}

The important thing here is that `Person` extends `DatabaseObject`.
Additionally, we implement the `IRouteController` interface, which allows us to use `Person` objects to create links, and we implement PHP's magic [__toString()](https://secure.php.net/manual/en/language.oop5.magic.php#object.tostring) method for convenience.

For every database object, you need to implement three additional classes:
an action class, an editor class and a list class.

#### `PersonAction`

{% highlight php %}
{% include tutorial/tutorial-series/part-1/files/lib/data/person/PersonAction.class.php %}
{% endhighlight %}

This implementation of `AbstractDatabaseObjectAction` is very basic and only sets the `$permissionsDelete` and `$requireACP` properties.
This is done so that later on, when implementing the people list for the ACP, we can delete people simply via AJAX.
`$permissionsDelete` has to be set to the permission needed in order to delete a person.
We will later use the [userGroupOption package installation plugin](package_pip_user-group-option.html) to create the `admin.content.canManagePeople` permission.
`$requireACP` restricts deletion of people to the ACP.

#### `PersonEditor`

{% highlight php %}
{% include tutorial/tutorial-series/part-1/files/lib/data/person/PersonEditor.class.php %}
{% endhighlight %}

This implementation of `DatabaseObjectEditor` fulfills the minimum requirement for a database object editor:
setting the static `$baseClass` property to the database object class name.

#### `PersonList`

{% highlight php %}
{% include tutorial/tutorial-series/part-1/files/lib/data/person/PersonList.class.php %}
{% endhighlight %}

Due to the default implementation of `DatabaseObjectList`, our `PersonList` class just needs to extend it and everything else is either automatically set by the code of `DatabaseObjectList` or, in the case of properties and methods, provided by that class.


## ACP

Next, we will take care of the controllers and views for the ACP.
In total, we need three each:

1. page to list people,
1. form to add people, and
1. form to edit people.

Before we create the controllers and views, let us first create the menu items for the pages in the ACP menu.

### ACP Menu

We need to create three menu items:

1. a “parent” menu item on the second level of the ACP menu item tree,
1. a third level menu item for the people list page, and
1. a fourth level menu item for the form to add new people.

{% highlight xml %}
{% include tutorial/tutorial-series/part-1/acpMenu.xml %}
{% endhighlight %}

We choose `wcf.acp.menu.link.content` as the parent menu item for the first menu item `wcf.acp.menu.link.person` because the people we are managing is just one form of content.
The fourth level menu item `wcf.acp.menu.link.person.add` will only be shown as an icon and thus needs an additional element `icon` which takes a FontAwesome icon class as value.

### People List

To list the people in the ACP, we need a `PersonListPage` class and a `personList` template.

#### `PersonListPage`

{% highlight php %}
{% include tutorial/tutorial-series/part-1/files/lib/acp/page/PersonListPage.class.php %}
{% endhighlight %}

As WoltLab Suite Core already provides a powerful default implementation of a sortable page, our work here is minimal:

1. We need to set the active ACP menu item via the `$activeMenuItem`.
1. `$neededPermissions` contains a list of permissions of which the user needs to have at least one in order to see the person list.
   We use the same permission for both the menu item and the page.
1. The database object list class whose name is provided via `$objectListClassName` and that handles fetching the people from database is the `PersonList` class, which we have already created.
1. To validate the sort field passed with the request, we set `$validSortFields` to the available database table columns.

#### `personList.tpl`

{% highlight smarty %}
{% include tutorial/tutorial-series/part-1/acptemplates/personList.tpl %}
{% endhighlight %}

We will go piece by piece through the template code:

1. We include the `header` template and set the page title `wcf.acp.person.list`.
   You have to include this template for every page!
1. We set the content header and additional provide a button to create a new person in the content header navigation.
1. As not all people are listed on the same page if many people have been created, we need a pagination for which we use the `pages` template plugin.
   The `{hascontent}{content}{/content}{/hascontent}` construct ensures the `.paginationTop` element is only shown if the `pages` template plugin has a return value, thus if a pagination is necessary.
1. Now comes the main part of the page, the list of the people, which will only be displayed if any people exist.
   Otherwise, an info box is displayed using the generic `wcf.global.noItems` language item.
   The `$objects` template variable is automatically assigned by `wcf\page\MultipleLinkPage` and contains the `PersonList` object used to read the people from database.
   
   The table itself consists of a `thead` and a `tbody` element and is extendable with more columns using the template events `columnHeads` and `columns`.
   In general, every table should provide these events.
   The default structure of a table is used here so that the first column of the content rows contains icons to edit and to delete the row (and provides another standard event `rowButtons`) and that the second column contains the ID of the person.
   The table can be sorted by clicking on the head of each column.
   The used variables `$sortField` and `$sortOrder` are automatically assigned to the template by `SortablePage`.
1. The `.contentFooter` element is only shown if people exist as it basically repeats the `.contentHeaderNavigation` and `.paginationTop` element.
1. The JavaScript code here fulfills two duties:
   Handling clicks on the delete icons and forwarding the requests via AJAX to the `PersonAction` class, and setting up some code that triggers if all people shown on the current page are deleted via JavaScript to either reload the page or show the `wcf.global.noItems` info box. 
1. Lastly, the `footer` template is included that terminates the page.
   You also have to include this template for every page!

Now, we have finished the page to manage the people so that we can move on to the forms with which we actually create and edit the people.

### Person Add Form

Like the person list, the form to add new people requires a controller class and a template.

#### `PersonAddForm`

{% highlight php %}
{% include tutorial/tutorial-series/part-1/files/lib/acp/form/PersonAddForm.class.php %}
{% endhighlight %}

The properties here consist of two types:
the “housekeeping” properties `$activeMenuItem` and `$neededPermissions`, which fulfill the same roles as for `PersonListPage`, and the “data” properties `$firstName` and `$lastName`, which will contain the data entered by the user of the person to be created.

Now, let's go through each method in execution order:

1. `readFormParameters()` is called after the form has been submitted and reads the entered first and last name and sanitizes the values by calling `StringUtil::trim()`.
1. `validate()` is called after the form has been submitted and is used to validate the input data.
   In case of invalid data, the method is expected to throw a `UserInputException`.
   Here, the validation for first and last name is the same and quite basic:
   We check that any name has been entered and that it is not longer than the database table column permits.
1. `save()` is called after the form has been submitted and the entered data has been validated and it creates the new person via `PersonAction`.
   Please note that we do not just pass the first and last name to the action object but merge them with the `$this->additionalFields` array which can be used by event listeners of plugins to add additional data.
   After creating the object, the `saved()` method is called which fires an event for plugins and the data properties are cleared so that the input fields on the page are empty so that another new person can be created.
   Lastly, a `success` variable is assigned to the template which will show a message that the person has been successfully created.
1. `assignVariables()` assigns the values of the “data” properties to the template and additionally assigns an `action` variable.
   This `action` variable will be used in the template to distinguish between adding a new person and editing an existing person so that which minimal adjustments, we can use the template for both cases.

#### `personAdd.tpl`

{% highlight smarty %}
{% include tutorial/tutorial-series/part-1/acptemplates/personAdd.tpl %}
{% endhighlight %}

We will now only concentrate on the new parts compared to `personList.tpl`:

1. We use the `$action` variable to distinguish between the languages items used for adding a person and for creating a person.
1. Including the `formError` template automatically shows an error message if the validation failed.
1. The `.success` element is shown after successful saving the data and, again, shows different a text depending on the executed action.
1. The main part is the `form` element which has a common structure you will find in many forms in WoltLab Suite Core.
   The notable parts here are:
   - The `action` attribute of the `form` element is set depending on which controller will handle the request.
     In the link for the edit controller, we can now simply pass the edited `Person` object directly as the `Person` class implements the `IRouteController` interface.
   - The field that caused the validation error can be accessed via `$errorField`.
   - The type of the validation error can be accessed via `$errorType`.
     For an empty input field, we show the generic `wcf.global.form.error.empty` language item.
     In all other cases, we use the error type to determine the object- and property-specific language item to show.
     The approach used here allows plugins to easily add further validation error messages by simply using a different error type and providing the associated language item.
   - Input fields can be grouped into different `.section` elements.
     At the end of each `.section` element, there should be an template event whose name ends with `Fields`.
     The first part of the event name should reflect the type of fields in the particular `.section` element.
     Here, the input fields are just general “data” fields so that the event is called `dataFields`.
   - After the last `.section` element, fire a `section` event so that plugins can add further sections.
   - Lastly, the `.formSubmit` shows the submit button and `{@SECURITY_TOKEN_INPUT_TAG}` contains a CSRF token that is automatically validated after the form is submitted.

### Person Edit Form

As mentioned before, for the form to edit existing people, we only need a new controller as the template has already been implemented in a way that it handles both, adding and editing.

#### `PersonEditForm`

{% highlight php %}
{% include tutorial/tutorial-series/part-1/files/lib/acp/form/PersonEditForm.class.php %}
{% endhighlight %}

In general, edit forms extend the associated add form so that the code to read and to validate the input data is simply inherited.

After setting a different active menu item, we declare two new properties for the edited person:
the id of the person passed in the URL is stored in `$personID` and based on this ID, a `Person` object is created that is stored in the `$person` property.

Now let use go through the different methods in chronological order again:

1. `readParameters()` reads the passed ID of the edited person and creates a `Person` object based on this ID.
   If the ID is invalid, `$this->person->personID` is `null` and an `IllegalLinkException` is thrown.
1. `readData()` only executes additional code in the case if `$_POST` is empty, thus only for the initial request before the form has been submitted.
   The data properties of `PersonAddForm` are populated with the data of the edited person so that this data is shown in the form for the initial request.
1. `save()` handles saving the changed data.
   
   {% include callout.html content="Do not call `parent::save()` because that would cause `PersonAddForm::save()` to be executed and thus a new person would to be created! In order for the `save` event to be fired, call `AbstractForm::save()` instead!" type="warning" %}
   
   The only differences compared to `PersonAddForm::save()` are that we pass the edited object to the `PersonAction` constructor, execute the `update` action instead of the `create` action and do not clear the input fields after saving the changes.
1. In `assignVariables()`, we assign the edited `Person` object to the template, which is required to create the link in the form’s action property.
   Furthermore, we assign the template variable `$action` `edit` as value.
   
   {% include callout.html content="After calling `parent::assignVariables()`, the template variable `$action` actually has the value `add` so that here, we are overwriting this already assigned value." type="info" %}


## Frontend

For the front end, that means the part with which the visitors of a website interact, we want to implement a simple sortable page that lists the people.
This page should also be directly linked in the main menu.

### `page.xml`

First, let us register the page with the system because every front end page or form needs to be explicitly registered using the [page package installation plugin](package_pip_page.html):

{% highlight xml %}
{% include tutorial/tutorial-series/part-1/page.xml %}
{% endhighlight %}

For more information about what each of the elements means, please refer to the [page package installation plugin page](package_pip_page.html).

### `menuItem.xml`

Next, we register the menu item using the [menuItem package installation plugin](package_pip_menuItem.html):

{% highlight xml %}
{% include tutorial/tutorial-series/part-1/menuItem.xml %}
{% endhighlight %}

Here, the import parts are that we register the menu item for the main menu `com.woltlab.wcf.MainMenu` and link the menu item with the page `com.woltlab.wcf.people.PersonList`, which we just registered.

### People List

As in the ACP, we need a controller and a template.
You might notice that both the controller’s (unqualified) class name and the template name are the same for the ACP and the front end.
This is no problem because the qualified names of the classes differ and the files are stored in different directories and because the templates are installed by different package installation plugins and are also stored in different directories.

#### `PersonListPage`

{% highlight php %}
{% include tutorial/tutorial-series/part-1/files/lib/page/PersonListPage.class.php %}
{% endhighlight %}

This class is almost identical to the ACP version.
In the front end, we do not need to set the active menu item manually because the system determines the active menu item automatically based on the requested page.
Furthermore, `$neededPermissions` has not been set because in the front end, users do not need any special permission to access the page.
In the front end, we explicitly set the `$defaultSortField` so that the people listed on the page are sorted by their last name (in ascending order) by default.

#### `personList.tpl`

{% highlight smarty %}
{% include tutorial/tutorial-series/part-1/templates/personList.tpl %}
{% endhighlight %}

If you compare this template to the one used in the ACP, you will recognize similar elements like the `.paginationTop` element, the `p.info` element if no people exist, and the `.contentFooter` element.
Furthermore, we include a template called `header` before actually showing any of the page contents and terminate the template by including the `footer` template.

Now, let us take a closer look at the differences:

- We do not explicitly create a `.contentHeader` element but simply assign the title to the `contentTitle` variable.
  The value of the assignment is simply the title of the page and a badge showing the number of listed people.
  The `header` template that we include later will handle correctly displaying the content header on its own based on the `$contentTitle` variable.
- Next, we create additional element for the HTML document’s `<head>` element.
  In this case, we define the [canonical link of the page](https://en.wikipedia.org/wiki/Canonical_link_element) and, because we are showing paginated content, add links to the previous and next page (if they exist).
- We want the page to be sortable but as we will not be using a table for listing the people like in the ACP, we are not able to place links to sort the people into the table head.
  Instead, usually a box is created in the sidebar on the right-hand side that contains `select` elements to determine sort field and sort order.
- The main part of the page is the listing of the people.
  We use a structure similar to the one used for displaying registered users.
  Here, for each person, we simply display a FontAwesome icon representing a person and show the person’s full name relying on `Person::__toString()`.
  Additionally, like in the user list, we provide the initially empty `ul.inlineList.commaSeparated` and `dl.plain.inlineDataList.small` elements that can be filled by plugins using the templates events. 


## `userGroupOption.xml`

We have already used the `admin.content.canManagePeople` permissions several times, now we need to install it using the [userGroupOption package installation plugin](package_pip_user-group-option.html):

{% highlight xml %}
{% include tutorial/tutorial-series/part-1/userGroupOption.xml %}
{% endhighlight %}

We use the existing `admin.content` user group option category for the permission as the people are “content” (similar the the ACP menu item).
As the permission is for administrators only, we set `defaultvalue` to `0` and `admindefaultvalue` to `1`.
This permission is only relevant for registered users so that it should not be visible when editing the guest user group.
This is achieved by setting `usersonly` to `1`.


## `package.xml`

Lastly, we need to create the `package.xml` file.
For more information about this kind of file, please refer to [the `package.xml` page](package_package-xml.html).

{% highlight xml %}
{% include tutorial/tutorial-series/part-1/package.xml %}
{% endhighlight %}

As this is a package for WoltLab Suite Core 3, we need to require it using `<requiredpackage>`.
We require the latest version (when writing this tutorial) `3.0.0 RC 4`.
Additionally, we disallow installation of the package in the next major version `3.1` by excluding the `3.1.0 Alpha 1` version.
This ensures that if changes from WoltLab Suite Core 3.0 to 3.1 require changing some parts of the package, it will not break the instance in which the package is installed.

The most important part are to installation instructions.
First, we install the ACP templates, files and templates, create the database table and import the language item.
Afterwards, the ACP menu items and the permission are added.
Now comes the part of the instructions where the order of the instructions is crucial:
In `menuItem.xml`, we refer to the `com.woltlab.wcf.people.PersonList` page that is delivered by `page.xml`.
As the menu item package installation plugin validates the given page and throws an exception if the page does not exist, we need to install the page before the menu item! 

---

This concludes the first part of our tutorial series after which you now have a working simple package with which you can manage people in the ACP and show the visitors of your website a simple list of all created people in the front end.

The complete source code of this part can be found on [GitHub](https://github.com/WoltLab/woltlab.github.io/tree/master/_includes/tutorial/tutorial-series/part-1).
