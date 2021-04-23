# Tutorial Series Part 1: Base Structure

In the first part of this tutorial series, we will lay out what the basic version of package should be able to do and how to implement these functions.


## Package Functionality

The package should provide the following possibilities/functions:

- Sortable list of all people in the ACP
- Ability to add, edit and delete people in the ACP
- Restrict the ability to add, edit and delete people (in short: manage people) in the ACP
- Sortable list of all people in the front end


## Used Components

We will use the following package installation plugins:

- [acpTemplate package installation plugin](../../package/pip/acp-template.md),
- [acpMenu package installation plugin](../../package/pip/acp-menu.md),
- [database package installation plugin](../../package/pip/database.md),
- [file package installation plugin](../../package/pip/file.md),
- [language package installation plugin](../../package/pip/language.md),
- [menuItem package installation plugin](../../package/pip/menu-item.md),
- [page package installation plugin](../../package/pip/page.md),
- [template package installation plugin](../../package/pip/template.md),
- [userGroupOption package installation plugin](../../package/pip/user-group-option.md),

use [database objects](../../php/database-objects.md), create [pages](../../php/pages.md) and use [templates](../../view/templates.md).


## Package Structure

The package will have the following file structure:

```
├── acpMenu.xml
├── acptemplates
│   ├── personAdd.tpl
│   └── personList.tpl
├── files
│   ├── acp
│   │   └── database
│   │       └── install_com.woltlab.wcf.people.php
│   └── lib
│       ├── acp
│       │   ├── form
│       │   │   ├── PersonAddForm.class.php
│       │   │   └── PersonEditForm.class.php
│       │   └── page
│       │       └── PersonListPage.class.php
│       ├── data
│       │   └── person
│       │       ├── Person.class.php
│       │       ├── PersonAction.class.php
│       │       ├── PersonEditor.class.php
│       │       └── PersonList.class.php
│       └── page
│           └── PersonListPage.class.php
├── language
│   ├── de.xml
│   └── en.xml
├── menuItem.xml
├── package.xml
├── page.xml
├── templates
│   └── personList.tpl
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

The first file for our package is the `install_com.woltlab.wcf.people.php` file used to create such a database table during package installation:

```sql
--8<-- "tutorial/tutorial-series/part-1/files/acp/database/install_com.woltlab.wcf.people.php"
```

### Database Object

#### `Person`

In our PHP code, each person will be represented by an object of the following class:

```php
--8<-- "tutorial/tutorial-series/part-1/files/lib/data/person/Person.class.php"
```

The important thing here is that `Person` extends `DatabaseObject`.
Additionally, we implement the `IRouteController` interface, which allows us to use `Person` objects to create links, and we implement PHP's magic [__toString()](https://secure.php.net/manual/en/language.oop5.magic.php#object.tostring) method for convenience.

For every database object, you need to implement three additional classes:
an action class, an editor class and a list class.

#### `PersonAction`

```php
--8<-- "tutorial/tutorial-series/part-1/files/lib/data/person/PersonAction.class.php"
```

This implementation of `AbstractDatabaseObjectAction` is very basic and only sets the `$permissionsDelete` and `$requireACP` properties.
This is done so that later on, when implementing the people list for the ACP, we can delete people simply via AJAX.
`$permissionsDelete` has to be set to the permission needed in order to delete a person.
We will later use the [userGroupOption package installation plugin](../../package/pip/user-group-option.md) to create the `admin.content.canManagePeople` permission.
`$requireACP` restricts deletion of people to the ACP.

#### `PersonEditor`

```php
--8<-- "tutorial/tutorial-series/part-1/files/lib/data/person/PersonEditor.class.php"
```

This implementation of `DatabaseObjectEditor` fulfills the minimum requirement for a database object editor:
setting the static `$baseClass` property to the database object class name.

#### `PersonList`

```php
--8<-- "tutorial/tutorial-series/part-1/files/lib/data/person/PersonList.class.php"
```

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

```xml
--8<-- "tutorial/tutorial-series/part-1/acpMenu.xml"
```

We choose `wcf.acp.menu.link.content` as the parent menu item for the first menu item `wcf.acp.menu.link.person` because the people we are managing is just one form of content.
The fourth level menu item `wcf.acp.menu.link.person.add` will only be shown as an icon and thus needs an additional element `icon` which takes a FontAwesome icon class as value.

### People List

To list the people in the ACP, we need a `PersonListPage` class and a `personList` template.

#### `PersonListPage`

```php
--8<-- "tutorial/tutorial-series/part-1/files/lib/acp/page/PersonListPage.class.php"
```

As WoltLab Suite Core already provides a powerful default implementation of a sortable page, our work here is minimal:

1. We need to set the active ACP menu item via the `$activeMenuItem`.
1. `$neededPermissions` contains a list of permissions of which the user needs to have at least one in order to see the person list.
   We use the same permission for both the menu item and the page.
1. The database object list class whose name is provided via `$objectListClassName` and that handles fetching the people from database is the `PersonList` class, which we have already created.
1. To validate the sort field passed with the request, we set `$validSortFields` to the available database table columns.

#### `personList.tpl`

```smarty
--8<-- "tutorial/tutorial-series/part-1/acptemplates/personList.tpl"
```

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
1. The delete button for each person shown in the `.columnIcon` element relies on the global [`WoltLabSuite/Core/Ui/Object/Action`](../../migration/wsc53/javascript.md#wcfactiondelete-and-wcfactiontoggle) module which only requires the `jsObjectActionContainer` CSS class in combination with the `data-object-action-class-name` attribute for the `table` element, the `jsObjectActionObject` CSS class for each person's `tr` element in combination with the `data-object-id` attribute, and lastly the delete button itself, which is created with the [`objectAction` template plugin](../../view/template-plugins.md#view/template-plugins/#54-objectaction).
1. The [`.jsReloadPageWhenEmpty` CSS class](../../migration/wsc53/javascript.md#wcftableemptytablehandler) on the `tbody` element ensures that once all persons on the page have been deleted, the page is reloaded.
1. Lastly, the `footer` template is included that terminates the page.
   You also have to include this template for every page!

Now, we have finished the page to manage the people so that we can move on to the forms with which we actually create and edit the people.

### Person Add Form

Like the person list, the form to add new people requires a controller class and a template.

#### `PersonAddForm`

```php
--8<-- "tutorial/tutorial-series/part-1/files/lib/acp/form/PersonAddForm.class.php"
```

The properties here consist of three types:
the “housekeeping” properties `$activeMenuItem` and `$neededPermissions`, which fulfill the same roles as for `PersonListPage`, and the [`$objectEditLinkController` property](../../migration/wsc52/php.md#addform), which is used to generate a link to edit the newly created person after submitting the form, and finally `$formAction` and `$objectActionClass` required by the [PHP form builder API](../../php/api/form_builder/overview.md) used to generate the form.

Because of using form builder, we only have to set up the two form fields for entering the first and last name, respectively:

1. Each field is a simple single-line text field, thus we use [`TextFormField`](../../php/api/form_builder/form_fields.md#textformfield).
1. The parameter of the `create()` method expects the id of the field/name of the database object property, which is `firstName` and `lastName`, respectively, here.
1. The language item of the label shown in the ouput above the input field is set via the `label()` method.
1. As both fields have to be filled out, `required()` is called, and the maximum length is set via `maximumLength()`.
1. Lastly, to make it easier to fill out the form more quickly, the first field is auto-focused by calling `autoFocus()`.

#### `personAdd.tpl`

```smarty
--8<-- "tutorial/tutorial-series/part-1/acptemplates/personAdd.tpl"
```

We will now only concentrate on the new parts compared to `personList.tpl`:

1. We use the `$action` variable to distinguish between the languages items used for adding a person and for creating a person.
1. Because of form builder, we only have to call `{@$form->getHtml()}` to generate all relevant output for the form.

### Person Edit Form

As mentioned before, for the form to edit existing people, we only need a new controller as the template has already been implemented in a way that it handles both, adding and editing.

#### `PersonEditForm`

```php
--8<-- "tutorial/tutorial-series/part-1/files/lib/acp/form/PersonEditForm.class.php"
```

In general, edit forms extend the associated add form so that the code to read and to validate the input data is simply inherited.

After setting a different active menu item, we have to change the value of `$formAction` because this form, in contrast to `PersonAddForm`, does not create but update existing persons.

As we rely on form builder, the only thing necessary in this controller is to read and validate the edit object, i.e. the edited person, which is done in `readParameters()`.


## Frontend

For the front end, that means the part with which the visitors of a website interact, we want to implement a simple sortable page that lists the people.
This page should also be directly linked in the main menu.

### `page.xml`

First, let us register the page with the system because every front end page or form needs to be explicitly registered using the [page package installation plugin](../../package/pip/page.md):

```xml
--8<-- "tutorial/tutorial-series/part-1/page.xml"
```

For more information about what each of the elements means, please refer to the [page package installation plugin page](../../package/pip/page.md).

### `menuItem.xml`

Next, we register the menu item using the [menuItem package installation plugin](../../package/pip/menu-item.md):

```xml
--8<-- "tutorial/tutorial-series/part-1/menuItem.xml"
```

Here, the import parts are that we register the menu item for the main menu `com.woltlab.wcf.MainMenu` and link the menu item with the page `com.woltlab.wcf.people.PersonList`, which we just registered.

### People List

As in the ACP, we need a controller and a template.
You might notice that both the controller’s (unqualified) class name and the template name are the same for the ACP and the front end.
This is no problem because the qualified names of the classes differ and the files are stored in different directories and because the templates are installed by different package installation plugins and are also stored in different directories.

#### `PersonListPage`

```php
--8<-- "tutorial/tutorial-series/part-1/files/lib/page/PersonListPage.class.php"
```

This class is almost identical to the ACP version.
In the front end, we do not need to set the active menu item manually because the system determines the active menu item automatically based on the requested page.
Furthermore, `$neededPermissions` has not been set because in the front end, users do not need any special permission to access the page.
In the front end, we explicitly set the `$defaultSortField` so that the people listed on the page are sorted by their last name (in ascending order) by default.

#### `personList.tpl`

```smarty
--8<-- "tutorial/tutorial-series/part-1/templates/personList.tpl"
```

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

We have already used the `admin.content.canManagePeople` permissions several times, now we need to install it using the [userGroupOption package installation plugin](../../package/pip/user-group-option.md):

```xml
--8<-- "tutorial/tutorial-series/part-1/userGroupOption.xml"
```

We use the existing `admin.content` user group option category for the permission as the people are “content” (similar the the ACP menu item).
As the permission is for administrators only, we set `defaultvalue` to `0` and `admindefaultvalue` to `1`.
This permission is only relevant for registered users so that it should not be visible when editing the guest user group.
This is achieved by setting `usersonly` to `1`.


## `package.xml`

Lastly, we need to create the `package.xml` file.
For more information about this kind of file, please refer to [the `package.xml` page](../../package/package-xml.md).

```xml
--8<-- "tutorial/tutorial-series/part-1/package.xml"
```

As this is a package for WoltLab Suite Core 3, we need to require it using `<requiredpackage>`.
We require the latest version (when writing this tutorial) `5.4.0 Alpha 1`.
Additionally, we disallow installation of the package in the next major version `6.0` by excluding the `6.0.0 Alpha 1` version.

The most important part are to installation instructions.
First, we install the ACP templates, files and templates, create the database table and import the language item.
Afterwards, the ACP menu items and the permission are added.
Now comes the part of the instructions where the order of the instructions is crucial:
In `menuItem.xml`, we refer to the `com.woltlab.wcf.people.PersonList` page that is delivered by `page.xml`.
As the menu item package installation plugin validates the given page and throws an exception if the page does not exist, we need to install the page before the menu item! 

---

This concludes the first part of our tutorial series after which you now have a working simple package with which you can manage people in the ACP and show the visitors of your website a simple list of all created people in the front end.

The complete source code of this part can be found on [GitHub]({jinja{ config.repo_url }}tree/{jinja{ config.edit_uri.split("/")[1] }}/snippets/tutorial/tutorial-series/part-1).
