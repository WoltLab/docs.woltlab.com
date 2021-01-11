{include file='header' pageTitle='wcf.acp.person.'|concat:$action}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}wcf.acp.person.{$action}{/lang}</h1>
	</div>
	
	<nav class="contentHeaderNavigation">
		<ul>
			<li><a href="{link controller='PersonList'}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}wcf.acp.menu.link.person.list{/lang}</span></a></li>
			
			{event name='contentHeaderNavigation'}
		</ul>
	</nav>
</header>

{include file='formError'}

{if $success|isset}
	<p class="success">{lang}wcf.global.success.{$action}{/lang}</p>
{/if}

<form method="post" action="{if $action == 'add'}{link controller='PersonAdd'}{/link}{else}{link controller='PersonEdit' object=$person}{/link}{/if}">
	<div class="section">
		<dl{if $errorField == 'firstName'} class="formError"{/if}>
			<dt><label for="firstName">{lang}wcf.person.firstName{/lang}</label></dt>
			<dd>
				<input type="text" id="firstName" name="firstName" value="{$firstName}" required autofocus maxlength="255" class="long">
				{if $errorField == 'firstName'}
					<small class="innerError">
						{if $errorType == 'empty'}
							{lang}wcf.global.form.error.empty{/lang}
						{else}
							{lang}wcf.acp.person.firstName.error.{$errorType}{/lang}
						{/if}
					</small>
				{/if}
			</dd>
		</dl>
		
		<dl{if $errorField == 'lastName'} class="formError"{/if}>
			<dt><label for="lastName">{lang}wcf.person.lastName{/lang}</label></dt>
			<dd>
				<input type="text" id="lastName" name="lastName" value="{$lastName}" required maxlength="255" class="long">
				{if $errorField == 'lastName'}
					<small class="innerError">
						{if $errorType == 'empty'}
							{lang}wcf.global.form.error.empty{/lang}
						{else}
							{lang}wcf.acp.person.lastName.error.{$errorType}{/lang}
						{/if}
					</small>
				{/if}
			</dd>
		</dl>
		
		{event name='dataFields'}
	</div>
	
	{event name='sections'}
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s">
		{@SECURITY_TOKEN_INPUT_TAG}
	</div>
</form>

{include file='footer'}
