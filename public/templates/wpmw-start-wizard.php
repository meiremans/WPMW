<?php
/**
 * First wizard page
 */
?>
<span class="title" itemprop="name">AB-IT's mega cool site wizard</span>

<div id="wizard1">
    <form action="#" onsubmit="return wpmu_create_site(this);">
        Sitename: <input type="text" name="sitename"><br>
        <input name="action" value="'sitename-submission" type="hidden">
        <input type="submit" value="Submit">
    </form>
</div>

<div id="wizard2" class="hidden">
<span class="title" itemprop="name">What type of site?</span>

    <form action="" method="post">
        Type:
        <select name="siteType">
            <option value="business">Business</option>
            <option value="personal">Personal</option>
        </select>

        <input name="action" value="'site-type-submission" type="hidden">
        <input type="submit" value="Submit">

    </form>
</div>

<div id="wizard3" class="hidden">
    <span class="title" itemprop="name">Choose banner(don't worry you can upload your own picture later)</span>

    <form action="" method="post">


    </form>
</div>