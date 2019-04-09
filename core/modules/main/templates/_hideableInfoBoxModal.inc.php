<?php if ($show_box): ?>
    <div class="fullpage_backdrop infobox_modal" id="infobox_<?= $key; ?>">
        <div class="backdrop_box large">
            <div class="backdrop_detail_header">
                <span><?= $title; ?></span>
            </div>
            <div class="backdrop_detail_content">
                <?php include_component($template, $options); ?>
            </div>
            <form id="close_me_<?= $key; ?>_form" action="<?= make_url('hide_infobox', array('key' => $key)); ?>" method="post" accept-charset="<?= \pachno\core\framework\Settings::getCharset(); ?>" onsubmit="Pachno.Main.hideInfobox('<?= make_url('hide_infobox', array('key' => $key)); ?>', '<?= $key; ?>');return false;">
                <div class="backdrop_details_submit">
                    <span class="explanation">
                        <input type="checkbox" value="1" class="fancycheckbox" name="dont_show" id="close_me_<?= $key; ?>"><label for="close_me_<?= $key; ?>"><?= fa_image_tag('check-square', ['class' => 'checked'], 'far') . fa_image_tag('square', ['class' => 'unchecked'], 'far') . __("Don't show this again"); ?></label>
                    </span>
                    <div class="submit_container">
                        <button type="submit"><?= image_tag('spinning_16.gif', ['id' => "infobox_{$key}_indicator", 'style' => 'display: none']) . $button_label; ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>
