<?php
/**
 * settings
 *
 * @package blog
 * @subpackage template
 * @author The myTravelbook Team <http://www.sourceforge.net/projects/mytravelbook>
 * @copyright Copyright (c) 2005-2006, myTravelbook Team
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License (GPL)
 * @version $Id$
 */
$errors = array();
$i18n = new MOD_i18n('apps/blog/settings.php');
$errors = $i18n->getText('i18n');
if (!$User = APP_User::login()) {
    echo '<p class="error">'.$errors['not_logged_in'].'</p>';
    return false;
}
?>
<div id="blog-settings">
</div>