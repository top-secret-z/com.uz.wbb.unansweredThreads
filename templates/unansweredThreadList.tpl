{capture assign='pageTitle'}{$__wcf->getActivePage()->getTitle()}{if $pageNo > 1} - {lang}wcf.page.pageNo{/lang}{/if}{/capture}

{capture assign='contentTitle'}{$__wcf->getActivePage()->getTitle()} <span class="badge">{#$items}</span>{/capture}

{include file='header'}

<script data-relocate="true">
    // hide columnLastPost since there isn't any
    var x = document.getElementsByClassName("columnLastPost");
    var i;
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none"
    }
</script>

{hascontent}
    <div class="paginationTop">
        {content}{pages print=true assign=pagesLinks application='wbb' controller='UnansweredThreadList' link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}{/content}
    </div>
{/hascontent}

{if $objects|count}
    <div class="section tabularBox messageGroupList wbbThreadList">
        <ol class="tabularList">
            <li class="tabularListRow tabularListRowHead">
                <ol class="tabularListColumns">
                    <li class="columnSort">
                        <ul class="inlineList">
                            <li>
                                <a rel="nofollow" href="{link application='wbb' controller='UnansweredThreadList'}pageNo={@$pageNo}&sortField={$sortField}&sortOrder={if $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">
                                    <span class="icon icon16 fa-sort-amount-{$sortOrder|strtolower} jsTooltip" title="{lang}wbb.board.sortBy{/lang} ({lang}wcf.global.sortOrder.{if $sortOrder === 'ASC'}ascending{else}descending{/if}{/lang})"></span>
                                </a>
                            </li>
                            <li>
                                <div class="dropdown">
                                    <span class="dropdownToggle">{lang}wbb.thread.{$sortField}{/lang}</span>

                                    <ul class="dropdownMenu">
                                        {foreach from=$validSortFields item=_sortField}
                                            <li{if $_sortField === $sortField} class="active"{/if}><a rel="nofollow" href="{link application='wbb' controller='UnansweredThreadList'}pageNo={@$pageNo}&sortField={$_sortField}&sortOrder={if $sortField === $_sortField}{if $sortOrder === 'DESC'}ASC{else}DESC{/if}{else}{$sortOrder}{/if}{/link}">{lang}wbb.thread.{$_sortField}{/lang}</a></li>
                                        {/foreach}
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ol>
            </li>

            {include file='threadList' application='wbb' enableEditMode=false showBoardLink=true}
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
