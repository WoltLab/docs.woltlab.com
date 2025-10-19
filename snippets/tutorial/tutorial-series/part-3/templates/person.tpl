{capture assign='pageTitle'}{$person} - {lang}wcf.person.list{/lang}{/capture}

{capture assign='contentTitle'}{$person}{/capture}

{include file='header'}

{if $commentsView}
	{unsafe:$commentsView->render()}
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
