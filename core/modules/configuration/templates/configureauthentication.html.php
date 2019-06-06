<?php $pachno_response->setTitle(__('Configure authentication')); ?>
<div class="content-with-sidebar">
    <?php include_component('configuration/sidebar', ['selected_section' => \pachno\core\framework\Settings::CONFIGURATION_SECTION_AUTHENTICATION]); ?>
    <div class="configuration-container">
        <h3><?php echo __('Configure authentication'); ?></h3>
        <div class="message-box type-warning">
            <?= fa_image_tag('exclamation-circle') . '<span>'.__('Please remember to install and configure your chosen authentication backend before setting it here. Changing settings on this page will result in you being logged out. If you find yourself unable to log in, use the %bin/pachno command line client to revert these settings.', ['%bin/pachno' => '<span class="command_box">bin/pachno</span>']).'</span>'; ?>
        </div>
        <?php if ($access_level == \pachno\core\framework\Settings::ACCESS_FULL): ?>
            <form accept-charset="<?php echo \pachno\core\framework\Context::getI18n()->getCharset(); ?>" action="<?php echo make_url('configure_authentication_pt2'); ?>" method="post" id="config_auth">
        <?php endif; ?>
        <table style="clear: both; width: 100%; margin-top: 15px;" class="padded_table" cellpadding=0 cellspacing=0>
            <tr>
                <td><label for="auth_backend"><?php echo __('Authentication backend'); ?></label></td>
                <td>
                    <select name="auth_backend" id="auth_backend">
                        <option value="default"<?php if (\pachno\core\framework\Settings::getAuthenticationBackendIdentifier() == 'default' || \pachno\core\framework\Settings::getAuthenticationBackendIdentifier() == null): ?> selected="selected"<?php endif; ?>><?php echo __('Pachno authentication (use internal user mechanisms)'); ?></option>
                        <?php
                        foreach ($modules as $module)
                        {
                            $selected = null;
                            if (\pachno\core\framework\Settings::getAuthenticationBackendIdentifier() == $module->getTabKey()):
                                $selected = ' selected="selected"';
                            endif;
                            echo '<option value="'.$module->getTabKey().'"'.$selected.'>'.$module->getLongName().'</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="config-explanation" colspan="2"><?php echo __('All modules which provide authentication are shown here. Please ensure your chosen backend is configured first, and please read the warnings included with your chosen backend to ensure that you do not lose administrator access.'); ?></td>
            </tr>
            <tr>
                <td colspan="2" style="vertical-align: top">
                    <label for="register_message"><?php echo __('Registration message'); ?></label>
                    <?php include_component('main/textarea', array('area_name' => 'register_message', 'area_id' => 'register_message', 'height' => '75px', 'width' => '100%', 'value' => \pachno\core\framework\Settings::get('register_message'), 'hide_hint' => true)); ?>
                </td>
            </tr>
            <tr>
                <td class="config-explanation" colspan="2"><?php echo __('Pachno\'s registration page is unavailable when using a different backend. Write a message here to be shown to users instead. WikiFormatting can be used in this box and similar ones on this page.'); ?></td>
            </tr>
            <tr>
                <td colspan="2" style="vertical-align: top">
                    <label for="forgot_message"><?php echo __('Forgot password message'); ?></label>
                    <?php include_component('main/textarea', array('area_name' => 'forgot_message', 'area_id' => 'forgot_message', 'height' => '75px', 'width' => '100%', 'value' => \pachno\core\framework\Settings::get('forgot_message'), 'hide_hint' => true)); ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="vertical-align: top">
                    <label for="changepw_message"><?php echo __('Change password message'); ?></label>
                    <?php include_component('main/textarea', array('area_name' => 'changepw_message', 'area_id' => 'changepw_message', 'height' => '75px', 'width' => '100%', 'value' => \pachno\core\framework\Settings::get('changepw_message'), 'hide_hint' => true)); ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="vertical-align: top">
                    <label for="changedetails_message"><?php echo __('Change account details message'); ?></label>
                    <?php include_component('main/textarea', array('area_name' => 'changedetails_message', 'area_id' => 'changedetails_message', 'height' => '75px', 'width' => '100%', 'value' => \pachno\core\framework\Settings::get('changedetails_message'), 'hide_hint' => true)); ?>
                </td>
            </tr>
        </table>
        <?php if ($access_level == \pachno\core\framework\Settings::ACCESS_FULL): ?>
                <div class="save-button-container">
                    <div class="message"><?php echo __('Click "%save" to save your changes in all categories', array('%save' => __('Save'))); ?></div>
                    <input type="submit" id="config_auth_button" value="<?php echo __('Save'); ?>">
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
