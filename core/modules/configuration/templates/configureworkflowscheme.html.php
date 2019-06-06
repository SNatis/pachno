<?php

    if ($workflow_scheme instanceof \pachno\core\entities\WorkflowScheme) {
        $pachno_response->setTitle(__('Configure workflow scheme "%workflow_scheme_name"', array('%workflow_scheme_name' => $workflow_scheme->getName())));
    } else {
        $pachno_response->setTitle(__('Configure workflow schemes'));
    }
    
?>
<div class="content-with-sidebar">
    <?php include_component('configuration/sidebar', ['selected_section' => \pachno\core\framework\Settings::CONFIGURATION_SECTION_WORKFLOW]); ?>
    <div class="configuration-container">
        <?php include_component('configuration/workflowmenu', array('selected_tab' => 'scheme', 'scheme' => $workflow_scheme)); ?>
        <div class="content" id="workflow_steps_container">
            <?php if ($workflow_scheme instanceof \pachno\core\entities\WorkflowScheme): ?>
                <div class="greybox workflow_steps_intro">
                    <div class="header"><?php echo __('Workflow scheme "%workflow_scheme_name"', array('%workflow_scheme_name' => $workflow_scheme->getName())); ?></div>
                    <div class="content">
                        <?php echo __('This page shows all the issuetype / workflow associations for the selected workflow scheme. Select the appropriate workflow schemes for each issue type in the list below, and press the "%save_workflow_associations" button when done.', array('%save_workflow_associations' => __('Save workflow assocations'))); ?>
                    </div>
                </div>
                <form action="<?php echo make_url('configure_workflow_scheme', array('scheme_id' => $workflow_scheme->getID())); ?>" onsubmit="Pachno.Config.Workflows.Scheme.update('<?php echo make_url('configure_workflow_scheme', array('scheme_id' => $workflow_scheme->getID())); ?>', <?php echo $workflow_scheme->getID(); ?>); return false;" method="post" id="workflow_scheme_form">
                    <table id="workflow_steps_list" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th><?php echo __('Issue type'); ?></th>
                                <th style="text-align: right;"><?php echo __('Associated workflow'); ?></th>
                            </tr>
                        </thead>
                        <tbody class="padded_table hover_highlight" id="workflow_steps_list_tbody">
                            <?php foreach ($issuetypes as $issuetype): ?>
                                <tr class="step">
                                    <td><?php echo $issuetype->getName(); ?></td>
                                    <td style="text-align: right;">
                                        <select name="workflow_id[<?php echo $issuetype->getID(); ?>]">
                                            <option value=""<?php if (!$workflow_scheme->hasWorkflowAssociatedWithIssuetype($issuetype)): ?> selected<?php endif; ?>><?php echo __('No workflow selected - will use default workflow'); ?></option>
                                            <?php foreach (\pachno\core\entities\Workflow::getAll() as $workflow): ?>
                                                <option value="<?php echo $workflow->getID(); ?>"<?php if ($workflow_scheme->hasWorkflowAssociatedWithIssuetype($issuetype) && $workflow_scheme->getWorkflowForIssuetype($issuetype)->getID() == $workflow->getID()): ?> selected<?php endif; ?>><?php echo $workflow->getName(); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <div style="text-align: right; padding: 10px;">
                    <?php echo image_tag('spinning_16.gif', array('style' => 'margin-right: 5px; display: none;', 'id' => 'workflow_scheme_indicator')); ?>
                    <input type="submit" value="<?php echo __('Save workflow associations'); ?>">
                </div>
                </form>
            <?php else: ?>
                <div class="redbox" id="no_such_workflow_error">
                    <div class="header"><?php echo $error; ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
