---
title: Media Provider Package Installation Plugin
sidebar: sidebar
permalink: package_pip_media-provider.html
folder: package/pip
parent: package_pip
---

{% include callout.html content="Available since WoltLab Suite 3.1" type="info" %}

Media providers are responsible to detect and convert links to a 3rd party service inside messages.

## Components

Each item is described as a `<provider>` element with the mandatory attribute `name` that should equal the lower-cased provider name. If a provider provides multiple components that are (largely) unrelated to each other, it is recommended to use a dash to separate the name and the component, e. g. `youtube-playlist`.

### `<title>`

The title is displayed in the administration control panel and is only used there, the value is neither localizable nor is it ever exposed to regular users.

### `<regex>`

The regular expression used to identify links to this provider, it must not contain anchors or delimiters. It is strongly recommended to capture the primary object id using the `(?P<ID>...)` group.

### `<className>`

{% include callout.html content="`<className>` and `<html>` are mutually exclusive." type="warning" %}

PHP-Callback-Class that is invoked to process the matched link in case that additional logic must be applied that cannot be handled through a simple replacement as defined by the `<html>` element.

The callback-class must implement the interface `\wcf\system\bbcode\media\provider\IBBCodeMediaProvider`.

### `<html>`

{% include callout.html content="`<className>` and `<html>` are mutually exclusive." type="warning" %}

Replacement HTML that gets populated using the captured matches in `<regex>`, variables are accessed as `{$VariableName}`. For example, the capture group `(?P<ID>...)` is accessed using `{$ID}`.

## Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/tornado/mediaProvider.xsd">
  <import>
    <provider name="youtube">
      <title>YouTube</title>
      <regex><![CDATA[https?://(?:.+?\.)?youtu(?:\.be/|be\.com/(?:#/)?watch\?(?:.*?&)?v=)(?P<ID>[a-zA-Z0-9_-]+)(?:(?:\?|&)t=(?P<start>[0-9hms]+)$)?]]></regex>
      <!-- advanced PHP callback -->
      <className><![CDATA[wcf\system\bbcode\media\provider\YouTubeBBCodeMediaProvider]]></className>
    </provider>

    <provider name="youtube-playlist">
      <title>YouTube Playlist</title>
      <regex><![CDATA[https?://(?:.+?\.)?youtu(?:\.be/|be\.com/)playlist\?(?:.*?&)?list=(?P<ID>[a-zA-Z0-9_-]+)]]></regex>
      <!-- uses a simple HTML replacement -->
      <html><![CDATA[<div class="videoContainer"><iframe src="https://www.youtube.com/embed/videoseries?list={$ID}" allowfullscreen></iframe></div>]]></html>
    </provider>
  </import>

  <delete>
    <provider identifier="example" />
  </delete>
</data>
```

{% include links.html %}
