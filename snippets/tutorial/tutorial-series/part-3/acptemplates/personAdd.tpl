{include file='header' pageTitle='wcf.acp.person.'|concat:$action}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}wcf.acp.person.{$action}{/lang}</h1>
	</div>
	
	<nav class="contentHeaderNavigation">
		<ul>
			<li><a href="{link controller='PersonList'}{/link}" class="button">{icon name='list'} <span>{lang}wcf.acp.menu.link.person.list{/lang}</span></a></li>
			
			{event name='contentHeaderNavigation'}
		</ul>
	</nav>
</header>

{@$form->getHtml()}

{include file='footer'}
