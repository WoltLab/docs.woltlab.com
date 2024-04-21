The RSS feed API makes it possible to output content in the RSS format in accordance with the [RSS 2.0 specifications](https://www.rssboard.org/rss-specification).
The [ArticleRssFeedPage](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/page/ArticleRssFeedPage.class.php) is available as a reference implementation.

## `RssFeed`

[RssFeed](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/rssFeed/RssFeed.class.php) represents an RSS feed.

After content has been added to the RSS feed, the feed can be output as XML using the `render` method.

Example:

```php
$feed = new RssFeed();
...
$output = $feed->render();
```

## `RssFeedCategory`

[RssFeedCategory](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/rssFeed/RssFeedCategory.class.php) represents a category of a feed item. A feed item can have multiple categories.

An instance of `RssFeedCategory` is created implicitly when a category is assigned to a feed item.

Example:

```php
$item = new RssFeedItem();
$item->category('title', 'domain');
```

## `RssFeedChannel`

[RssFeedChannel](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/rssFeed/RssFeedChannel.class.php) represents a channel within an RSS feed.

Example:

```php
$feed = new RssFeed();
$channel = new RssFeedChannel();
$channel
    ->title('title')
    ->description('description')
    ->link('https://www.example.net');
$feed->channel($channel);
```

## `RssFeedEnclosure`

[RssFeedEnclosure](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/rssFeed/RssFeedEnclosure.class.php) represents an enclosure of a feed item. A feed item can only have one enclosure.

An instance of `RssFeedEnclosure` is created implicitly when an enclosure is assigned to a feed item.

Example:

```php
$item = new RssFeedItem();
$item->enclosure('url', /*size*/1024, 'mime/type');
```

## `RssFeedItem`

[RssFeedItem](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/rssFeed/RssFeedItem.class.php) represents an item in an RSS feed.

Example:

```php
$feed = new RssFeed();
$channel = new RssFeedChannel();
$feed->channel($channel);

$item = new RssFeedItem();
$item
    ->title('title')
    ->link('url');
$channel->item($item);
```

## `RssFeedSource`

[RssFeedSource](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/rssFeed/RssFeedSource.class.php) represents the source of a feed item. A feed item can only have one source.

An instance of `RssFeedSource` is created implicitly when an source is assigned to a feed item.

Example:

```php
$item = new RssFeedItem();
$item->source('title', 'url');
```
