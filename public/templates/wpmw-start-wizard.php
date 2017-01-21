<?php
/**
 * First wizard page
 */
?>
<span class="title" itemprop="name">AB-IT's mega cool site wizard</span>

<form action="#" onsubmit="return wpmu_wizard_step5(this);">
    <div id="wizard1">
        Sitename: <input type="text" name="sitename"><br>
        <input type="button" value="Submit" onClick="return wpmu_wizard_step2(this)">
    </div>

    <div id="wizard2" class="hidden">
        <span class="title" itemprop="name">What type of site?</span>

        Type:
        <select name="siteType">
            <option value="business">Business</option>
            <option value="personal">Personal</option>
        </select>

        <input type="button" value="Submit" onClick="return wpmu_wizard_step3(this)">


    </div>

    <div id="wizard3" class="hidden">
        <span class="title" itemprop="name">Choose banner(don't worry you can upload your own picture later)</span>

        <img src="<?php echo get_template_directory_uri(); ?>/img/headers/default.jpg" width="" height="" alt=""/>
        <img src="<?php echo get_template_directory_uri(); ?>/img/headers/fancybanner.png" width="" height="" alt=""/>
        <select name="bannerChoice">
            <option value="default.jpg">Default</option>
            <option value="fancybanner.png">Fancy banner</option>
        </select>
        <input type="button" value="Submit" onClick="return wpmu_wizard_step4(this)">
    </div>

    <div id="wizard4" class="hidden">
        <span class="title" itemprop="name">Insert your text for the homepage</span>
        <textarea rows="4" cols="50" name="pageText">
              Insert your text here
           </textarea>
        <input name="action" value="'site-type-submission" type="hidden">
        <input type="submit" value="Submit">

    </div>
</form>