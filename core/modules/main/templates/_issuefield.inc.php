<?php

    if (in_array($field, array('priority'))) $primary = true;

?>
<li id="<?php echo $field; ?>_field" <?php if (!$info['visible']): ?> style="display: none;"<?php endif; ?>>
    <dl>
        <dt id="<?php echo $field; ?>_header">
            <?php echo $info['title']; ?>
        </dt>
        <dd id="<?php echo $field; ?>_content" class="<?php if (isset($info['extra_classes'])) echo $info['extra_classes']; ?>">
            <?php $canEditField = "canEdit".ucfirst($field); ?>
            <?php if (array_key_exists('choices', $info) && count($info['choices']) && $issue->$canEditField()): ?>
                <a href="javascript:void(0);" class="dropper dropdown_link"><?php echo image_tag('tabmenu_dropdown.png', array('class' => 'dropdown')); ?></a>
                <ul class="popup_box more_actions_dropdown with-header" id="<?php echo $field; ?>_change">
                    <li class="header"><?php echo $info['change_header']; ?></li>
                    <li>
                        <a href="javascript:void(0);" onclick="Pachno.Issues.Field.set('<?php echo make_url('issue_setfield', array('project_key' => $issue->getProject()->getKey(), 'issue_id' => $issue->getID(), 'field' => $field, $field . '_id' => 0)); ?>', '<?php echo $field; ?>');"><?php echo fa_image_tag('times') . $info['clear']; ?></a>
                    </li>
                    <?php if (count($info['choices'])): ?>
                        <li class="separator"></li>
                        <?php foreach ($info['choices'] as $choice): ?>
                            <?php if ($choice instanceof \pachno\core\entities\DatatypeBase && !$choice->canUserSet($pachno_user)) continue; ?>
                            <li>
                                <a href="javascript:void(0);" onclick="Pachno.Issues.Field.set('<?php echo make_url('issue_setfield', array('project_key' => $issue->getProject()->getKey(), 'issue_id' => $issue->getID(), 'field' => $field, $field . '_id' => $choice->getID())); ?>', '<?php echo $field; ?>');" <?php if ($choice instanceof \pachno\core\entities\Priority): ?>class="priority priority_<?= $choice->getValue(); ?>"<?php endif; ?>>
                                    <?php if ($choice->getFontAwesomeIcon()): ?>
                                        <?php echo fa_image_tag($choice->getFontAwesomeIcon(), [], $choice->getFontAwesomeIconStyle()).__($choice->getName()); ?>
                                    <?php elseif (isset($info['fa_icon'])): ?>
                                        <?php echo fa_image_tag($info['fa_icon'], [], $info['fa_icon_style']).__($choice->getName()); ?>
                                    <?php else: ?>
                                        <?php echo image_tag('icon_' . $field . '.png').__($choice->getName()); ?>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <li id="<?php echo $field; ?>_spinning" style="margin-top: 3px; display: none;"><?php echo image_tag('spinning_20.gif', array('style' => 'float: left; margin-right: 5px;')) . '&nbsp;' . __('Please wait'); ?>...</li>
                    <?php else: ?>
                        <li class="faded_out"><?php echo __('No choices available'); ?></li>
                    <?php endif; ?>
                    <li id="<?php echo $field; ?>_change_error" class="error_message" style="display: none;"></li>
                </ul>
            <?php endif; ?>
            <?php if (array_key_exists('url', $info) && $info['url']): ?>
                <a id="<?php echo $field; ?>_name"<?php if (!$info['name_visible']): ?> style="display: none;"<?php endif; ?> target="_new" href="<?php echo $info['current_url']; ?>"><?php echo $info['name']; ?></a>
            <?php else: ?>
                <span id="<?php echo $field; ?>_name"<?php if (!$info['name_visible']): ?> style="display: none;"<?php endif; ?>>
                    <?php if (isset($info['fa_icon'])) echo fa_image_tag($info['fa_icon'], [], $info['fa_icon_style']); ?>
                    <?php echo __($info['name']); ?>
                </span>
            <?php endif; ?>
            <span class="faded_out" id="no_<?php echo $field; ?>"<?php if (!$info['noname_visible']): ?> style="display: none;"<?php endif; ?>><?php echo __('Not determined'); ?></span>
        </dd>
    </dl>
    <div style="clear: both;"> </div>
</li>
