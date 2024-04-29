<ul class="sidebarItemList">
    {foreach from=$boxPersonList item=boxPerson}
        <li class="box24">
            {icon name='user' size=24}
            
            <div class="sidebarItemTitle">
                <h3>{anchor object=$boxPerson}</h3>
                {capture assign='__boxPersonDescription'}{lang __optional=true}wcf.person.boxList.description.{$boxSortField}{/lang}{/capture}
                {if $__boxPersonDescription}
                    <small>{unsafe:$__boxPersonDescription}</small>
                {/if}
            </div>
        </li>
    {/foreach}
</ul>
