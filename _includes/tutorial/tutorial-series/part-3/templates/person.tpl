{capture assign='pageTitle'}{$person} - {lang}wcf.person.list{/lang}{/capture}

{capture assign='contentTitle'}{$person}{/capture}

{include file='header'}

{if $person->enableComments}
	{if $commentList|count || $commentCanAdd}
		<header id="comments" class="section sectionContainerList">
			<h2 class="sectionTitle">{lang}wcf.person.comments{/lang}{if $person->comments} <span class="badge">{#$person->comments}</span>{/if}</h2>
		</header>
		
		{include file='__commentJavaScript' commentContainerID='personCommentList'}
		
		<ul id="personCommentList" class="commentList containerList"
			data-can-add="{if $commentCanAdd}true{else}false{/if}"
			data-object-id="{@$person->personID}"
			data-object-type-id="{@$commentObjectTypeID}"
			data-comments="{if $person->comments}{@$commentList->countObjects()}{else}0{/if}"
			data-last-comment-time="{@$lastCommentTime}"
		>
			{include file='commentList'}
		</ul>
	{/if}
{/if}

<footer class="contentFooter">
	{hascontent}
		<nav class="contentFooterNavigation">
			<ul>
				{content}{event name='contentFooterNavigation'}{/content}
			</ul>
		</nav>
	{/hascontent}
</footer>

{include file='footer'}
