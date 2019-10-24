<?php

/** @var \pachno\core\entities\Project $project */

?>
<div class="form-container">
    <?php if ($access_level == \pachno\core\framework\Settings::ACCESS_FULL): ?>
    <form accept-charset="<?php echo \pachno\core\framework\Context::getI18n()->getCharset(); ?>" action="<?php echo make_url('configure_project_settings', array('project_id' => $project->getID())); ?>" method="post" onsubmit="Pachno.Project.submitAdvancedSettings('<?php echo make_url('configure_project_settings', array('project_id' => $project->getID())); ?>'); return false;" data-interactive-form>
    <?php endif; ?>
        <div class="form-row">
            <h3><?= __('Project settings'); ?></h3>
        </div>
        <div class="form-row">
            <label for="enable_builds_yes"><?php echo __('Enable releases'); ?></label>
            <div class="fancy-label-select">
                <?php if ($access_level == \pachno\core\framework\Settings::ACCESS_FULL): ?>
                    <input type="radio" name="enable_builds" value="1" class="fancy-checkbox" id="enable_builds_yes"<?php if ($project->isBuildsEnabled()): ?> checked<?php endif; ?>>
                    <label for="enable_builds_yes"><?php echo fa_image_tag('check', ['class' => 'checked']) . __('Yes'); ?></label>
                    <input type="radio" name="enable_builds" value="0" class="fancy-checkbox" id="enable_builds_no"<?php if (!$project->isBuildsEnabled()): ?> checked<?php endif; ?>>
                    <label for="enable_builds_no"><?php echo fa_image_tag('check', ['class' => 'checked']) . __('No'); ?></label>
                <?php else: ?>
                    <?php echo ($project->isBuildsEnabled()) ? __('Yes') : __('No'); ?>
                <?php endif; ?>
            </div>
            <div class="helper-text"><?php echo __('If this project has regular new main- or test-releases, you can use this feature to track issue across different releases'); ?></div>
        </div>
        <div class="form-row">
            <label for="project_downloads_enabled"><?php echo __('Enable downloads'); ?></label>
            <div class="fancy-label-select">
                <?php if ($access_level == \pachno\core\framework\Settings::ACCESS_FULL): ?>
                    <input type="radio" name="enable_downloads" value="1" class="fancy-checkbox" id="enable_downloads_yes"<?php if ($project->hasDownloads()): ?> checked<?php endif; ?>>
                    <label for="enable_downloads_yes"><?php echo fa_image_tag('check', ['class' => 'checked']) . __('Yes'); ?></label>
                    <input type="radio" name="enable_downloads" value="0" class="fancy-checkbox" id="enable_downloads_no"<?php if (!$project->hasDownloads()): ?> checked<?php endif; ?>>
                    <label for="enable_downloads_no"><?php echo fa_image_tag('check', ['class' => 'checked']) . __('No'); ?></label>
                <?php else: ?>
                    <?php echo ($project->hasDownloads()) ? __('Yes') : __('No'); ?>
                <?php endif; ?>
            </div>
            <div class="helper-text"><?php echo __('If project releases can be downloaded, use this feature to either upload the files or point to download links'); ?></div>
        </div>
    <?php if ($access_level == \pachno\core\framework\Settings::ACCESS_FULL): ?>
        <div class="form-row submit-container">
            <button type="submit" class="button primary">
                <span><?php echo __('Save'); ?></span>
                <?= fa_image_tag('spinner', ['class' => 'fa-spin icon indicator']); ?>
            </button>
        </div>
    </form>
    <?php endif; ?>
</div>
