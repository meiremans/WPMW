<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       ab-it.io
 * @since      1.0.0
 *
 * @package    Wpmw
 * @subpackage Wpmw/public/partials
 */
function form_creation(){
	?>
    <form>
        First name: <input type="text" name="firstname"><br>
        Last name: <input type="text" name="lastname"><br>
        Message: <textarea name="message"> Enter text here...</textarea>
    </form>]
	<?php
}
?>


<!-- This file should primarily consist of HTML with a little bit of PHP. -->
