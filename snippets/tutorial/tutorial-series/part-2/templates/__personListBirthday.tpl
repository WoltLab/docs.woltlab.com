{if $person->birthday}
	<dt>{lang}wcf.person.birthday{/lang}</dt>
	<dd>{@$person->birthday|strtotime|date}</dd>
{/if}
