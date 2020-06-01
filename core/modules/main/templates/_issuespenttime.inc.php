<div class="backdrop_box large" id="viewissue_add_item_div">
    <div class="backdrop_detail_header">
        <a href="javascript:void(0);" class="back_link" onclick="Pachno.UI.Backdrop.show('<?php echo make_url('get_partial_for_backdrop', array('key' => 'issue_spenttimes', 'issue_id' => $entry->getIssue()->getID())); ?>');"><?= fa_image_tag('chevron-left'); ?></a>
        <span><?php echo __('Issue time tracking - edit time spent entry'); ?></span>
        <a href="javascript:void(0);" class="closer" onclick="Pachno.UI.Backdrop.reset();"><?= fa_image_tag('times'); ?></a>
    </div>
    <div id="backdrop_detail_content" class="backdrop_detail_content">
        <form action="<?php echo make_url('issue_edittimespent', array('project_key' => $entry->getIssue()->getProject()->getKey(), 'issue_id' => $entry->getIssueID(), 'entry_id' => $entry->getID())); ?>" onsubmit="Pachno.Issues.editTimeEntry(this);return false;">
            <ul class="simple-list issue_timespent_form">
                <li>
                    <label for="issue_timespent_<?php echo $entry->getID(); ?>_day"><?php echo __('Time logged at'); ?></label>
                    <select id="issue_timespent_<?php echo $entry->getID(); ?>_day" name="edited_at[day]">
                        <?php for($cc = 1; $cc <= 31; $cc++): ?>
                            <option value="<?php echo $cc; ?>"<?php if ($cc == date('d', $entry->getEditedAt())): ?> selected<?php endif; ?>><?php echo $cc; ?></option>
                        <?php endfor; ?>
                    </select>
                    <select id="issue_timespent_<?php echo $entry->getID(); ?>_month" name="edited_at[month]">
                        <?php for($cc = 1; $cc <= 12; $cc++): ?>
                            <option value="<?php echo $cc; ?>"<?php if ($cc == date('m', $entry->getEditedAt())): ?> selected<?php endif; ?>><?php echo date('F', mktime(12, 0, 1, $cc, 1)); ?></option>
                        <?php endfor; ?>
                    </select>
                    <select id="issue_timespent_<?php echo $entry->getID(); ?>_year" name="edited_at[year]">
                        <?php for($cc = 1990; $cc <= date('Y') + 10; $cc++): ?>
                            <option value="<?php echo $cc; ?>"<?php if ($cc == date('Y', $entry->getEditedAt())): ?> selected<?php endif; ?>><?php echo $cc; ?></option>
                        <?php endfor; ?>
                    </select>
                </li>
                <li>
                    <label for="issue_timespent_<?php echo $entry->getID(); ?>_activitytype"><?php echo __('Activity'); ?></label>
                    <select id="issue_timespent_<?php echo $entry->getID(); ?>_activitytype" name="timespent_activitytype">
                        <?php foreach (\pachno\core\entities\ActivityType::getAll() as $activitytype): ?>
                            <option value="<?php echo $activitytype->getID(); ?>" <?php if ($activitytype->getID() == $entry->getActivityTypeID()) echo 'selected'; ?>><?php echo $activitytype->getName(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </li>
                <li>
                    <label for="issue_timespent_<?php echo $entry->getID(); ?>_points"><?php echo __('Points spent'); ?></label>
                    <input type="text" style="width: 40px;" id="issue_timespent_<?php echo $entry->getID(); ?>_points" name="points" value="<?php echo $entry->getSpentPoints(); ?>"><?php echo __('%number_of points', array('%number_of' => '')); ?><br>
                </li>
                <li>
                    <label for="issue_timespent_<?php echo $entry->getID(); ?>_minutes"><?php echo __('Minutes spent'); ?></label>
                    <input type="text" style="width: 40px;" id="issue_timespent_<?php echo $entry->getID(); ?>_minutes" name="minutes" value="<?php echo $entry->getSpentMinutes(); ?>"><?php echo __('%number_of minutes', array('%number_of' => '')); ?><br>
                </li>
                <li>
                    <label for="issue_timespent_<?php echo $entry->getID(); ?>_hours"><?php echo __('Hours spent'); ?></label>
                    <input type="text" style="width: 40px;" id="issue_timespent_<?php echo $entry->getID(); ?>_hours" name="hours" value="<?php echo round($entry->getSpentHours() / 100, 2); ?>"><?php echo __('%number_of hours', array('%number_of' => '')); ?><br>
                </li>
                <li>
                    <label for="issue_timespent_<?php echo $entry->getID(); ?>_days"><?php echo __('Days spent'); ?></label>
                    <input type="text" style="width: 40px;" id="issue_timespent_<?php echo $entry->getID(); ?>_days" name="days" value="<?php echo $entry->getSpentDays(); ?>"><?php echo __('%number_of days', array('%number_of' => '')); ?><br>
                </li>
                <li>
                    <label for="issue_timespent_<?php echo $entry->getID(); ?>_weeks"><?php echo __('Weeks spent'); ?></label>
                    <input type="text" style="width: 40px;" id="issue_timespent_<?php echo $entry->getID(); ?>_weeks" name="weeks" value="<?php echo $entry->getSpentWeeks(); ?>"><?php echo __('%number_of weeks', array('%number_of' => '')); ?><br>
                </li>
                <li>
                    <label for="issue_timespent_<?php echo $entry->getID(); ?>_months"><?php echo __('Months spent'); ?></label>
                    <input type="text" style="width: 40px;" id="issue_timespent_<?php echo $entry->getID(); ?>_months" name="months" value="<?php echo $entry->getSpentMonths(); ?>"><?php echo __('%number_of months', array('%number_of' => '')); ?><br>
                </li>
                <li>
                    <label for="issue_timespent_<?php echo $entry->getID(); ?>_comment" class="optional"><?php echo __('Comment (optional)'); ?></label>
                    <input id="issue_timespent_<?php echo $entry->getID(); ?>_comment" name="timespent_comment" type="text" style="width: 500px;" value="<?php echo htmlentities($entry->getComment(), ENT_COMPAT, \pachno\core\framework\Context::getI18n()->getCharset()); ?>">
                </li>
            </ul>
            <div class="backdrop_details_submit">
                <span class="explanation"></span>
                <div class="submit_container"><input type="submit" class="button" value="<?php echo __('Update entry'); ?>"></div>
            </div>
        </form>
    </div>
</div>
