<?php $canedititem = (($itemtype == 'build' && $issue->canEditAffectedBuilds()) || ($itemtype == 'component' && $issue->canEditAffectedComponents()) || ($itemtype == 'edition' && $issue->canEditAffectedEditions())); ?>
<li id="affected_<?php echo $itemtype; ?>_<?php echo $item['a_id']; ?>" class="affected_item">
    <?php if ($itemtype == 'component'): ?>
        <?php echo fa_image_tag('puzzle-piece', ['title' => $itemtypename, 'class' => 'icon_affected_type']); ?>
    <?php elseif ($itemtype == 'edition'): ?>
        <?php echo fa_image_tag('window-restore', ['title' => $itemtypename, 'class' => 'icon_affected_type'], 'far'); ?>
    <?php else: ?>
        <?php echo fa_image_tag('compact-disc', ['title' => $itemtypename, 'class' => 'icon_affected_type']); ?>
    <?php endif; ?>
    <?php if ($canedititem): ?>
        <a href="javascript:void(0);" class="removelink" onclick="Pachno.Main.Helpers.Dialog.show('<?php echo __('Remove %itemname?', array('%itemname' => $item[$itemtype]->getName())); ?>', '<?php echo __('Please confirm that you want to remove this item from the list of items affected by this issue'); ?>', {yes: {click: function() {Pachno.Issues.Affected.remove('<?php echo make_url('remove_affected', array('issue_id' => $issue->getID(), 'affected_type' => $itemtype, 'affected_id' => $item['a_id'])).'\', '.'\''.$itemtype.'_'.$item['a_id']; ?>');Pachno.Main.Helpers.Dialog.dismiss();}}, no: {click: Pachno.Main.Helpers.Dialog.dismiss}});"><?php echo fa_image_tag('times', array('id' => 'affected_'.$itemtype.'_'.$item['a_id'].'_delete_icon', 'class' => 'delete')); ?></a>
    <?php endif; ?>
    <span class="affected_name"><?php echo $item[$itemtype]->getName(); ?></span>
    <div class="status-badge dropper affected_status" id="affected_<?php echo $itemtype; ?>_<?php echo $item['a_id']; ?>_status" style="background-color: <?php echo ($item['status'] instanceof \pachno\core\entities\Status) ? $item['status']->getColor() : '#FFF'; ?>;" title="<?php echo ($item['status'] instanceof \pachno\core\entities\Datatype) ? __($item['status']->getName()) : __('Unknown'); ?>"><?php echo ($item['status'] instanceof \pachno\core\entities\Datatype) ? $item['status']->getName() : __('Unknown'); ?></div>
    <ul class="rounded_box white shadowed dropdown_box popup_box more_actions_dropdown" id="affected_<?php echo $itemtype; ?>_<?php echo $item['a_id']; ?>_status_change">
        <?php foreach ($statuses as $status): ?>
            <?php if (!$status->canUserSet($pachno_user)) continue; ?>
            <li>
                <a href="javascript:void(0);" onclick="Pachno.Issues.Affected.setStatus('<?php echo make_url('status_affected', array('issue_id' => $issue->getID(), 'affected_type' => $itemtype, 'affected_id' => $item['a_id'], 'status_id' => $status->getID())); ?>', '<?php echo $itemtype.'_'.$item['a_id']; ?>');">
                    <div class="status-badge" style="background-color: <?php echo $status->getColor(); ?>;color: <?php echo $status->getTextColor(); ?>;">
                        <span><?php echo __($status->getName()); ?></span>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>
        <li id="affected_<?php echo $itemtype; ?>_<?php echo $item['a_id']; ?>_status_spinning" style="display: none;"><?php echo image_tag('spinning_20.gif') . '&nbsp;' . __('Please wait'); ?>...</li>
        <li id="affected_<?php echo $itemtype; ?>_<?php echo $item['a_id']; ?>_status_error" class="error_message" style="display: none;"></li>
    </ul>
    <span onclick="Pachno.Issues.Affected.toggleConfirmed('<?php echo make_url('confirm_affected', array('issue_id' => $issue->getID(), 'affected_type' => $itemtype, 'affected_id' => $item['a_id'])); ?>', '<?php echo $itemtype.'_'.$item['a_id']; ?>');" class="affected-state <?php echo ($item['confirmed']) ? 'confirmed' : 'unconfirmed'; ?>"><span id="affected_<?php echo $itemtype; ?>_<?php echo $item['a_id']; ?>_state"><?php echo ($item['confirmed']) ? __('Confirmed') : __('Unconfirmed'); ?></span><?php echo image_tag('spinning_16.gif'); ?></span>
    <?php if ($itemtype == 'build'): ?>
        <span class="faded_out">(<?php echo $item['build']->getVersionMajor().'.'.$item['build']->getVersionMinor().'.'.$item['build']->getVersionRevision(); ?>)</span>
    <?php endif; ?>
</li>
