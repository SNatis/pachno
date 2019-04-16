<div id="issue-messages-container">
    <div class="issue_info error<?php if (isset($issue_unsaved)): ?> active<?php endif; ?>" id="viewissue_unsaved"<?php if (!isset($issue_unsaved)): ?> style="display: none;"<?php endif; ?>>
        <div class="header"><?php echo __('Could not save your changes'); ?></div>
    </div>
    <div class="issue_info error<?php if ($issue->hasMergeErrors()): ?> active<?php endif; ?>" id="viewissue_merge_errors"<?php if (!$issue->hasMergeErrors()): ?> style="display: none;"<?php endif; ?>>
        <div class="header"><?php echo __('This issue has been changed since you started editing it'); ?></div>
        <div class="content"><?php echo __('Data that has been changed is highlighted in red below. Undo your changes to see the updated information'); ?></div>
    </div>
    <div class="issue_info important" id="viewissue_changed" <?php if (!$issue->hasUnsavedChanges()): ?>style="display: none;"<?php endif; ?>>
        <form action="<?php echo make_url('viewissue', array('project_key' => $issue->getProject()->getKey(), 'issue_no' => $issue->getFormattedIssueNo())); ?>" method="post">
            <div class="buttons">
                <input class="button" type="submit" value="<?php echo __('Save changes'); ?>">
                <button class="button" onclick="$('comment_add_button').hide(); $('comment_add').show();$('comment_save_changes').checked = true;$('comment_bodybox').focus();return false;"><?php echo __('Add comment and save changes'); ?></button>
            </div>
            <input type="hidden" name="issue_action" value="save">
        </form>
        <?php echo __("You have changed this issue, but haven't saved your changes yet. To save it, press the %save_changes button to the right", array('%save_changes' => '<b>' . __("Save changes") . '</b>')); ?>
    </div>
    <?php if (isset($error) && $error): ?>
        <div class="issue_info error" id="viewissue_error">
            <?php if ($error == 'transition_error'): ?>
                <div class="header"><?php echo __('There was an error trying to move this issue to the next step in the workflow'); ?></div>
                <div class="content" style="text-align: left;">
                    <?php include_component('main/issue_transition_error'); ?>
                </div>
            <?php else: ?>
                <div class="header"><?php echo __('There was an error trying to save changes to this issue'); ?></div>
                <div class="content">
                    <?php if (isset($workflow_error) && $workflow_error): ?>
                        <?php echo __('No workflow step matches this issue after changes are saved. Please either use the workflow action buttons, or make sure your changes are valid within the current project workflow for this issue type.'); ?>
                    <?php else: ?>
                        <?php echo $error; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <?php if (isset($issue_saved)): ?>
        <div class="issue_info successful" id="viewissue_saved">
            <?php echo __('Your changes have been saved'); ?>
            <div class="buttons">
                <button class="button" onclick="$('viewissue_saved').hide();"><?php echo __('OK'); ?></button>
            </div>
        </div>
    <?php endif; ?>
    <?php if (isset($issue_message)): ?>
        <div class="issue_info successful" id="viewissue_saved">
            <?php echo $issue_message; ?>
            <div class="buttons">
                <button class="button" onclick="$('viewissue_saved').hide();"><?php echo __('OK'); ?></button>
            </div>
        </div>
    <?php endif; ?>
    <?php if (isset($issue_file_uploaded)): ?>
        <div class="issue_info successful" id="viewissue_saved">
            <?php echo __('The file was attached to this issue'); ?>
            <div class="buttons">
                <button class="button" onclick="$('viewissue_saved').hide();"><?php echo __('OK'); ?></button>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($issue->isBeingWorkedOn() && $issue->isOpen()): ?>
        <div class="issue_info information" id="viewissue_being_worked_on">
            <?php if ($issue->getUserWorkingOnIssue()->getID() == $pachno_user->getID()): ?>
                <?php echo __('You have been working on this issue since %time', array('%time' => \pachno\core\framework\Context::getI18n()->formatTime($issue->getWorkedOnSince(), 6))); ?>
            <?php elseif ($issue->getAssignee() instanceof \pachno\core\entities\Team): ?>
                <?php echo __('%teamname has been working on this issue since %time', array('%teamname' => $issue->getAssignee()->getName(), '%time' => \pachno\core\framework\Context::getI18n()->formatTime($issue->getWorkedOnSince(), 6))); ?>
            <?php else: ?>
                <?php echo __('%user has been working on this issue since %time', array('%user' => $issue->getUserWorkingOnIssue()->getNameWithUsername(), '%time' => \pachno\core\framework\Context::getI18n()->formatTime($issue->getWorkedOnSince(), 6))); ?>
            <?php endif; ?>
            <div class="buttons">
                <button class="button" onclick="$('viewissue_being_worked_on').hide();"><?php echo __('OK'); ?></button>
            </div>
        </div>
    <?php endif; ?>
    <div class="issue_info error" id="blocking_div"<?php if (!$issue->isBlocking()): ?> style="display: none;"<?php endif; ?>>
        <?php echo __('This issue is blocking the next release'); ?>
    </div>
    <?php if ($issue->isDuplicate()): ?>
        <div class="issue_info information" id="viewissue_duplicate">
            <?php echo fa_image_tag('info-circle'); ?>
            <?php echo __('This issue is a duplicate of issue %link_to_duplicate_issue', array('%link_to_duplicate_issue' => link_tag(make_url('viewissue', array('project_key' => $issue->getProject()->getKey(), 'issue_no' => $issue->getDuplicateOf()->getFormattedIssueNo())), $issue->getDuplicateOf()->getFormattedIssueNo(true)) . ' - "' . $issue->getDuplicateOf()->getTitle() . '"')); ?>
        </div>
    <?php endif; ?>
    <?php if ($issue->isClosed()): ?>
        <div class="issue_info information" id="viewissue_closed">
            <?php echo fa_image_tag('info-circle'); ?>
            <?php echo __('This issue has been closed with status "%status_name" and resolution "%resolution".', array('%status_name' => (($issue->getStatus() instanceof \pachno\core\entities\Status) ? $issue->getStatus()->getName() : __('Not determined')), '%resolution' => (($issue->getResolution() instanceof \pachno\core\entities\Resolution) ? $issue->getResolution()->getName() : __('Not determined')))); ?>
        </div>
    <?php endif; ?>
    <?php if ($issue->getProject()->isArchived()): ?>
        <div class="issue_info important" id="viewissue_archived">
            <?php echo image_tag('icon_important.png', array('style' => 'float: left; margin: 0 5px 0 5px;')); ?>
            <?php echo __('The project this issue belongs to has been archived, and so this issue is now read only'); ?>
        </div>
    <?php endif; ?>
</div>
