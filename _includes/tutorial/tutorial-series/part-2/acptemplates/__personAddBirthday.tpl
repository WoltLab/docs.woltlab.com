<dl{if $errorField == 'birthday'} class="formError"{/if}>
	<dt><label for="birthday">{lang}wcf.person.birthday{/lang}</label></dt>
	<dd>
		<input type="date" id="birthday" name="birthday" value="{$birthday}">
		{if $errorField == 'birthday'}
			<small class="innerError">
				{if $errorType == 'noValidSelection'}
					{lang}wcf.global.form.error.noValidSelection{/lang}
				{else}
					{lang}wcf.acp.person.birthday.error.{$errorType}{/lang}
				{/if}
			</small>
		{/if}
	</dd>
</dl>
