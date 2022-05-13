# Migrating from WoltLab Suite 5.3 - Templates and Languages

## `{csrfToken}`

Going forward, any uses of the `SECURITY_TOKEN_*` constants should be avoided.
To reference the CSRF token (“Security Token”) within templates, the `{csrfToken}` template plugin was added.

Before:

```smarty
{@SECURITY_TOKEN_INPUT_TAG}
{link controller="Foo"}t={@SECURITY_TOKEN}{/link}
```

After:

```smarty
{csrfToken}
{link controller="Foo"}t={csrfToken type=url}{/link} {* The use of the CSRF token in URLs is discouraged.
                                                        Modifications should happen by means of a POST request. *}
```

The `{csrfToken}` plugin was backported to WoltLab Suite 5.2 and higher, allowing compatibility with a large range of WoltLab Suite branches.
See [WoltLab/WCF#3612](https://github.com/WoltLab/WCF/pull/3612) for details.


## RSS Feed Links

Prior to version 5.4 of WoltLab Suite, all RSS feed links contained the access token for logged-in users so that the feed shows all contents the specific user has access to.
With version 5.4, links with the CSS class `rssFeed` will open a dialog when clicked that offers the feed link with the access token for personal use and an anonymous feed link that can be shared with others.

```smarty
{* before *}
<li>
    <a rel="alternate" {*
        *}href="{if $__wcf->getUser()->userID}{link controller='ArticleFeed'}at={@$__wcf->getUser()->userID}-{@$__wcf->getUser()->accessToken}{/link}{else}{link controller='ArticleFeed'}{/link}{/if}" {*
        *}title="{lang}wcf.global.button.rss{/lang}" {*
        *}class="jsTooltip"{*
    *}>
        <span class="icon icon16 fa-rss"></span>
        <span class="invisible">{lang}wcf.global.button.rss{/lang}</span>
    </a>
</li>

{* after *}
<li>
    <a rel="alternate" {*
        *}href="{if $__wcf->getUser()->userID}{link controller='ArticleFeed'}at={@$__wcf->getUser()->userID}-{@$__wcf->getUser()->accessToken}{/link}{else}{link controller='ArticleFeed'}{/link}{/if}" {*
        *}title="{lang}wcf.global.button.rss{/lang}" {*
        *}class="rssFeed jsTooltip"{*
    *}>
        <span class="icon icon16 fa-rss"></span>
        <span class="invisible">{lang}wcf.global.button.rss{/lang}</span>
    </a>
</li>
```
