{capture assign='contentTitle'}{lang}wcf.person.list{/lang} <span class="badge">{#$items}</span>{/capture}

{capture assign='headContent'}
	{if $pageNo < $pages}
		<link rel="next" href="{link controller='PersonList'}pageNo={@$pageNo+1}{/link}">
	{/if}
	{if $pageNo > 1}
		<link rel="prev" href="{link controller='PersonList'}{if $pageNo > 2}pageNo={@$pageNo-1}{/if}{/link}">
	{/if}
	<link rel="canonical" href="{link controller='PersonList'}{if $pageNo > 1}pageNo={@$pageNo}{/if}{/link}">
{/capture}

{capture assign='sidebarRight'}
	<section class="box">
		<form method="post" action="{link controller='PersonList'}{/link}">
			<h2 class="boxTitle">{lang}wcf.global.sorting{/lang}</h2>
			
			<div class="boxContent">
				<dl>
					<dt></dt>
					<dd>
						<select id="sortField" name="sortField">
							<option value="firstName"{if $sortField == 'firstName'} selected{/if}>{lang}wcf.person.firstName{/lang}</option>
							<option value="lastName"{if $sortField == 'lastName'} selected{/if}>{lang}wcf.person.lastName{/lang}</option>
							{event name='sortField'}
						</select>
						<select name="sortOrder">
							<option value="ASC"{if $sortOrder == 'ASC'} selected{/if}>{lang}wcf.global.sortOrder.ascending{/lang}</option>
							<option value="DESC"{if $sortOrder == 'DESC'} selected{/if}>{lang}wcf.global.sortOrder.descending{/lang}</option>
						</select>
					</dd>
				</dl>
				
				<div class="formSubmit">
					<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s">
				</div>
			</div>
		</form>
	</section>
{/capture}

{include file='header'}

{hascontent}
	<div class="paginationTop">
		{content}
			{pages print=true assign=pagesLinks controller='PersonList' link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}
		{/content}
	</div>
{/hascontent}

{if $items}
	<div class="section sectionContainerList">
		<ol class="containerList personList">
			{foreach from=$objects item=person}
				<li>
					<div class="box48">
						<span class="icon icon48 fa-user"></span>
						
						<div class="details personInformation">
							<div class="containerHeadline">
								<h3><a href="{$person->getLink()}">{$person}</a></h3>
							</div>
							
							{hascontent}
								<ul class="inlineList commaSeparated">
									{content}{event name='personData'}{/content}
								</ul>
							{/hascontent}
							
							{hascontent}
								<dl class="plain inlineDataList small">
									{content}
										{if $person->enableComments}
											<dt>{lang}wcf.person.comments{/lang}</dt>
											<dd>{#$person->comments}</dd>
										{/if}
										
										{event name='personStatistics'}
									{/content}
								</dl>
							{/hascontent}
						</div>
					</div>
				</li>
			{/foreach}
		</ol>
	</div>
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

<footer class="contentFooter">
	{hascontent}
		<div class="paginationBottom">
			{content}{@$pagesLinks}{/content}
		</div>
	{/hascontent}
	
	{hascontent}
		<nav class="contentFooterNavigation">
			<ul>
				{content}{event name='contentFooterNavigation'}{/content}
			</ul>
		</nav>
	{/hascontent}
</footer>

{include file='footer'}
