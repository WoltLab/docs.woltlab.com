# Part 4: Box and Box Conditions

In this part of our tutorial series, we add support for creating boxes listing people.

## Package Functionality

In addition to the existing functions from [part 3](part_3.md), the package will provide the following functionality after this part of the tutorial:

- Creating boxes dynamically listing people
- Filtering the people listed in boxes using conditions

## Used Components

In addition to the components used in previous parts, we will use the [`objectTypeDefinition` package installation plugin](../../package/pip/object-type-definition.md) and use the box and condition APIs.

To pre-install a specific person list box, we refer to the documentation of the [`box` package installation plugin](../../package/pip/box.md).


## Package Structure

The complete package will have the following file structure (_excluding_ unchanged files from [part 3](part_3.md)):

```
├── files
│   └── lib
│       └── system
│           ├── box
│           │   └── PersonListBoxController.class.php
│           └── condition
│               └── person
│                   ├── PersonFirstNameTextPropertyCondition.class.php
│                   └── PersonLastNameTextPropertyCondition.class.php
├── language
│   ├── de.xml
│   └── en.xml
├── objectType.xml
├── objectTypeDefinition.xml
└── templates
    └── boxPersonList.tpl
```

For all changes, please refer to the [source code on GitHub]({jinja{ config.repo_url }}tree/{jinja{ config.edit_uri.split("/")[1] }}/snippets/tutorial/tutorial-series/part-4).


## Box Controller

In addition to static boxes with fixed contents, administrators are able to create dynamic boxes with contents from the database.
In our case here, we want administrators to be able to create boxes listing people.
To do so, we first have to register a new object type for this person list box controller for the object type definition `com.woltlab.wcf.boxController`:

```xml
<type>
	<name>com.woltlab.wcf.personList</name>
	<definitionname>com.woltlab.wcf.boxController</definitionname>
	<classname>wcf\system\box\PersonListBoxController</classname>
</type>
```

The `com.woltlab.wcf.boxController` object type definition requires the provided class to implement `wcf\system\box\IBoxController`:

```php
--8<-- "tutorial/tutorial-series/part-4/files/lib/system/box/PersonListBoxController.class.php"
```

By extending `AbstractDatabaseObjectListBoxController`, we only have to provide minimal data ourself and rely mostly on the default implementation provided by `AbstractDatabaseObjectListBoxController`:

1. As we will support [conditions](#conditions) for the listed people, we have to set the relevant condition definition via `$conditionDefinition`.
2. `AbstractDatabaseObjectListBoxController` already supports restricting the number of listed objects.
   To do so, you only have to specify the default number of listed objects via `$defaultLimit`.
3. `AbstractDatabaseObjectListBoxController` also supports setting the sort order of the listed objects.
   You have to provide the supported sort fields via `$validSortFields` and specify the prefix used for the language items of the sort fields via `$sortFieldLanguageItemPrefix` so that for every `$validSortField` in `$validSortFields`, the language item `{$sortFieldLanguageItemPrefix}.{$validSortField}` must exist.
4. The box system supports [different positions](../../package/pip/box.md#position).
   Each box controller specifies the positions it supports via `$supportedPositions`.
   To keep the implementation simple here as different positions might require different output in the template, we restrict ourselves to sidebars.
5. `getObjectList()` returns an instance of `DatabaseObjectList` that is used to read the listed objects.
   `getObjectList()` itself must not call `readObjects()`, as `AbstractDatabaseObjectListBoxController` takes care of calling the method after adding the conditions and setting the sort order.
6. `getTemplate()` returns the contents of the box relying on the `boxPersonList` template here:
   ```smarty
   --8<-- "tutorial/tutorial-series/part-4/templates/boxPersonList.tpl"
   ```
   The template relies on a `.sidebarItemList` element, which is generally used for sidebar listings.
   (If different box positions were supported, we either have to generate different output by considering the value of `$boxPosition` in the template or by using different templates in `getTemplate()`.)
   One specific piece of code is the `$__boxPersonDescription` variable, which supports an optional description below the person's name relying on the optional language item `wcf.person.boxList.description.{$boxSortField}`.
   We only add one such language item when sorting the people by comments:
   In such a case, the number of comments will be shown.
   (When sorting by first and last name, there are no additional useful information that could be shown here, though the plugin from [part 2](part_2.md) adding support for birthdays might also show the birthday when sorting by first or last name.)
   
Lastly, we also provide the language item `wcf.acp.box.boxController.com.woltlab.wcf.personList`, which is used in the list of available box controllers.


## Conditions

The condition system can be used to generally filter a list of objects.
In our case, the box system supports conditions to filter the objects shown in a specific box.
Admittedly, our current person implementation only contains minimal data so that filtering might not make the most sense here but it will still show how to use the condition system for boxes.
We will support filtering the people by their first and last name so that, for example, a box can be created listing all people with a specific first name.

The first step for condition support is to register a object type definition for the relevant conditions requiring the `IObjectListCondition` interface:

```xml
--8<-- "tutorial/tutorial-series/part-4/objectTypeDefinition.xml"
```

Next, we register the specific conditions for filtering by the first and last name using this object type condition:

```xml
<type>
   <name>com.woltlab.wcf.people.firstName</name>
   <definitionname>com.woltlab.wcf.box.personList.condition</definitionname>
   <classname>wcf\system\condition\person\PersonFirstNameTextPropertyCondition</classname>
</type>
<type>
   <name>com.woltlab.wcf.people.lastName</name>
   <definitionname>com.woltlab.wcf.box.personList.condition</definitionname>
   <classname>wcf\system\condition\person\PersonLastNameTextPropertyCondition</classname>
</type>
```

`PersonFirstNameTextPropertyCondition` and `PersonLastNameTextPropertyCondition` only differ minimally so that we only focus on `PersonFirstNameTextPropertyCondition` here, which relies on the default implementation `AbstractObjectTextPropertyCondition` and only requires specifying different object properties:

```php
--8<-- "tutorial/tutorial-series/part-4/files/lib/system/condition/person/PersonFirstNameTextPropertyCondition.class.php"
```

1. `$className` contains the class name of the relevant database object from which the class name of the database object list is derived and `$propertyName` is the name of the database object's property that contains the value used for filtering.
1. By setting `$supportsMultipleValues` to `true`, multiple comma-separated values can be specified so that, for example, a box can also only list people with either of two specific first names.
1. `$description` (optional), `$fieldName`, and `$label` are used in the output of the form field.

(The implementation here is specific for `AbstractObjectTextPropertyCondition`.
The `wcf\system\condition` namespace also contains several other default condition implementations.)
