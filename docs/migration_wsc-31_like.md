# Migrating from WSC 3.1 - Like System

## Introduction

With version 5.2 of WoltLab Suite Core the like system was completely replaced by the new reactions system. This makes it necessary to make some adjustments to existing code so that your plugin integrates completely into the new system. However, we have kept these adjustments as small as possible so that it is possible to use the reaction system with slight restrictions even without adjustments. 

## Limitations if no adjustments are made to the existing code

If no adjustments are made to the existing code, the following functions are not available: 
* Notifications about reactions/likes
* Recent Activity Events for reactions/likes

## Migration
### Notifications
#### Mark notification as compatible 
Since there are no more likes with the new version, it makes no sense to send notifications about it. Instead of notifications about likes, notifications about reactions are now sent. However, this only changes the notification text and not the notification itself. To update the notification, we first add the interface `\wcf\data\reaction\object\IReactionObject` to the `\wcf\data\like\object\ILikeObject` object (e.g. in WoltLab Suite Forum we added the interface to the class `\wbb\data\post\LikeablePost`). After that the object is marked as "compatible with WoltLab Suite Core 5.2" and notifications about reactions are sent again. 

#### Language Variables
Next, to display all reactions for the current notification in the notification text, we include the trait `\wcf\system\user\notification\event\TReactionUserNotificationEvent` in the user notification event class (typically named like `*LikeUserNotificationEvent`). These trait provides a new function that reads out and groups the reactions. The result of this function must now only be passed to the language variable. The name "reactions" is typically used as the variable name for the language variable. 

As a final step, we only need to change the language variables themselves. To ensure a consistent usability, the same formulations should be used as in the WoltLab Suite Core. 

##### English

`{prefix}.like.title`
```
Reaction to a {objectName}
```

`{prefix}.like.title.stacked`

```
{#$count} users reacted to your {objectName}
```

`{prefix}.like.message`
```
{@$author->getAnchorTag()} reacted to your {objectName} ({implode from=$reactions key=reactionID item=count}{@$__wcf->getReactionHandler()->getReactionTypeByID($reactionID)->renderIcon()}×{#$count}{/implode}).
```

`{prefix}.like.message.stacked`

```
{if $count < 4}{@$authors[0]->getAnchorTag()}{if $count == 2} and {else}, {/if}{@$authors[1]->getAnchorTag()}{if $count == 3} and {@$authors[2]->getAnchorTag()}{/if}{else}{@$authors[0]->getAnchorTag()} and {#$others} others{/if} reacted to your {objectName} ({implode from=$reactions key=reactionID item=count}{@$__wcf->getReactionHandler()->getReactionTypeByID($reactionID)->renderIcon()}×{#$count}{/implode}).
```

`wcf.user.notification.{objectTypeName}.like.notification.like`
```
Notify me when someone reacted to my {objectName}
```

##### German

`{prefix}.like.title`
```
Reaktion auf einen {objectName}
```

`{prefix}.like.title.stacked`

```
{#$count} Benutzern haben auf {if LANGUAGE_USE_INFORMAL_VARIANT}dein(en){else}Ihr(en){/if} {objectName} reagiert
```

`{prefix}.like.message`
```
{@$author->getAnchorTag()} hat auf {if LANGUAGE_USE_INFORMAL_VARIANT}dein(en){else}Ihr(en){/if} {objectName} reagiert ({implode from=$reactions key=reactionID item=count}{@$__wcf->getReactionHandler()->getReactionTypeByID($reactionID)->renderIcon()}×{#$count}{/implode}).
```

`{prefix}.like.message.stacked`

```
{if $count < 4}{@$authors[0]->getAnchorTag()}{if $count == 2} und {else}, {/if}{@$authors[1]->getAnchorTag()}{if $count == 3} und {@$authors[2]->getAnchorTag()}{/if}{else}{@$authors[0]->getAnchorTag()} und {#$others} weitere{/if} haben auf {if LANGUAGE_USE_INFORMAL_VARIANT}dein(en){else}Ihr(en){/if} {objectName} reagiert ({implode from=$reactions key=reactionID item=count}{@$__wcf->getReactionHandler()->getReactionTypeByID($reactionID)->renderIcon()}×{#$count}{/implode}).
```

`wcf.user.notification.{object_type_name}.like.notification.like`
```
Jemandem hat auf {if LANGUAGE_USE_INFORMAL_VARIANT}dein(en){else}Ihr(en){/if} {objectName} reagiert
```

### Recent Activity 

To adjust entries in the Recent Activity, only three small steps are necessary. First we pass the concrete reaction to the language variable, so that we can use the reaction object there. To do this, we add the following variable to the text of the `\wcf\system\user\activity\event\IUserActivityEvent` object: `$event->reactionType`. Typically we name the variable `reactionType`. In the second step, we mark the event as compatible. Therefore we set the parameter `supportsReactions` in the [`objectType.xml`](package_pip_object-type.md) to `1`. So for example the entry looks like this:
 
```xml
<type>
	<name>com.woltlab.example.likeableObject.recentActivityEvent</name>
	<definitionname>com.woltlab.wcf.user.recentActivityEvent</definitionname>
	<classname>wcf\system\user\activity\event\LikeableObjectUserActivityEvent</classname>
	<supportsReactions>1</supportsReactions>
</type>
```

Finally we modify our language variable. To ensure a consistent usability, the same formulations should be used as in the WoltLab Suite Core.

#### English
`wcf.user.recentActivity.{object_type_name}.recentActivityEvent`
```
Reaction ({objectName})
```

_Your language variable for the recent activity text_
```
Reacted with <span title="{$reactionType->getTitle()}" class="jsTooltip">{@$reactionType->renderIcon()}</span> to the {objectName}.
```

#### German
`wcf.user.recentActivity.{objectTypeName}.recentActivityEvent`
```
Reaktion ({objectName})
```

_Your language variable for the recent activity text_
```
Hat mit <span title="{$reactionType->getTitle()}" class="jsTooltip">{@$reactionType->renderIcon()}</span> auf {objectName} reagiert.
```

### Comments
If comments send notifications, they must also be updated. The language variables are changed in the same way as described in the section [Notifications / Language](migration_wsc-31_like.md#Language-Variables). After that comment must be marked as compatible. Therefore we set the parameter `supportsReactions` in the [`objectType.xml`](package_pip_object-type.md) to `1`. So for example the entry looks like this: 

```xml
<type>
	<name>com.woltlab.wcf.objectComment.response.like.notification</name>
	<definitionname>com.woltlab.wcf.notification.objectType</definitionname>
	<classname>wcf\system\user\notification\object\type\LikeUserNotificationObjectType</classname>
	<category>com.woltlab.example</category>
	<supportsReactions>1</supportsReactions>
</type>
```

## Forward Compatibility 

So that these changes also work in older versions of WoltLab Suite Core, the used classes and traits were backported with WoltLab Suite Core 3.0.22 and WoltLab Suite Core 3.1.10.
