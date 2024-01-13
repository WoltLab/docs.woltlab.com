{include file='header' pageTitle='wcf.acp.person.list'}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}wcf.acp.person.list{/lang}</h1>
	</div>
	
	<nav class="contentHeaderNavigation">
		<ul>
			<li><a href="{link controller='PersonAdd'}{/link}" class="button">{icon name='plus'} <span>{lang}wcf.acp.menu.link.person.add{/lang}</span></a></li>
			
			{event name='contentHeaderNavigation'}
		</ul>
	</nav>
</header>

{hascontent}
	<div class="paginationTop">
		{content}{pages print=true assign=pagesLinks controller="PersonList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}{/content}
	</div>
{/hascontent}

{if $objects|count}
	<div class="section tabularBox">
		<table class="table jsObjectActionContainer" data-object-action-class-name="wcf\data\person\PersonAction">
			<thead>
				<tr>
					<th class="columnID columnPersonID{if $sortField == 'personID'} active {@$sortOrder}{/if}" colspan="2"><a href="{link controller='PersonList'}pageNo={@$pageNo}&sortField=personID&sortOrder={if $sortField == 'personID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.global.objectID{/lang}</a></th>
					<th class="columnTitle columnFirstName{if $sortField == 'firstName'} active {@$sortOrder}{/if}"><a href="{link controller='PersonList'}pageNo={@$pageNo}&sortField=firstName&sortOrder={if $sortField == 'firstName' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.person.firstName{/lang}</a></th>
					<th class="columnTitle columnLastName{if $sortField == 'lastName'} active {@$sortOrder}{/if}"><a href="{link controller='PersonList'}pageNo={@$pageNo}&sortField=lastName&sortOrder={if $sortField == 'lastName' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.person.lastName{/lang}</a></th>
					
					{event name='columnHeads'}
				</tr>
			</thead>
			
			<tbody class="jsReloadPageWhenEmpty">
				{foreach from=$objects item=person}
					<tr class="jsObjectActionObject" data-object-id="{@$person->getObjectID()}">
						<td class="columnIcon">
							<a href="{link controller='PersonEdit' object=$person}{/link}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip">{icon name='pencil'}</a>
							{objectAction action="delete" objectTitle=$person->getTitle()}
							
							{event name='rowButtons'}
						</td>
						<td class="columnID">{#$person->personID}</td>
						<td class="columnTitle columnFirstName"><a href="{link controller='PersonEdit' object=$person}{/link}">{$person->firstName}</a></td>
						<td class="columnTitle columnLastName"><a href="{link controller='PersonEdit' object=$person}{/link}">{$person->lastName}</a></td>
						
						{event name='columns'}
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
	
	<footer class="contentFooter">
		{hascontent}
			<div class="paginationBottom">
				{content}{@$pagesLinks}{/content}
			</div>
		{/hascontent}
		
		<nav class="contentFooterNavigation">
			<ul>
				<li><a href="{link controller='PersonAdd'}{/link}" class="button">{icon name='plus'} <span>{lang}wcf.acp.menu.link.person.add{/lang}</span></a></li>
				
				{event name='contentFooterNavigation'}
			</ul>
		</nav>
	</footer>
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

{include file='footer'}
