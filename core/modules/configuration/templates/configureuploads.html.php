<?php $pachno_response->setTitle(__('Configure uploads & attachments')); ?>
<script type="text/javascript">

    function toggleSettings()
    {
        if ($('enable_uploads_yes').checked)
        {
            $('upload_restriction_mode').enable();
            $('upload_extensions_list').enable();
            if ($('upload_storage')) $('upload_storage').enable();
            $('upload_max_file_size').enable();
            if ($('upload_storage').getValue() == 'files')
            {
                $('upload_localpath').enable();
            }
            $('upload_allow_image_caching').enable();
            $('upload_delivery_use_xsend').enable();
        }
        else
        {
            $('upload_restriction_mode').disable();
            $('upload_extensions_list').disable();
            if ($('upload_storage')) $('upload_storage').disable();
            $('upload_max_file_size').disable();
            $('upload_localpath').disable();
            $('upload_allow_image_caching').disable();
            $('upload_delivery_use_xsend').disable();
        }
    }

</script>
<div class="content-with-sidebar">
    <?php include_component('leftmenu', ['selected_section' => \pachno\core\framework\Settings::CONFIGURATION_SECTION_UPLOADS]); ?>
    <div class="configuration-container">
        <h3><?php echo __('Configure uploads & attachments'); ?></h3>
        <?php if (\pachno\core\framework\Context::getScope()->getMaxUploadLimit()): ?>
            <div style="text-align: center;">
                <?php include_component('main/percentbar', array('height' => '20', 'percent' => \pachno\core\framework\Context::getScope()->getCurrentUploadUsagePercent())); ?>
                <?php echo __('%mb MB of %max MB', array('%mb' => \pachno\core\framework\Context::getScope()->getCurrentUploadUsageMB(), '%max' => \pachno\core\framework\Context::getScope()->getMaxUploadLimit())); ?>
            </div>
        <?php endif; ?>
        <?php if ($uploads_enabled && $access_level == \pachno\core\framework\Settings::ACCESS_FULL): ?>
            <form accept-charset="<?php echo \pachno\core\framework\Context::getI18n()->getCharset(); ?>" action="<?php echo make_url('configure_files'); ?>" method="post" onsubmit="Pachno.Main.Helpers.formSubmit('<?php echo make_url('configure_files'); ?>', 'config_uploads'); return false;" id="config_uploads">
        <?php endif; ?>
        <?php if (!function_exists('mime_content_type') && !extension_loaded('fileinfo')): ?>
            <div class="rounded_box yellow borderless" style="margin: 5px 0px 5px 0px; width: 700px; padding: 5px 10px 5px 10px;">
                <?php echo __('The file upload functionality can be enhanced with file type detection. To enable this, please install and enable the fileinfo extension.'); ?>
            </div>
        <?php endif; ?>
        <?php if ($uploads_enabled): ?>
            <table style="clear: both; width: 700px; margin-top: 5px;" class="padded_table" cellpadding=0 cellspacing=0>
                <tr>
                    <td style="width: 200px;"><label for="enable_uploads_yes"><?php echo __('Enable uploads'); ?></label></td>
                    <td style="width: auto;">
                        <?php if ($access_level == \pachno\core\framework\Settings::ACCESS_FULL): ?>
                            <input type="radio" name="enable_uploads" value="1" class="fancycheckbox" id="enable_uploads_yes"<?php if (\pachno\core\framework\Settings::isUploadsEnabled()): ?> checked<?php endif; ?> onclick="toggleSettings();"><label for="enable_uploads_yes"><?php echo fa_image_tag('check-square', ['class' => 'checked'], 'far') . fa_image_tag('square', ['class' => 'unchecked'], 'far') . __('Yes'); ?></label>
                            <input type="radio" name="enable_uploads" value="0" class="fancycheckbox" id="enable_uploads_no"<?php if (!\pachno\core\framework\Settings::isUploadsEnabled()): ?> checked<?php endif; ?> onclick="toggleSettings();"><label for="enable_uploads_no"><?php echo fa_image_tag('check-square', ['class' => 'checked'], 'far') . fa_image_tag('square', ['class' => 'unchecked'], 'far') . __('No'); ?></label>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="config-explanation" colspan="2"><?php echo __('When uploads are disabled, users will not be able to attach files to issues or upload documents, images or PDFs in project planning. More fine-grained permissions are available from the permissions configuration.'); ?></td>
                </tr>
                <tr>
                    <td><label for="upload_max_file_size"><?php echo __('Max upload file size'); ?></label></td>
                    <td>
                        <input type="text" name="upload_max_file_size" id="upload_max_file_size" style="width: 50px;" value="<?php echo \pachno\core\framework\Settings::getUploadsMaxSize(); ?>"<?php if (!\pachno\core\framework\Settings::isUploadsEnabled()): ?> disabled<?php endif; ?>>&nbsp;<?php echo __('MB'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="config-explanation" colspan="2">
                        <?php echo __('Enter the maximum allowed file size for uploads here. Remember that this value cannot be higher than the current php max_upload_size or post_max_size, both defined in php.ini.'); ?>
                        <u><?php echo __('Currently, these values are max_upload_size: %ini_max_upload_size and post_max_size: %ini_post_max_size.', array('%ini_max_upload_size' => '<b>' . (int) ini_get('upload_max_filesize') . __('MB') . '</b>', '%ini_post_max_size' => '<b>' . (int) ini_get('post_max_size') . __('MB') . '</b>')); ?></u>
                        <?php if (\pachno\core\framework\Context::getScope()->getMaxUploadLimit()): ?>
                        <br>
                        <br>
                        <b><?php echo __('Also note that there is a total upload limit on this instance, which is %limit MB.', array('%limit' => '<u>' . \pachno\core\framework\Context::getScope()->getMaxUploadLimit() . '</u>')); ?><br></b>
                        <br>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="upload_restriction_mode"><?php echo __('Upload restrictions'); ?></label></td>
                    <td>
                        <select name="upload_restriction_mode" id="upload_restriction_mode"<?php if (!\pachno\core\framework\Settings::isUploadsEnabled()): ?> disabled<?php endif; ?> onChange="var label = (this.getValue() == 'whitelist') ? '<?php echo __('Allowed extensions'); ?>' : '<?php echo __('Denied extensions'); ?>'; $('label_upload_extensions_list').update(label); $('label_upload_extensions_list').innerHTML;">
                            <option value="whitelist"<?php if (\pachno\core\framework\Settings::getUploadsRestrictionMode() == 'whitelist'): ?> selected<?php endif; ?>><?php echo __('Use a whitelist (only allow the following of extensions)'); ?></option>
                            <option value="blacklist"<?php if (\pachno\core\framework\Settings::getUploadsRestrictionMode() == 'blacklist'): ?> selected<?php endif; ?>><?php echo __('Use a blacklist (allow everything except the following extensions)'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label id="label_upload_extensions_list" for="upload_extensions_list"><?php if (\pachno\core\framework\Settings::getUploadsRestrictionMode() == 'whitelist') echo __('Allowed extensions'); else echo __('Denied extensions'); ?></label></td>
                    <td>
                        <input type="text" name="upload_extensions_list" id="upload_extensions_list" style="width: 250px;" value="<?php echo implode(',', \pachno\core\framework\Settings::getUploadsExtensionsList()); ?>"<?php if (!\pachno\core\framework\Settings::isUploadsEnabled()): ?> disabled<?php endif; ?>>
                    </td>
                </tr>
                <tr>
                    <td class="config-explanation" colspan="2">
                        <?php echo __('A space-, comma- or semicolon-separated list of extensions used to filter uploads, based on the %upload_restrictions setting above.', array('%upload_restrictions' => '<i><b>'.__('Upload restrictions').'</i></b>')); ?><br>
                        <?php echo '<b>' . __('Ex: "%example_1" or "%example_2" or "%example_3"', array('%example_1' => '</b><i>txt doc jpg png</i>', '%example_2' => '<i>txt,doc,jpg,png</i>', '%example_3' => '<i>txt;doc;jpg;png</i>')); ?>
                    </td>
                </tr>
                <?php if (\pachno\core\framework\Context::getScope()->isDefault()): ?>
                    <tr>
                        <td><label for="upload_storage"><?php echo __('File storage'); ?></label></td>
                        <td>
                            <select name="upload_storage" id="upload_storage"<?php if (!\pachno\core\framework\Settings::isUploadsEnabled()): ?> disabled<?php endif; ?> onchange="(this.value == 'files') ? $('upload_localpath').enable() : $('upload_localpath').disable();">
                                <option value="files"<?php if (\pachno\core\framework\Settings::getUploadStorage() == 'files'): ?> selected<?php endif; ?>><?php echo __('Store it in the folder specified below'); ?></option>
                                <option value="database"<?php if (\pachno\core\framework\Settings::getUploadStorage() == 'database'): ?> selected<?php endif; ?>><?php echo __('Use the database to store files'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="config-explanation" colspan="2"><?php echo __('Specify whether you want to use the filesystem or database to store uploaded files. Using the database will make it easier to move your installation to another server.'); ?></td>
                    </tr>
                    <tr>
                        <td><label><?php echo __('Storage usage'); ?></label></td>
                        <td>
                            <?php echo __('%mb MB used', ['%mb' => \pachno\core\framework\Context::getScope()->getCurrentUploadUsageMB()]); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="upload_localpath"><?php echo __('Upload location'); ?></label></td>
                        <td>
                            <input type="text" name="upload_localpath" id="upload_localpath" style="width: 250px;" value="<?php echo (\pachno\core\framework\Settings::getUploadsLocalpath() != "") ? \pachno\core\framework\Settings::getUploadsLocalpath() : PACHNO_PATH . 'files/'; ?>"<?php if (!\pachno\core\framework\Settings::isUploadsEnabled() || \pachno\core\framework\Settings::getUploadStorage() == 'database'): ?> disabled<?php endif; ?>>
                        </td>
                    </tr>
                    <tr>
                        <td class="config-explanation" colspan="2"><?php echo __("If you're storing files on the filesystem, specify where you want to save the files, here. Default location is the %files folder in the main folder (not the public folder)", array('%files' => '<b>files/</b>')); ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td style="width: 200px;"><label for="upload_delivery_use_xsend_yes"><?php echo __('Use X-Sendfile for delivering files'); ?></label></td>
                    <td style="width: auto;">
                        <?php if ($access_level == \pachno\core\framework\Settings::ACCESS_FULL): ?>
                            <input type="radio" name="upload_delivery_use_xsend" value="1" class="fancycheckbox" id="upload_delivery_use_xsend_yes"<?php if (\pachno\core\framework\Settings::isUploadsDeliveryUseXsend()): ?> checked<?php endif; ?> onclick="toggleSettings();"><label for="upload_delivery_use_xsend_yes"><?php echo fa_image_tag('check-square', ['class' => 'checked'], 'far') . fa_image_tag('square', ['class' => 'unchecked'], 'far') . __('Yes'); ?></label>
                            <input type="radio" name="upload_delivery_use_xsend" value="0" class="fancycheckbox" id="upload_delivery_use_xsend_no"<?php if (!\pachno\core\framework\Settings::isUploadsDeliveryUseXsend()): ?> checked<?php endif; ?> onclick="toggleSettings();"><label for="upload_delivery_use_xsend_no"><?php echo fa_image_tag('check-square', ['class' => 'checked'], 'far') . fa_image_tag('square', ['class' => 'unchecked'], 'far') . __('No'); ?></label>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="config-explanation" colspan="2">
                      <?php echo __("Choose whether files shall be delivered through PHP or the X-Sendfile server extension. X-Sendfile allows delivering big files without impacting PHP's memory limit."); ?><br />
                      <?php echo '<b>' . __("Warning:") . '</b> ' . __(" When enabling this option, make sure the X-Sendfile extension is installed on your server and configured properly to serve files from the above upload location, or file delivery will be severely broken."); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 200px;"><label for="upload_allow_image_caching_yes"><?php echo __('Enable browser caching for images'); ?></label></td>
                    <td style="width: auto;">
                        <?php if ($access_level == \pachno\core\framework\Settings::ACCESS_FULL): ?>
                        <input type="radio" name="upload_allow_image_caching" value="1" class="fancycheckbox" id="upload_allow_image_caching_yes"<?php if (\pachno\core\framework\Settings::isUploadsImageCachingEnabled()): ?> checked<?php endif; ?> onclick="toggleSettings();"><label for="upload_allow_image_caching_yes"><?php echo fa_image_tag('check-square', ['class' => 'checked'], 'far') . fa_image_tag('square', ['class' => 'unchecked'], 'far') . __('Yes'); ?></label>
                        <input type="radio" name="upload_allow_image_caching" value="0" class="fancycheckbox" id="upload_allow_image_caching_no"<?php if (!\pachno\core\framework\Settings::isUploadsImageCachingEnabled()): ?> checked<?php endif; ?> onclick="toggleSettings();"><label for="upload_allow_image_caching_no"><?php echo fa_image_tag('check-square', ['class' => 'checked'], 'far') . fa_image_tag('square', ['class' => 'unchecked'], 'far') . __('No'); ?></label>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="config-explanation" colspan="2"><?php echo __('By default, browser caching is disabled for uploads and attachments. When enabling this option, image files will be delivered to the browser with a valid caching header.'); ?></td>
                </tr>
            </table>
        <?php else: ?>
            <div class="content faded_out dark">
                <?php echo __('File uploads are not available in this instance of Pachno.'); ?>
                <?php echo __('When uploads are disabled, users will not be able to attach files to issues or upload documents, images or PDFs in project planning. More fine-grained permissions are available from the permissions configuration.'); ?>
            </div>
        <?php endif; ?>
        <?php if ($uploads_enabled && $access_level == \pachno\core\framework\Settings::ACCESS_FULL): ?>
                <div class="save-button-container">
                    <div class="message"><?php echo __('Click "%save" to save your changes in all categories', array('%save' => __('Save'))); ?></div>
                    <span id="config_uploads_indicator" style="display: none;"><?php echo image_tag('spinning_20.gif'); ?></span>
                    <input type="submit" id="config_uploads_button" value="<?php echo __('Save'); ?>">
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
