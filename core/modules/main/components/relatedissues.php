<div class="related-issues-list" id="related_child_issues_inline">
    <?php foreach ($child_issues as $child_issue): ?>
        <?php include_component('main/relatedissue', array('issue' => $child_issue, 'related_issue' => $issue)); ?>
    <?php endforeach; ?>
</div>
<div id="no_related_issues"<?php if (count($child_issues) > 0): ?> style="display: none;"<?php endif; ?>><?php echo __('This issue does not have any child issues'); ?></div>
