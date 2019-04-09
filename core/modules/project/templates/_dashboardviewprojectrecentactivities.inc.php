<div class="dashboard_project_recent_activities">
    <?php if (count($recent_activities) > 0): ?>
        <?php include_component('project/timeline', array('activities' => $recent_activities)); ?>
        <br style="clear: both;">
    <?php else: ?>
        <div class="no-items">
            <?= fa_image_tag('th-list'); ?>
            <span><?php echo __('As soon as something important happens it will appear here.'); ?></span>
        </div>
    <?php endif; ?>
</div>
<div class="button-container">
    <?php if ($pachno_user->hasProjectPageAccess('project_timeline', \pachno\core\framework\Context::getCurrentProject())): ?>
        <?php echo link_tag(make_url('project_timeline', array('project_key' => \pachno\core\framework\Context::getCurrentProject()->getKey())), __('Show complete timeline'), array('class' => 'button dash', 'title' => __('Show more'))); ?>
        <?php echo link_tag(make_url('project_timeline_important', array('project_key' => \pachno\core\framework\Context::getCurrentProject()->getKey())), __('Show timeline for important events'), array('class' => 'button dash', 'title' => __('Show more'))); ?>
    <?php endif; ?>
</div>
