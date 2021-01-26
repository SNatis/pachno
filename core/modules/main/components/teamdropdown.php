<?php if (!$team instanceof \pachno\core\entities\Team || $team->getID() == 0): ?>
    <span class="faded_out"><?php echo __('No such team'); ?></span>
<?php else: ?>
<div class="teamdropdown_container dropper-container">
    <a href="javascript:void(0);" class="dropper userlink<?php if ($pachno_user->isMemberOfTeam($team)) echo ' friend'; ?>">
        <?php echo image_tag('icon_team.png', array('class' => "avatar small team-avatar")); ?>
        <?php echo isset($displayname) && is_string($displayname) ? $displayname : $team->getName(); ?>
    </a>
    <ul class="rounded_box white shadowed team_dropdown popup_box dropdown-container more_actions_dropdown">
        <li class="header">
            <div class="team_image_container">
                <?php echo image_tag('team_large.png', array('alt' => ' ', 'style' => "width: 36px; height: 36px;")); ?>
            </div>
            <div class="team_name_container">
                <div class="team_name"><?php echo $team->getName(); ?></div>
                <?php if ($pachno_user->isMemberOfTeam($team)): ?>
                    <div class="team_status"><?php echo ($team->isOndemand()) ? __('You are working together') : __('You are on this team'); ?></div>
                <?php endif; ?>
            </div>
        </li>
        <?php if (!$team->isOndemand()): ?>
            <li><?php echo link_tag(make_url('team_dashboard', array('team_id' => $team->getID())), __('Show team dashboard')); ?></li>
        <?php endif; ?>
        <?php \pachno\core\framework\Event::createNew('core', 'teamactions_bottom', $team)->trigger(); ?>
    </ul>
</div>
<?php endif; ?>
