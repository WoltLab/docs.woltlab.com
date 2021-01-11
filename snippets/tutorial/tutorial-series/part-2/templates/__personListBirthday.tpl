{if $person->birthday !== '0000-00-00'}
	<dt>{lang}wcf.person.birthday{/lang}</dt>
	<dd>{@$person->birthday|strtotime|date}</dd>
{/if}
