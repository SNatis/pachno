<?php

    use pachno\core\framework\Settings;

    /**
     * @var \pachno\core\framework\Response $pachno_response
     * @var \pachno\core\entities\User $pachno_user
     */

    $pachno_response->setTitle(__('Your account details'));
    $pachno_response->addBreadcrumb(__('Account details'), make_url('account'));

?>
<?php if ($pachno_user->canChangePassword()): ?>
    <div class="fullpage_backdrop" id="change_password_div" style="<?php if (!$has_autopassword) echo 'display: none;'; ?>">
        <div class="backdrop_box login_page login_popup">
            <div class="backdrop_detail_header">
                <span><?= __('Changing your password'); ?></span>
                <a href="javascript:void(0);" class="closer" onclick="$('change_password_div').toggle();"><?= fa_image_tag('times'); ?></a>
            </div>
            <form accept-charset="<?= \pachno\core\framework\Context::getI18n()->getCharset(); ?>" action="<?= make_url('account_change_password'); ?>" onsubmit="Pachno.Main.Profile.changePassword('<?= make_url('account_change_password'); ?>'); return false;" method="post" id="change_password_form">
                <div class="backdrop_detail_content login_content">
                    <div class="logindiv regular active" id="change_password_container">
                        <?php if (\pachno\core\framework\Settings::isUsingExternalAuthenticationBackend()): ?>
                            <?= \pachno\core\helpers\TextParser::parseText(\pachno\core\framework\Settings::get('changepw_message'), false, null, array('embedded' => true)); ?>
                        <?php else: ?>
                            <div class="article"><?= __('Enter your current password in the first box, then enter your new password twice (to prevent you from typing mistakes). Press the "%change_password" button to change your password.', array('%change_password' => __('Change password'))); ?></div>
                            <ul class="login_formlist">
                                <?php if (!$has_autopassword): ?>
                                    <li>
                                        <label for="current_password"><?= __('Current password'); ?></label>
                                        <input type="password" name="current_password" id="current_password" value="">
                                    </li>
                                <?php else: ?>
                                    <li style="display: none;">
                                        <input type="hidden" name="current_password" id="current_password" value="<?= $autopassword; ?>">
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <label for="new_password_1"><?= __('New password'); ?></label>
                                    <input type="password" name="new_password_1" id="new_password_1" value="">
                                </li>
                                <li>
                                    <label for="new_password_2"><?= __('New password (repeat it)'); ?></label>
                                    <input type="password" name="new_password_2" id="new_password_2" value="">
                                </li>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (!\pachno\core\framework\Settings::isUsingExternalAuthenticationBackend()): ?>
                    <div class="backdrop_details_submit">
                        <span class="explanation"></span>
                        <div class="submit_container">
                            <button type="submit" class="button"><?= image_tag('spinning_20.gif', array('id' => 'change_password_indicator', 'style' => 'display: none;')) . __('Change password'); ?></button>
                        </div>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
<?php endif; ?>
<?php if ($pachno_user->isOpenIdLocked()): ?>
    <div class="fullpage_backdrop" id="pick_username_div" style="display: none;">
        <form accept-charset="<?= \pachno\core\framework\Context::getI18n()->getCharset(); ?>" action="<?= make_url('account_check_username'); ?>" onsubmit="Pachno.Main.Profile.checkUsernameAvailability('<?= make_url('account_check_username'); ?>'); return false;" method="post" id="check_username_form">
            <div class="backdrop_box login_page login_popup">
                <div class="backdrop_detail_header">
                    <span><?= __('Picking a username'); ?></span>
                    <a href="javascript:void(0);" class="closer" onclick="$('pick_username_div').toggle();"><?= fa_image_tag('times'); ?></a>
                </div>
                <div class="backdrop_detail_content login_content">
                    <div class="logindiv regular active" id="add_application_password_container">
                        <div class="article">
                            <p><?= __('Since this account was created via an OpenID login, you will have to pick a username to be able to log in with a username or password. You can continue to use your account with your OpenID login, so this is only if you want to pick a username for your account.'); ?><p>
                        </div>
                        <ul class="account_popupform">
                            <li>
                                <label for="username_pick"><?= __('Type desired username'); ?></label>
                                <input type="text" name="desired_username" id="username_pick">
                            </li>
                            <li id="username_unavailable" style="display: none;">
                                <?= __('This username is not available'); ?>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="backdrop_details_submit">
                    <span class="explanation"><?= __('Click "%check_availability" to see if your desired username is available.', array('%check_availability' => __('Check availability'))); ?></span>
                    <div class="submit_container">
                        <button type="submit" class="button"><?= image_tag('spinning_20.gif', array('id' => 'pick_username_indicator', 'style' => 'display: none;')) . __('Check availability'); ?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php endif; ?>
<div class="fullpage_backdrop" id="add_application_password_div" style="display: none;">
    <div class="backdrop_box login_page login_popup">
        <form accept-charset="<?= \pachno\core\framework\Context::getI18n()->getCharset(); ?>" action="<?= make_url('account_add_application_password'); ?>" onsubmit="Pachno.Main.Profile.addApplicationPassword('<?= make_url('account_add_application_password'); ?>'); return false;" method="post" id="add_application_password_form">
            <div id="add_application_password_container">
                <div class="backdrop_detail_header">
                    <span><?= __('Add application-specific password'); ?></span>
                    <a href="javascript:void(0);" class="closer" onclick="$('add_application_password_div').toggle();"><?= fa_image_tag('times'); ?></a>
                </div>
                <div class="backdrop_detail_content login_content">
                    <div class="logindiv regular active">
                        <div class="article"><?= __('Please enter the name of the application or computer which will be using this password. Examples include "Toms computer", "Work laptop", "My iPhone" and similar.'); ?></div>
                        <ul class="account_popupform">
                            <li>
                                <label for="add_application_password_name"><?= __('Application name'); ?></label>
                                <input type="text" name="name" id="add_application_password_name" value="">
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="backdrop_details_submit">
                    <span class="explanation"></span>
                    <div class="submit_container">
                        <button type="submit" class="button"><?= image_tag('spinning_20.gif', array('id' => 'add_application_password_indicator', 'style' => 'display: none;')) . __('Add application password'); ?></button>
                    </div>
                </div>
            </div>
            <div id="add_application_password_response" style="display: none;">
                <div class="backdrop_detail_header">
                    <span><?= __('Application password generated'); ?></span>
                </div>
                <div class="backdrop_detail_content login_content">
                    <div class="article"><?= __("Use this one-time password when authenticating with the application. Spaces don't matter, and you don't have to write it down."); ?></div>
                    <div class="application_password_preview" id="application_password_preview"></div>
                </div>
                <div class="backdrop_details_submit">
                    <span class="explanation"></span>
                    <div class="submit_container">
                        <a href="<?= make_url('account'); ?>" class="button"><?= __('Done'); ?></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="account_info_container">
    <div id="account_user_info">
        <?= image_tag($pachno_user->getAvatarURL(false), array('style' => 'float: left; margin-right: 5px;', 'alt' => '[avatar]'), true); ?>
        <span id="user_name_span">
            <?= $pachno_user->getRealname(); ?><br>
            <?php if (!$pachno_user->isOpenIdLocked()): ?>
                @<?= $pachno_user->getUsername(); ?>
            <?php endif; ?>
        </span>
    </div>
    <div id="account_details_container">
        <div id="account_tabs" class="fancy-tabs">
            <a class="tab <?php if ($selected_tab == 'profile'): ?> selected<?php endif; ?>" id="tab_profile" onclick="Pachno.Main.Helpers.tabSwitcher('tab_profile', 'account_tabs', true);" href="javascript:void(0);">
                <?= fa_image_tag('edit', ['class' => 'icon']); ?>
                <span class="name"><?= __('Profile'); ?></span>
            </a>
            <a class="tab" id="tab_settings" onclick="Pachno.Main.Helpers.tabSwitcher('tab_settings', 'account_tabs', true);" href="javascript:void(0);">
                <?= fa_image_tag('cog', ['class' => 'icon']); ?>
                <span class="name"><?= __('Settings'); ?></span>
            </a>
            <a class="tab" id="tab_notificationsettings" onclick="Pachno.Main.Helpers.tabSwitcher('tab_notificationsettings', 'account_tabs', true);" href="javascript:void(0);">
                <?= fa_image_tag('bell', ['class' => 'icon']); ?>
                <span class="name"><?= __('Notification settings'); ?></span>
            </a>
            <?php \pachno\core\framework\Event::createNew('core', 'account_tabs')->trigger(); ?>
            <?php foreach (\pachno\core\framework\Context::getAllModules() as $modules): ?>
                <?php foreach ($modules as $module_name => $module): ?>
                    <?php if ($module->hasAccountSettings()): ?>
                        <a class="tab" id="tab_settings_<?= $module_name; ?>" onclick="Pachno.Main.Helpers.tabSwitcher('tab_settings_<?= $module_name; ?>', 'account_tabs', true);" href="javascript:void(0);">
                            <?= fa_image_tag($module->getAccountSettingsLogo(), ['class' => 'icon']); ?>
                            <span class="name"><?= __($module->getAccountSettingsName()); ?></span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <a class="tab <?php if ($selected_tab == 'security'): ?> selected<?php endif; ?>" id="tab_security" onclick="Pachno.Main.Helpers.tabSwitcher('tab_security', 'account_tabs', true);" href="javascript:void(0);">
                <?= fa_image_tag('lock', ['class' => 'icon']); ?>
                <span class="name"><?= __('Security'); ?></span>
            </a>
            <?php if (count($pachno_user->getScopes()) > 1): ?>
                <a class="tab" id="tab_scopes" onclick="Pachno.Main.Helpers.tabSwitcher('tab_scopes', 'account_tabs', true);" href="javascript:void(0);">
                    <?= fa_image_tag('clone', ['class' => 'icon']); ?>
                    <span class="name"><?= __('Scope memberships'); ?></span>
                </a>
            <?php endif; ?>
        </div>
        <div id="account_tabs_panes">
            <div id="tab_profile_pane" style="<?php if ($selected_tab != 'profile'): ?> display: none;<?php endif; ?>">
                <?php if (\pachno\core\framework\Settings::isUsingExternalAuthenticationBackend()): ?>
                    <?= \pachno\core\helpers\TextParser::parseText(\pachno\core\framework\Settings::get('changedetails_message'), false, null, array('embedded' => true)); ?>
                <?php else: ?>
                    <form accept-charset="<?= \pachno\core\framework\Context::getI18n()->getCharset(); ?>" action="<?= make_url('account_save_information'); ?>" onsubmit="Pachno.Main.Profile.updateInformation('<?= make_url('account_save_information'); ?>'); return false;" method="post" id="profile_information_form">
                        <h3><?= __('About yourself'); ?></h3>
                        <p><?= __('Edit your profile details here, including additional information (Required fields are marked with a little star). Keep in mind that some of this information may be seen by other users.'); ?></p>
                        <table class="padded_table" cellpadding=0 cellspacing=0>
                            <tr>
                                <td style="width: 300px;"><label for="profile_buddyname">* <?= __('Display name'); ?></label></td>
                                <td>
                                    <input type="text" name="buddyname" id="profile_buddyname" value="<?= $pachno_user->getBuddyname(); ?>" style="width: 200px;">
                                </td>
                            </tr>
                            <tr>
                                <td class="config-explanation" colspan="2"><?= __('This name is what other people will see you as.'); ?></td>
                            </tr>
                            <tr>
                                <td ><label for="profile_email">* <?= __('Email address'); ?></label></td>
                                <td>
                                    <input type="email" name="email" id="profile_email" value="<?= $pachno_user->getEmail(); ?>" style="width: 300px;">
                                </td>
                            </tr>
                            <tr>
                                <td ><label for="profile_email_private_yes">* <?= __('Show my email address to others'); ?></label></td>
                                <td>
                                    <input type="radio" name="email_private" value="0" id="profile_email_private_no"<?php if ($pachno_user->isEmailPublic()): ?> checked<?php endif; ?>>&nbsp;<label for="profile_email_private_no"><?= __('Yes'); ?></label>&nbsp;&nbsp;
                                    <input type="radio" name="email_private" value="1" id="profile_email_private_yes"<?php if ($pachno_user->isEmailPrivate()): ?> checked<?php endif; ?>>&nbsp;<label for="profile_email_private_yes"><?= __('No'); ?></label>
                                </td>
                            </tr>
                            <tr>
                                <td class="config-explanation" colspan="2"><?= __('Whether your email address is visible to other users in your profile information card. The email address is always visible to admins.'); ?></td>
                            </tr>
                            <tr>
                                <td ><label for="profile_use_gravatar_yes"><?= __('Use Gravatar avatar'); ?></label></td>
                                <td>
                                    <input type="radio" name="use_gravatar" value="1" id="profile_use_gravatar_yes"<?php if ($pachno_user->usesGravatar()): ?> checked<?php endif; ?>>&nbsp;<label for="profile_use_gravatar_yes"><?= __('Yes'); ?></label>&nbsp;&nbsp;
                                    <input type="radio" name="use_gravatar" value="0" id="profile_use_gravatar_no"<?php if (!$pachno_user->usesGravatar()): ?> checked<?php endif; ?>>&nbsp;<label for="profile_use_gravatar_no"><?= __('No'); ?></label>
                                </td>
                            </tr>
                            <tr>
                                <td class="config-explanation" colspan="2">
                                    <?= __("Pachno can use your %link_to_gravatar profile picture, if you have one. If you don't have one but still want to use Gravatar for profile pictures, Pachno will use a Gravatar %auto_generated_image_unique_for_your_email_address. Don't have a Gravatar yet? %link_to_get_one_now",
                                                    array('%link_to_gravatar' => link_tag('http://www.gravatar.com', 'Gravatar', ['target' => '_blank']),
                                                        '%auto_generated_image_unique_for_your_email_address' => link_tag('http://blog.gravatar.com/2008/04/22/identicons-monsterids-and-wavatars-oh-my', __('auto-generated image unique for your email address'), ['target' => '_blank']),
                                                        '%link_to_get_one_now' => link_tag('http://en.gravatar.com/site/signup/'.urlencode($pachno_user->getEmail()), __('Get one now!'), array('target' => '_blank')))); ?>
                                    <br>
                                    <a style="<?php if (!$pachno_user->usesGravatar()): ?>display: none; <?php endif; ?>" id="gravatar_change" href="http://en.gravatar.com/emails/" class="button">
                                        <?= image_tag('gravatar.png'); ?>
                                        <?= __('Change my profile picture / avatar'); ?>
                                    </a>
                                </td>
                            </tr>
                        </table>
                        <h3><?= __('Language and location'); ?></h3>
                        <p><?= __('This information is used to provide a more localized experience based on your location and language preferences. Items such as timestamps will be displayed in your local timezone, and you can choose to use Pachno in your own language.'); ?></p>
                        <table class="padded_table" cellpadding=0 cellspacing=0>
                            <tr>
                                <td style="width: 300px;"><label for="profile_timezone"><?= __('Current timezone'); ?></label></td>
                                <td>
                                    <select name="timezone" id="profile_timezone" style="width: 300px;">
                                        <option value="sys"<?php if (in_array($pachno_user->getTimezoneIdentifier(), array('sys', null))): ?> selected<?php endif; ?>><?= __('Use server timezone'); ?></option>
                                        <?php foreach ($timezones as $timezone => $description): ?>
                                            <option value="<?= $timezone; ?>"<?php if ($pachno_user->getTimezoneIdentifier() == $timezone): ?> selected<?php endif; ?>><?= $description; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="config-explanation" colspan="2">
                                    <?= __('Based on this information, the time at your location should be: %time', array('%time' => \pachno\core\framework\Context::getI18n()->formatTime(time(), 1))); ?>
                                </td>
                            </tr>
                            <tr>
                                <td ><label for="profile_timezone"><?= __('Language'); ?></label></td>
                                <td>
                                    <select name="profile_language" id="profile_language" style="width: 300px;">
                                        <option value="sys"<?php if ($pachno_user->getLanguage() == 'sys'): ?> selected<?php endif; ?>><?= __('Use global setting - %lang', array('%lang' => \pachno\core\framework\Settings::getLanguage())); ?></option>
                                    <?php foreach ($languages as $lang_code => $lang_desc): ?>
                                        <option value="<?= $lang_code; ?>" <?php if ($pachno_user->getLanguage() == $lang_code): ?> selected<?php endif; ?>><?= $lang_desc; ?><?php if (\pachno\core\framework\Settings::getLanguage() == $lang_code): ?> <?= __('(site default)'); endif;?></option>
                                    <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <h3><?= __('Additional information'); ?></h3>
                        <p><?= __('You may want to provide more information about yourself here. This is completely optional, and only used to show more information about yourself to other users.'); ?></p>
                        <table class="padded_table" cellpadding=0 cellspacing=0>
                            <tr>
                                <td style="width: 200px;"><label for="profile_realname"><?= __('Full name'); ?></label></td>
                                <td>
                                    <input type="text" name="realname" id="profile_realname" value="<?= $pachno_user->getRealname(); ?>" style="width: 300px;">
                                </td>
                            </tr>
                            <tr>
                                <td ><label for="profile_homepage"><?= __('Homepage'); ?></label></td>
                                <td>
                                    <input type="url" name="homepage" id="profile_homepage" value="<?= $pachno_user->getHomepage(); ?>" style="width: 300px;">
                                </td>
                            </tr>
                        </table>
                        <div class="save-button-container">
                            <div class="message"><?= __('Click "%save" to save your account information', array('%save' => __('Save'))); ?></div>
                            <span id="profile_save_indicator" style="display: none;"><?= image_tag('spinning_20.gif'); ?></span>
                            <input type="submit" id="submit_information_button" value="<?= __('Save'); ?>">
                        </div>
                    </form>
                <?php endif; ?>
            </div>
            <div id="tab_settings_pane" style="display: none;">
                <form accept-charset="<?= \pachno\core\framework\Context::getI18n()->getCharset(); ?>" action="<?= make_url('account_save_settings'); ?>" onsubmit="Pachno.Main.Profile.updateSettings('<?= make_url('account_save_settings'); ?>'); return false;" method="post" id="profile_settings_form">
                    <h3><?= __('Navigation'); ?></h3>
                    <p><?= __('These settings apply to all areas of Pachno, and lets you customize your experience to fit your own style.'); ?></p>
                    <table class="padded_table" cellpadding=0 cellspacing=0>
                        <tr>
                            <td style="width: 200px;"><label for="profile_enable_keyboard_navigation_yes"><?= __('Enable keyboard navigation'); ?></label></td>
                            <td>
                                <input type="radio" name="enable_keyboard_navigation" value="1" id="profile_enable_keyboard_navigation_yes"<?php if ($pachno_user->isKeyboardNavigationEnabled()): ?> checked<?php endif; ?>>&nbsp;<label for="profile_use_gravatar_yes"><?= __('Yes'); ?></label>&nbsp;&nbsp;
                                <input type="radio" name="enable_keyboard_navigation" value="0" id="profile_enable_keyboard_navigation_no"<?php if (!$pachno_user->isKeyboardNavigationEnabled()): ?> checked<?php endif; ?>>&nbsp;<label for="profile_use_gravatar_no"><?= __('No'); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td class="config-explanation" colspan="2">
                                <?= __('Lets you use arrow up / down in issue lists to navigate'); ?><br>
                            </td>
                        </tr>
                    </table>
                    <h3><?= __('Editing'); ?></h3>
                    <p><?= __('The settings you select here will be used as the default formatting syntax for comments you post, issues you create and articles you write. Remember that you can switch this on a case by case basis - look for the syntax selector next to any text area with formatting buttons.'); ?></p>
                    <table class="padded_table" cellpadding=0 cellspacing=0>
                        <tr>
                            <td colspan="2">
                                <table class="profile_syntax_table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><?= __('Mediawiki'); ?></th>
                                            <th><?= __('Markdown'); ?></th>
                                            <th><?= __('Plain text'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><label for="syntax_issues_md"><?= __('Preferred syntax when creating issues'); ?></label></td>
                                            <td><input type="radio" name="syntax_issues" value="<?= \pachno\core\framework\Settings::SYNTAX_MW; ?>" id="syntax_issues_mw" <?php if ($pachno_user->getPreferredIssuesSyntax(true) == \pachno\core\framework\Settings::SYNTAX_MW) echo 'checked'; ?>></td>
                                            <td><input type="radio" name="syntax_issues" value="<?= \pachno\core\framework\Settings::SYNTAX_MD; ?>" id="syntax_issues_md" <?php if ($pachno_user->getPreferredIssuesSyntax(true) == \pachno\core\framework\Settings::SYNTAX_MD) echo 'checked'; ?>></td>
                                            <td><input type="radio" name="syntax_issues" value="<?= \pachno\core\framework\Settings::SYNTAX_PT; ?>" id="syntax_issues_pt" <?php if ($pachno_user->getPreferredIssuesSyntax(true) == \pachno\core\framework\Settings::SYNTAX_PT) echo 'checked'; ?>></td>
                                        </tr>
                                        <tr>
                                            <td><label for="syntax_articles_mw"><?= __('Preferred syntax when creating articles'); ?></label></td>
                                            <td><input type="radio" name="syntax_articles" value="<?= \pachno\core\framework\Settings::SYNTAX_MW; ?>" id="syntax_articles_mw" <?php if ($pachno_user->getPreferredWikiSyntax(true) == \pachno\core\framework\Settings::SYNTAX_MW) echo 'checked'; ?>></td>
                                            <td><input type="radio" name="syntax_articles" value="<?= \pachno\core\framework\Settings::SYNTAX_MD; ?>" id="syntax_articles_md" <?php if ($pachno_user->getPreferredWikiSyntax(true) == \pachno\core\framework\Settings::SYNTAX_MD) echo 'checked'; ?>></td>
                                            <td><input type="radio" name="syntax_articles" value="<?= \pachno\core\framework\Settings::SYNTAX_PT; ?>" id="syntax_articles_pt" <?php if ($pachno_user->getPreferredWikiSyntax(true) == \pachno\core\framework\Settings::SYNTAX_PT) echo 'checked'; ?>></td>
                                        </tr>
                                        <tr>
                                            <td><label for="syntax_comments_md"><?= __('Preferred syntax when posting comments'); ?></label></td>
                                            <td><input type="radio" name="syntax_comments" value="<?= \pachno\core\framework\Settings::SYNTAX_MW; ?>" id="syntax_comments_mw" <?php if ($pachno_user->getPreferredCommentsSyntax(true) == \pachno\core\framework\Settings::SYNTAX_MW) echo 'checked'; ?>></td>
                                            <td><input type="radio" name="syntax_comments" value="<?= \pachno\core\framework\Settings::SYNTAX_MD; ?>" id="syntax_comments_md" <?php if ($pachno_user->getPreferredCommentsSyntax(true) == \pachno\core\framework\Settings::SYNTAX_MD) echo 'checked'; ?>></td>
                                            <td><input type="radio" name="syntax_comments" value="<?= \pachno\core\framework\Settings::SYNTAX_PT; ?>" id="syntax_comments_pt" <?php if ($pachno_user->getPreferredCommentsSyntax(true) == \pachno\core\framework\Settings::SYNTAX_PT) echo 'checked'; ?>></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <div class="save-button-container">
                        <div class="message"><?= __('Click "%save" to update the settings on this tab', array('%save' => __('Save'))); ?></div>
                        <span id="profile_settings_save_indicator"><?= image_tag('spinning_20.gif'); ?></span>
                        <input type="submit" id="submit_settings_button" value="<?= __('Save'); ?>">
                    </div>
                </form>
            </div>
            <div id="tab_notificationsettings_pane" style="display: none;">
                <form accept-charset="<?= \pachno\core\framework\Context::getI18n()->getCharset(); ?>" action="<?= make_url('account_save_settings'); ?>" onsubmit="Pachno.Main.Profile.updateNotificationSettings('<?= make_url('account_save_notificationsettings'); ?>'); return false;" method="post" id="profile_notificationsettings_form">
                    <h3><?= __('Subscriptions'); ?></h3>
                    <p><?= __('Pachno can subscribe you to issues, articles and other items in the system, so you can receive notifications when they are updated. Please select when you would like Pachno to subscribe you.'); ?></p>
                    <table class="padded_table" cellpadding=0 cellspacing=0>
                        <?php foreach ($subscriptionssettings as $key => $description): ?>
                            <?php if (in_array($key, [Settings::SETTINGS_USER_SUBSCRIBE_NEW_ISSUES_MY_PROJECTS_CATEGORY, Settings::SETTINGS_USER_SUBSCRIBE_NEW_ISSUES_MY_PROJECTS])) continue; ?>
                            <tr>
                                <td style="width: auto; border-bottom: 1px solid #DDD;"><label for="<?= $key; ?>_yes"><?= $description ?></label></td>
                                <?php if ($key == \pachno\core\framework\Settings::SETTINGS_USER_SUBSCRIBE_NEW_ISSUES_MY_PROJECTS_CATEGORY): ?>
                                    <td style="width: 50px; text-align: center; border-bottom: 1px solid #DDD;" valign="middle">
                                        <div class="filter interactive_dropdown" data-filterkey="<?= $key; ?>" data-value="" data-all-value="<?= __('All'); ?>">
                                            <input type="hidden" name="core_<?= $key; ?>" value="" id="filter_<?= $key; ?>_value_input">
                                            <label><?= __('Category'); ?></label>
                                            <span class="value"><?php if (true || !$filter->hasValue()) echo __('All'); ?></span>
                                            <div class="interactive_menu">
                                                <h1><?= __('Select category'); ?></h1>
                                                <input type="search" placeholder="<?= __('Filter values'); ?>">
                                                <div class="interactive_values_container">
                                                    <ul class="interactive_menu_values">
                                                        <?php foreach (\pachno\core\entities\Category::getAll() as $category_id => $category): ?>
                                                            <li data-value="<?= $category_id; ?>" class="filtervalue<?php if (false && $filter->hasValue($category_id)) echo ' selected'; ?>">
                                                                <?= fa_image_tag('check-square', ['class' => 'checked'], 'far') . fa_image_tag('square', ['class' => 'unchecked'], 'far') ?>
                                                                <input type="checkbox" value="<?= $category_id; ?>" name="core_<?= $key; ?>_value_<?= $category_id; ?>" data-text="<?= __($category->getName()); ?>" id="core_<?= $key; ?>_value_<?= $category_id; ?>" <?php if (false && $filter->hasValue($category_id)) echo 'checked'; ?>>
                                                                <label for="core_<?= $key; ?>_value_<?= $category_id; ?>"><?= __($category->getName()); ?></label>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                <?php else: ?>
                                    <td style="width: 50px; text-align: center; border-bottom: 1px solid #DDD;" valign="middle">
                                        <input type="checkbox" class="fancy-checkbox" name="core_<?= $key; ?>" value="1" id="<?= $key; ?>_yes"<?php if (!$pachno_user->getNotificationSetting($key, true)->isOff()): ?> checked<?php endif; ?>><label for="<?= $key; ?>_yes"><?= fa_image_tag('check-square', ['class' => 'checked'], 'far') . fa_image_tag('square', ['class' => 'unchecked'], 'far'); ?></label>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <?php $category_key = \pachno\core\framework\Settings::SETTINGS_USER_SUBSCRIBE_NEW_ISSUES_MY_PROJECTS_CATEGORY; ?>
                    <?php $project_issues_key = \pachno\core\framework\Settings::SETTINGS_USER_SUBSCRIBE_NEW_ISSUES_MY_PROJECTS; ?>
                    <table class="padded_table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="width: auto; border-bottom: 1px solid #DDD;"><label for="<?= $project_issues_key; ?>_yes"><?= __('Automatically subscribe to new issues that are created in my project(s)'); ?></label></td>
                            <td style="width: 350px; text-align: right; border-bottom: 1px solid #DDD; vertical-align: middle;">
                                <div class="filter interactive_dropdown rightie" data-filterkey="<?= $project_issues_key; ?>" data-value="" data-all-value="<?= __('No projects'); ?>">
                                    <input type="hidden" name="core_<?= $project_issues_key; ?>" value="<?= join(',', $selected_project_subscriptions); ?>" id="filter_<?= $project_issues_key; ?>_value_input">
                                    <label><?= __('Projects'); ?></label>
                                    <span class="value"><?= (empty($selected_project_subscriptions) && !$all_projects_subscription) ? __('No projects') : __('All my projects'); ?></span>
                                    <div class="interactive_menu">
                                        <h1><?= __('Select which projects to subscribe to'); ?></h1>
                                        <input type="search" placeholder="<?= __('Filter projects'); ?>">
                                        <div class="interactive_values_container">
                                            <ul class="interactive_menu_values">
                                                <li data-value="0" class="filtervalue <?php if ($all_projects_subscription) echo ' selected'; ?>" data-exclusive data-selection-group="1" data-exclude-group="2">
                                                    <?= fa_image_tag('check-square', ['class' => 'checked'], 'far') . fa_image_tag('square', ['class' => 'unchecked'], 'far'); ?>
                                                    <input type="checkbox" value="all" name="core_<?= $project_issues_key; ?>_all" data-text="<?= __('All my projects'); ?>" id="core_<?= $project_issues_key; ?>_value_all" <?php if ($all_projects_subscription) echo 'checked'; ?>>
                                                    <label for="core_<?= $project_issues_key; ?>_value_all"><?= __('All my projects'); ?></label>
                                                </li>
                                                <li class="separator"></li>
                                                <?php foreach ($projects as $project_id => $project): ?>
                                                    <li data-value="<?= $project_id; ?>" class="filtervalue<?php if (in_array($project_id, $selected_project_subscriptions)) echo ' selected'; ?>" data-selection-group="2" data-exclude-group="1">
                                                        <?= fa_image_tag('check-square', ['class' => 'checked'], 'far') . fa_image_tag('square', ['class' => 'unchecked'], 'far'); ?>
                                                        <input type="checkbox" value="<?= $project_id; ?>" name="core_<?= $project_issues_key; ?>_<?= $project_id; ?>" data-text="<?= __($project->getName()); ?>" id="core_<?= $project_issues_key; ?>_value_<?= $project_id; ?>" <?php if (in_array($project_id, $selected_project_subscriptions)) echo 'checked'; ?>>
                                                        <label for="core_<?= $project_issues_key; ?>_value_<?= $project_id; ?>"><?= __($project->getName()); ?></label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid #DDD;">
                                <label for="<?= $category_key; ?>_yes"><?= __('Automatically subscribe to new issues in selected categories'); ?></label><br>
                                <?= __("If you don't want to set up automatic subscriptions for all projects you're participating in, you can choose to subscribe to categories, instead. Note that if '%all_my_projects' is selected in the project subscriptions dropdown, the category subscription will have no further effect.", ['%all_my_projects' => __('All my projects')]); ?>
                            </td>
                            <td style="text-align: right; border-bottom: 1px solid #DDD; vertical-align: middle;">
                                <div class="filter interactive_dropdown rightie" data-filterkey="<?= $category_key; ?>" data-value="" data-all-value="<?= __('None selected'); ?>">
                                    <input type="hidden" name="core_<?= $category_key; ?>" value="<?= join(',', $selected_category_subscriptions); ?>" id="filter_<?= $category_key; ?>_value_input">
                                    <label><?= __('Categories'); ?></label>
                                    <span class="value"><?php if (empty($selected_category_subscriptions)) echo __('None selected'); ?></span>
                                    <div class="interactive_menu">
                                        <h1><?= __('Select which categories to subscribe to'); ?></h1>
                                        <input type="search" placeholder="<?= __('Filter categories'); ?>">
                                        <div class="interactive_values_container">
                                            <ul class="interactive_menu_values">
                                                <?php foreach ($categories as $category_id => $category): ?>
                                                    <li data-value="<?= $category_id; ?>" class="filtervalue<?php if (in_array($category_id, $selected_category_subscriptions)) echo ' selected'; ?>">
                                                        <?= fa_image_tag('check-square', ['class' => 'checked'], 'far') . fa_image_tag('square', ['class' => 'unchecked'], 'far'); ?>
                                                        <input type="checkbox" value="<?= $category_id; ?>" name="core_<?= $category_key; ?>_<?= $category_id; ?>" data-text="<?= __($category->getName()); ?>" id="core_<?= $category_key; ?>_value_<?= $category_id; ?>" <?php if (in_array($category_id, $selected_category_subscriptions)) echo 'checked'; ?>>
                                                        <label for="core_<?= $category_key; ?>_value_<?= $category_id; ?>"><?= __($category->getName()); ?></label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <h3><?= __('Notifications'); ?></h3>
                    <p><?= __('Pachno will send you notifications based on system actions and/or your subscriptions. Notifications can be received in the notifications box (the counter visible next to your avatar in the top menu) and/or via email.'); ?></p>
                    <table class="padded_table" cellpadding=0 cellspacing=0>
                        <thead>
                            <tr>
                                <th></th>
                                <th style="white-space: nowrap; width: 120px;"><?= __('Notifications box'); ?></th>
                                <?php \pachno\core\framework\Event::createNew('core', 'account_pane_notificationsettings_thead')->trigger(); ?>
                            </tr>
                        </thead>
                        <?php foreach ($notificationsettings as $key => $description): ?>
                            <?php if ($key == \pachno\core\framework\Settings::SETTINGS_USER_NOTIFY_NEW_ISSUES_MY_PROJECTS_CATEGORY) continue; ?>
                            <tr>
                                <td style="width: auto; border-bottom: 1px solid #DDD;"><label for="<?= $key; ?>_yes"><?= $description ?></label></td>
                                <?php if ($key == \pachno\core\framework\Settings::SETTINGS_USER_NOTIFY_GROUPED_NOTIFICATIONS): ?>
                                    <td style="text-align: center; border-bottom: 1px solid #DDD;" valign="middle">
                                        <input type="text" name="core_<?= $key; ?>" id="<?= $key; ?>_yes" value="<?= $pachno_user->getNotificationSetting($key, false, 'core')->getValue(); ?>" style="width:30px;">
                                    </td>
                                    <td style="text-align: center; border-bottom: 1px solid #DDD;" valign="middle"></td>
                                <?php else: ?>
                                    <td style="text-align: center; border-bottom: 1px solid #DDD;" valign="middle">
                                        <input type="checkbox" class="fancy-checkbox" name="core_<?= $key; ?>" value="1" id="<?= $key; ?>_yes"<?php if ($pachno_user->getNotificationSetting($key, $key == Settings::SETTINGS_USER_NOTIFY_MENTIONED, 'core')->isOn()) echo ' checked'; ?>><label for="<?= $key; ?>_yes"><?= fa_image_tag('check-square', ['class' => 'checked'], 'far') . fa_image_tag('square', ['class' => 'unchecked'], 'far'); ?></label>
                                    </td>
                                    <?php \pachno\core\framework\Event::createNew('core', 'account_pane_notificationsettings_cell')->trigger(compact('key')); ?>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <?php $category_key = \pachno\core\framework\Settings::SETTINGS_USER_NOTIFY_NEW_ISSUES_MY_PROJECTS_CATEGORY; ?>
                    <table class="padded_table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="width: auto; border-bottom: 1px solid #DDD; vertical-align: middle;">
                                <label for="<?= $category_key; ?>_yes"><?= __('Notify to notifications box when issues are created in selected categories') ?></label><br>
                                <?= __('If you want to be notified when an issue is created in a specific category, but do not want to automatically subscribe for updates to these issues, make sure auto-subscriptions are turned off in the "%subscriptions"-section, then use this dropdown to configure notifications.', ['%subscriptions' => __('Subscriptions')]); ?>
                            </td>
                            <td style="width: 350px; text-align: right; border-bottom: 1px solid #DDD; vertical-align: middle;">
                                <label><?= __('Notifications box'); ?></label><br>
                                <div class="filter interactive_dropdown rightie" data-filterkey="<?= $category_key; ?>" data-value="" data-all-value="<?= __('None selected'); ?>">
                                    <input type="hidden" name="core_<?= $category_key; ?>" value="<?= join(',', $selected_category_notifications); ?>" id="filter_<?= $category_key; ?>_value_input">
                                    <label><?= __('Categories'); ?></label>
                                    <span class="value"><?php if (empty($selected_category_notifications)) echo __('None selected'); ?></span>
                                    <div class="interactive_menu">
                                        <h1><?= __('Select which categories to subscribe to'); ?></h1>
                                        <input type="search" placeholder="<?= __('Filter categories'); ?>">
                                        <div class="interactive_values_container">
                                            <ul class="interactive_menu_values">
                                                <?php foreach ($categories as $category_id => $category): ?>
                                                    <li data-value="<?= $category_id; ?>" class="filtervalue<?php if (in_array($category_id, $selected_category_notifications)) echo ' selected'; ?>">
                                                        <?= fa_image_tag('check-square', ['class' => 'checked'], 'far') . fa_image_tag('square', ['class' => 'unchecked'], 'far'); ?>
                                                        <input type="checkbox" value="<?= $category_id; ?>" name="core_<?= $category_key; ?>_<?= $category_id; ?>" data-text="<?= __($category->getName()); ?>" id="core_<?= $category_key; ?>_value_<?= $category_id; ?>" <?php if (in_array($category_id, $selected_category_notifications)) echo 'checked'; ?>>
                                                        <label for="core_<?= $category_key; ?>_value_<?= $category_id; ?>"><?= __($category->getName()); ?></label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <?php \pachno\core\framework\Event::createNew('core', 'account_pane_notificationsettings_notification_categories')->trigger(compact('categories')); ?>
                            </td>
                        </tr>
                    </table>
                    <?php \pachno\core\framework\Event::createNew('core', 'account_pane_notificationsettings_subscriptions')->trigger(compact('categories')); ?>
                    <h3><?= __('Desktop notifications'); ?></h3>
                    <p><?= __('You can receive desktop notifications based on system actions or your subscriptions. Choose your desktop notification preferences from this section.'); ?></p>
                    <table class="padded_table desktop-notifications-settings" cellpadding=0 cellspacing=0>
                        <tr>
                            <td><label for="profile_enable_desktop_notifications"><?= __('Enable desktop notifications'); ?></label></td>
                            <td>
                                <input type="button" class="button" value="<?= __('Grant Permission'); ?>" id="profile_enable_desktop_notifications" onclick="Pachno.Main.Notifications.Web.GrantPermissionOrSendTest('<?= __('Test notification'); ?>', '<?= __('This is a test notification.'); ?>', '<?= \pachno\core\framework\Settings::isUsingCustomFavicon() ? \pachno\core\framework\Settings::getFaviconURL() : image_url('favicon.png'); ?>');">
                            </td>
                        </tr>
                        <tr>
                            <td class="config-explanation" colspan="2">
                                <?= __('If your web browser supports desktop notification, Pachno can show a desktop notification whenever a new notification is received. To allow this, please click the "%grant_permission" button', ['%grant_permission' => __('Grant permission')]); ?>
                            </td>
                        </tr>
                    </table>
                    <table class="padded_table desktop-notifications-settings" cellpadding=0 cellspacing=0>
                        <tr>
                            <td>
                                <input type="checkbox" class="fancy-checkbox" name="enable_desktop_notifications_new_tab" value="1" id="profile_enable_desktop_notifications_new_tab"<?php if ($pachno_user->isDesktopNotificationsNewTabEnabled()): ?> checked<?php endif; ?>>
                                <label for="profile_enable_desktop_notifications_new_tab"><?= fa_image_tag('check-square', ['class' => 'checked'], 'far') . fa_image_tag('square', ['class' => 'unchecked'], 'far') . __('Open desktop notifications in new tab'); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td class="config-explanation" colspan="2">
                                <?= __('Whether clicking on notification will open target url in new or current tab. Notifications which target is backdrop will not be affected and will open in current tab. By default browsers will block opening of new tab programmatically, unless you enable pop-ups.'); ?>
                            </td>
                        </tr>
                    </table>
                    <div class="save-button-container">
                        <div class="message"><?= __('Click "%save" to update the settings on this tab', array('%save' => __('Save'))); ?></div>
                        <span id="profile_notificationsettings_save_indicator"><?= image_tag('spinning_20.gif'); ?></span>
                        <input type="submit" id="submit_notificationsettings_button" value="<?= __('Save'); ?>">
                    </div>
                </form>
            </div>
            <div id="tab_security_pane" style="<?php if ($selected_tab != 'security'): ?> display: none;<?php endif; ?>">
                <h3 style="position: relative;"><?= __('Two-factor authentication'); ?></h3>
                <p><?= __("Enabling two-factor authentication increases account security by requiring that you provide a one-time code every time you log in on a new device."); ?></p>
                <ul class="access_keys_list">
                    <li id="account_2fa_disabled" style="<?php if ($pachno_user->is2FaEnabled()) echo 'display: none;'; ?>">
                        <h4><?= fa_image_tag('times', ['class' => 'icon']); ?><span><?= __('Two-factor authentication is not enabled'); ?></span></h4>
                        <p><?= __('Enable two-factor authentication to increase account security'); ?></p>
                        <button class="button" onclick="Pachno.Main.Helpers.Backdrop.show('<?= make_url('get_partial_for_backdrop', ['key' => 'enable_2fa']); ?>');"><?= __('Enable'); ?></button>
                    </li>
                    <li id="account_2fa_enabled" style="<?php if (!$pachno_user->is2FaEnabled()) echo 'display: none;'; ?>">
                        <h4><?= fa_image_tag('check', ['class' => 'icon']); ?><span><?= __('Two-factor authentication is enabled'); ?></span></h4>
                        <p><?= __('A one-time code is required to log in on a new device'); ?></p>
                        <button class="button" onclick="Pachno.Main.Helpers.Dialog.show('<?= __('Really disable two-factor authentication?'); ?>', '<?= __('Do you really want to two-factor authentication? By doing this, only your username and password is required when logging in.'); ?>', {yes: {click: function () { Pachno.Main.Login.disable2Fa('<?= make_url('account_disable_2fa', array('csrf_token' => \pachno\core\framework\Context::getCsrfToken())); ?>') }}, no: {click: Pachno.Main.Helpers.Dialog.dismiss}});"><?= __('Disable 2FA'); ?></button>
                    </li>
                </ul>
                <h3 style="position: relative;">
                    <?= __('Passwords and keys'); ?>
                    <a class="button dropper" id="password_actions" href="javascript:void(0);"><?= __('Actions'); ?></a>
                    <ul id="password_more_actions" style="width: 300px; font-size: 0.8em; text-align: right; top: 29px; margin-top: 0; right: 3px; z-index: 1000;" class="more_actions_dropdown popup_box dropper">
                        <?php if ($pachno_user->canChangePassword() && !$pachno_user->isOpenIdLocked()): ?>
                            <li><a href="javascript:void(0);" onclick="$('change_password_div').toggle();"><?= __('Change my password'); ?></a></li>
                        <?php elseif ($pachno_user->isOpenIdLocked()): ?>
                            <li><a href="javascript:void(0);" onclick="$('pick_username_div').toggle();" id="pick_username_button"><?= __('Pick a username'); ?></a></li>
                        <?php else: ?>
                            <li><a href="javascript:void(0);" onclick="Pachno.Main.Helpers.Message.error('<?= __('Changing password disabled'); ?>', '<?= __('Changing your password can not be done via this interface. Please contact your administrator to change your password.'); ?>');" class="disabled"><?= __('Change my password'); ?></a></li>
                        <?php endif; ?>
                        <li><a href="javascript:void(0);" onclick="$('add_application_password_div').toggle();"><?= __('Add application-specific password'); ?></a></li>
                    </ul>
                </h3>
                <p><?= __("When authenticating with Pachno you only use your main password on the website - other applications and RSS feeds needs specific access tokens that you can enable / disable on an individual basis. You can control all your passwords and keys from here."); ?></p>
                <ul class="access_keys_list">
                    <li>
                        <button class="button" onclick="Pachno.Main.Helpers.Dialog.show('<?= __('Regenerate your RSS key?'); ?>', '<?= __('Do you really want to regenerate your RSS access key? By doing this all your previously bookmarked or linked RSS feeds will stop working and you will have to get the link from inside Pachno again.'); ?>', {yes: {href: '<?= make_url('account_regenerate_rss_key', array('csrf_token' => \pachno\core\framework\Context::getCsrfToken())); ?>'}, no: {click: Pachno.Main.Helpers.Dialog.dismiss}});"><?= __('Reset'); ?></button>
                        <h4><?= __('RSS feeds access key'); ?></h4>
                        <p><?= __('Automatically used as part of RSS feed URLs. Regenerating this key prevents your previous RSS feed links from working.'); ?></p>
                    </li>
                    <?php foreach ($pachno_user->getApplicationPasswords() as $password): ?>
                        <li id="application_password_<?= $password->getID(); ?>">
                            <button class="button" onclick="Pachno.Main.Helpers.Dialog.show('<?= __('Remove this application-specific password?'); ?>', '<?= __('Do you really want to remove this application-specific password? By doing this, that application will no longer have access, and you will have to generate a new application password for the application to regain access.'); ?>', {yes: {click: function() {Pachno.Main.Profile.removeApplicationPassword('<?= make_url('account_remove_application_password', array('id' => $password->getID(), 'csrf_token' => \pachno\core\framework\Context::getCsrfToken())); ?>', <?= $password->getID(); ?>);}}, no: {click: Pachno.Main.Helpers.Dialog.dismiss}});"><?= __('Delete'); ?></button>
                            <h4><?= __('Application password: %password_name', array('%password_name' => $password->getName())); ?></h4>
                            <p><?= __('Last used: %last_used_time, created at: %created_at_time', array('%last_used_time' => ($password->getLastUsedAt()) ? \pachno\core\framework\Context::getI18n()->formatTime($password->getLastUsedAt(), 20) : __('never used'), '%created_at_time' => \pachno\core\framework\Context::getI18n()->formatTime($password->getCreatedAt(), 20))); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php \pachno\core\framework\Event::createNew('core', 'account_tab_panes')->trigger(); ?>
            <?php foreach (\pachno\core\framework\Context::getAllModules() as $modules): ?>
                <?php foreach ($modules as $module_name => $module): ?>
                    <?php if ($module->hasAccountSettings()): ?>
                        <div id="tab_settings_<?= $module_name; ?>_pane" style="display: none;">
                            <?php include_component("{$module_name}/accountsettings", array('module' => $module)); ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <?php if (count($pachno_user->getScopes()) > 1): ?>
                <div id="tab_scopes_pane" style="display: none;">
                    <h3><?= __('Pending memberships'); ?></h3>
                    <ul class="simple-list" id="pending_scope_memberships">
                        <?php foreach ($pachno_user->getUnconfirmedScopes() as $scope): ?>
                            <?php include_component('main/userscope', array('scope' => $scope)); ?>
                        <?php endforeach; ?>
                    </ul>
                    <span id="no_pending_scope_memberships" class="faded_out" style="<?php if (count($pachno_user->getUnconfirmedScopes())): ?>display: none;<?php endif; ?>"><?= __('You have no pending scope memberships'); ?></span>
                    <h3 style="margin-top: 20px;"><?= __('Confirmed memberships'); ?></h3>
                    <ul class="simple-list" id="confirmed_scope_memberships">
                        <?php foreach ($pachno_user->getConfirmedScopes() as $scope_id => $scope): ?>
                            <?php include_component('main/userscope', array('scope' => $scope)); ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    var Pachno, jQuery;
    require(['domReady', 'pachno/index', 'jquery', 'jquery.nanoscroller'], function (domReady, pachno_index_js, jquery, nanoscroller) {
        domReady(function () {
            Pachno = pachno_index_js;

            Pachno.Main.Helpers.tabSwitchFromHash('account_tabs');

            $$('.filter').each(function (filter) {
                Pachno.Search.initializeFilterField(filter);
            });

            <?php if ($error): ?>
                Pachno.Main.Helpers.Message.error('<?= __('An error occurred'); ?>', '<?= $error; ?>');
            <?php endif; ?>
            <?php if ($rsskey_generated): ?>
                Pachno.Main.Helpers.Message.success('<?= __('Your RSS key has been regenerated'); ?>', '<?= __('All previous RSS links have been invalidated.'); ?>');
            <?php endif; ?>
            <?php if ($username_chosen): ?>
                Pachno.Main.Helpers.Message.success('<?= __("You\'ve chosen the username \'%username\'", array('%username' => $pachno_user->getUsername())); ?>', '<?= __('Before you can use the new username to log in, you must pick a password via the "%change_password" button.', array('%change_password' => __('Change password'))); ?>');
            <?php endif; ?>
            <?php if ($openid_used): ?>
                Pachno.Main.Helpers.Message.error('<?= __('This OpenID identity is already in use'); ?>', '<?= __('Someone is already using this identity. Check to see if you have already added this account.'); ?>');
            <?php endif; ?>
        });
    });
</script>
