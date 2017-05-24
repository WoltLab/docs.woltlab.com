---
title: WCF 2.1.x - PHP
sidebar: sidebar
permalink: migration_wcf-21_php.html
folder: migration/wcf-21
---

## Message Processing

WoltLab Suite 3.0 finally made the transition from raw bbcode to bbcode-flavored HTML, with many new features related to message processing being added. This change impacts both message validation and storing, requiring slightly different APIs to get the job done.

### Input Processing for Storage

The returned HTML is an intermediate representation with a maximum of meta data embedded into it, designed to be stored in the database. Some bbcodes are replaced during this process, for example `[b]…[/b]` becomes `<strong>…</strong>`, while others are converted into a metacode tag for later processing.

```php
<?php
$processor = new \wcf\system\html\input\HtmlInputProcessor();
$processor->process($message, $messageObjectType, $messageObjectID);
$html = $processor->getHtml();
```

The `$messageObjectID` can be zero if the element did not exist before, but it should be non-zero when saving an edited message.

### Embedded Objects

Embedded objects need to be registered after saving the message, but once again you can use the processor instance to do the job.

```php
<?php
$processor = new \wcf\system\html\input\HtmlInputProcessor();
$processor->process($message, $messageObjectType, $messageObjectID);
$html = $processor->getHtml();

// at this point the message is saved to database and the created object
// `$example` is a `DatabaseObject` with the id column `$exampleID`

$processor->setObjectID($example->exampleID);
if (\wcf\system\message\embedded\object\MessageEmbeddedObjectManager::getInstance()->registerObjects($processor)) {
    // there is at least one embedded object, this is also the point at which you
    // would set `hasEmbeddedObjects` to true (if implemented by your type)
    (new \wcf\data\example\ExampleEditor($example))->update(['hasEmbeddedObjects' => 1]);
}
```

### Rendering the Message

The output processor will parse the intermediate HTML and finalize the output for display. This step is highly dynamic and allows for bbcode evaluation and contextual output based on the viewer's permissions.

```php
<?php
$processor = new \wcf\system\html\output\HtmlOutputProcessor();
$processor->process($html, $messageObjectType, $messageObjectID);
$renderedHtml = $processor->getHtml();
```

#### Simplified Output

At some point there can be the need of a simplified output HTML that includes only basic HTML formatting and reduces more sophisticated bbcodes into a simpler representation.

```php
<?php
$processor = new \wcf\system\html\output\HtmlOutputProcessor();
$processor->setOutputType('text/simplified-html');
$processor->process(…);
```

#### Plaintext Output

The `text/plain` output type will strip down the simplified HTML into pure text, suitable for text-only output such as the plaintext representation of an email.

```php
<?php
$processor = new \wcf\system\html\output\HtmlOutputProcessor();
$processor->setOutputType('text/plain');
$processor->process(…);
```

### Rebuilding Data

#### Converting from BBCode

{% include callout.html content="Enabling message conversion for HTML messages is undefined and yields unexpected results." type="warning" %}

Legacy message that still use raw bbcodes must be converted to be properly parsed by the html processors. This process is enabled by setting the fourth parameter of `process()` to `true`.

```php
<?php
$processor = new \wcf\system\html\input\HtmlInputProcessor();
$processor->process($html, $messageObjectType, $messageObjectID, true);
$renderedHtml = $processor->getHtml();
```

#### Extracting Embedded Objects

The `process()` method of the input processor is quite expensive, as it runs through the full message validation including the invocation of HTMLPurifier. This is perfectly fine when dealing with single messages, but when you're handling messages in bulk to extract their embedded objects, you're better of with `processEmbeddedContent()`. This method deconstructs the message, but skips all validation and expects the input to be perfectly valid, that is the output of a previous run of `process()` saved to storage.

```php
<?php
$processor = new \wcf\system\html\input\HtmlInputProcessor();
$processor->processEmbeddedContent($html, $messageObjectType, $messageObjectID);

// invoke `MessageEmbeddedObjectManager::registerObjects` here
```

## Breadcrumbs / Page Location

{% include callout.html content="Breadcrumbs used to be added left to right, but parent locations are added from the bottom to the top, starting with the first ancestor and going upwards. In most cases you simply need to reverse the order." type="warning" %}

Breadcrumbs used to be a lose collection of arbitrary links, but are now represented by actual page objects and the control has shifted over to the `PageLocationManager`.

```php
<?php
// before
\wcf\system\WCF::getBreadcrumbs()->add(new \wcf\system\breadcrumb\Breadcrumb('title', 'link'));

// after
\wcf\system\page\PageLocationManager::getInstance()->addParentLocation($pageIdentifier, $pageObjectID, $object);
```

## Pages and Forms

The property `$activeMenuItem` has been deprecated for the front end and is no longer evaluated at runtime. Recognition of the active item is entirely based around the invoked controller class name and its definition in the page table. You need to properly [register your pages](package_pip_page.html) for this feature to work.

## Search

### ISearchableObjectType

Added the `setLocation()` method that is used to set the current page location based on the search result.

### SearchIndexManager

The methods `SearchIndexManager::add()` and `SearchIndexManager::update()` have been deprecated and forward their call to the new method `SearchIndexManager::set()`.

{% include links.html %}
