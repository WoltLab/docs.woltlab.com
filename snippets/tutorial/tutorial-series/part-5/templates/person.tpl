{capture assign='pageTitle'}{$person} - {lang}wcf.person.list{/lang}{/capture}

{capture assign='contentTitle'}{$person}{/capture}

{include file='header'}

{if $person->informationCount || $__wcf->session->getPermission('user.person.canAddInformation')}
	<section class="section sectionContainerList">
		<header class="sectionHeader">
			<h2 class="sectionTitle">
				{lang}wcf.person.information.list{/lang}
				{if $person->informationCount}
					<span class="badge">{#$person->informationCount}</span>
				{/if}
			</h2>
		</header>
		
		<ul class="commentList containerList personInformationList jsObjectActionContainer" {*
			*}data-object-action-class-name="wcf\data\person\information\PersonInformationAction"{*
		*}>
			{if $__wcf->session->getPermission('user.person.canAddInformation')}
				<li class="containerListButtonGroup">
					<ul class="buttonGroup">
						<li>
							<a href="#" class="button" id="personInformationAddButton">
								<span class="icon icon16 fa-plus"></span>
								<span>{lang}wcf.person.information.add{/lang}</span>
							</a>
						</li>
					</ul>
				</li>
			{/if}
			
			{foreach from=$person->getInformation() item=$information}
				<li class="comment personInformation jsObjectActionObject" data-object-id="{@$information->getObjectID()}">
					<div class="box48{if $__wcf->getUserProfileHandler()->isIgnoredUser($information->userID)} ignoredUserContent{/if}">
						{user object=$information->getUserProfile() type='avatar48' ariaHidden='true' tabindex='-1'}
						
						<div class="commentContentContainer">
							<div class="commentContent">
								<div class="containerHeadline">
									<h3>
										{if $information->userID}
											{user object=$information->getUserProfile()}
										{else}
											<span>{$information->username}</span>
										{/if}
										
										<small class="separatorLeft">{@$information->time|time}</small>
									</h3>
								</div>
								
								<div class="htmlContent userMessage" id="personInformation{@$information->getObjectID()}">
									{@$information->getFormattedInformation()}
								</div>
								
								<nav class="jsMobileNavigation buttonGroupNavigation">
									<ul class="buttonList iconList">
										{if $information->canEdit()}
											<li class="jsOnly">
												<a href="#" title="{lang}wcf.global.button.edit{/lang}" class="jsEditInformation jsTooltip">
													<span class="icon icon16 fa-pencil"></span>
													<span class="invisible">{lang}wcf.global.button.edit{/lang}</span>
												</a>
											</li>
										{/if}
										{if $information->canDelete()}
											<li class="jsOnly">
												<a href="#" title="{lang}wcf.global.button.delete{/lang}" class="jsObjectAction jsTooltip" data-object-action="delete" data-confirm-message="{lang}wcf.person.information.delete.confirmMessage{/lang}">
													<span class="icon icon16 fa-times"></span>
													<span class="invisible">{lang}wcf.global.button.edit{/lang}</span>
												</a>
											</li>
										{/if}
										
										{event name='informationOptions'}
									</ul>
								</nav>
							</div>
						</div>
					</div>
				</li>
			{/foreach}
		</ul>
	</section>
{/if}

{if $person->enableComments}
	{if $commentList|count || $commentCanAdd}
		<section id="comments" class="section sectionContainerList">
			<header class="sectionHeader">
				<h2 class="sectionTitle">
					{lang}wcf.person.comments{/lang}
					{if $person->comments}<span class="badge">{#$person->comments}</span>{/if}
				</h2>
			</header>
			
			{include file='__commentJavaScript' commentContainerID='personCommentList'}
			
			<div class="personComments">
				<ul id="personCommentList" class="commentList containerList" {*
					*}data-can-add="{if $commentCanAdd}true{else}false{/if}" {*
					*}data-object-id="{@$person->personID}" {*
					*}data-object-type-id="{@$commentObjectTypeID}" {*
					*}data-comments="{if $person->comments}{@$commentList->countObjects()}{else}0{/if}" {*
					*}data-last-comment-time="{@$lastCommentTime}" {*
				*}>
					{include file='commentListAddComment' wysiwygSelector='personCommentListAddComment'}
					{include file='commentList'}
				</ul>
			</div>
		</section>
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

<script data-relocate="true">
	require(['Language', 'WoltLabSuite/Core/Controller/Person'], (Language, ControllerPerson) => {
		Language.addObject({
			'wcf.person.information.add': '{jslang}wcf.person.information.add{/jslang}',
			'wcf.person.information.add.success': '{jslang}wcf.person.information.add.success{/jslang}',
			'wcf.person.information.edit': '{jslang}wcf.person.information.edit{/jslang}',
			'wcf.person.information.edit.success': '{jslang}wcf.person.information.edit.success{/jslang}',
		});
		
		ControllerPerson.init({@$person->personID}, {
			canAddInformation: {if $__wcf->session->getPermission('user.person.canAddInformation')}true{else}false{/if},
		});
	});
</script>

{include file='footer'}
