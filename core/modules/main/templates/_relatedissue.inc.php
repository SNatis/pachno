<li class="hover_highlight<?php if ($issue->isClosed()): ?> closed<?php endif; ?> relatedissue" id="related_issue_<?php echo $issue->getID(); ?>">
    <?php if (isset($related_issue) &&$related_issue->canAddRelatedIssues()): ?>
        <?php echo javascript_link_tag(image_tag('action_delete.png'), array('class' => 'removelink', 'onclick' => "Pachno.Main.Helpers.Dialog.show('".__('Remove relation to issue %itemname?', array('%itemname' => $issue->getFormattedIssueNo(true)))."', '".__('Please confirm that you want to remove this item from the list of issues related to this issue')."', {yes: {click: function() {Pachno.Issues.removeRelated('".make_url('viewissue_remove_related_issue', array('project_key' => $related_issue->getProject()->getKey(), 'issue_id' => $related_issue->getID(), 'related_issue_id' => $issue->getID()))."', ".$issue->getID().");Pachno.Main.Helpers.Dialog.dismiss();}}, no: {click: Pachno.Main.Helpers.Dialog.dismiss}});")); ?>
    <?php endif; ?>
    <a class="issue" href="<?php echo make_url('viewissue', array('project_key' => $issue->getProject()->getKey(), 'issue_no' => $issue->getFormattedIssueNo())); ?>">
        <?php echo __('%issuetype %issue_no', array('%issuetype' => (($issue->hasIssueType()) ? $issue->getIssueType()->getName() : __('Unknown issuetype')), '%issue_no' => $issue->getFormattedIssueNo(true))); ?>
        <span title="<?php echo \pachno\core\framework\Context::getI18n()->decodeUTF8($issue->getTitle()); ?>"><?php echo \pachno\core\framework\Context::getI18n()->decodeUTF8($issue->getTitle()); ?></span>
    </a>
    <?php if ($issue->isAssigned()): ?>
        <div class="assignee">
            <?php if ($issue->getAssignee() instanceof \pachno\core\entities\User): ?>
                (<?php echo __('Assigned to %assignee', array('%assignee' => get_component_html('main/userdropdown', array('user' => $issue->getAssignee(), 'show_avatar' => true)))); ?>)
            <?php else: ?>
                (<?php echo __('Assigned to %assignee', array('%assignee' => get_component_html('main/teamdropdown', array('team' => $issue->getAssignee())))); ?>)
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <span class="issue-state <?php echo $issue->isClosed() ? 'closed' : 'open'; ?>"><?php echo $issue->isClosed() ? __('Closed') : __('Open'); ?></span>
    <?php include_component('main/statusbadge', ['status' => $issue->getStatus()]); ?>
</li>
