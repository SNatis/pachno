<?php $pachno_response->setTitle(__('Configure issue types')); ?>
<div class="content-with-sidebar">
    <?php include_component('leftmenu', ['selected_section' => \pachno\core\framework\Settings::CONFIGURATION_SECTION_ISSUETYPES]); ?>
    <div class="configuration-container">
        <div class="configuration-content">
            <h1><?php echo __('Configure issue types'); ?></h1>
            <div class="helper-text">
                <p><?php echo __('All issue types have their own settings for which fields are available / required on both the reporting page and on the issue overview page.'); ?></p>
                <p><?php echo __('You can read more about how issue types and schemes in Pachno works and is set up in the %online_documentation', array('%online_documentation' => link_tag('https://projects.pachno.com/pachno/docs/IssuetypeScheme', __('online documentation')))); ?></p>
            </div>
            <h3>
                <span><?php echo __('Existing issue types'); ?></span>
                <button class="button" onclick="Pachno.Main.Helpers.Backdrop.show('<?= make_url('get_partial_for_backdrop', ['key' => 'edit_issuetype']); ?>');"><?= __('Create issue type'); ?></button>
            </h3>
            <div id="issuetypes_list" class="flexible-table">
                <div class="row header">
                    <div class="column header name-container"><?= __('Issue type'); ?></div>
                    <div class="column header actions"></div>
                </div>
                <?php foreach ($issue_types as $type): ?>
                    <?php include_component('issuetype', array('type' => $type)); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
