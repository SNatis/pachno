<?php

    /** @var \pachno\core\entities\User $user */

?>
<div class="backdrop_box x-large usercard" id="user_details_popup">
    <div class="backdrop_detail_header">
        <span><?php echo (!$user->isScopeConfirmed()) ? $user->getUsername() : $user->getRealname() . '(' . $user->getUsername() . ')'; ?></span>
        <a href="javascript:void(0);" class="closer" onclick="Pachno.UI.Backdrop.reset();"><?= fa_image_tag('times'); ?></a>
    </div>
    <div id="backdrop_detail_content" class="backdrop_detail_content rounded_top usercard_content">
        <?php if (!$user->isScopeConfirmed()): ?>
            <div class="user_details">
                <div class="user_realname">
                    <?php echo $user->getUsername(); ?>
                    <div class="user_status"><?php echo __('This user has not been confirmed yet'); ?></div>
                </div>
            </div>
        <?php else: ?>
            <div class="user_profile">
                <div class="user_id"><?php echo $user->getID(); ?></div>
                <div style="padding: 2px; width: 48px; height: 48px; text-align: center; background-color: #FFF; border: 1px solid #DDD; float: left;">
                    <?php echo image_tag($user->getAvatarURL(false), array('alt' => ' ', 'style' => "width: 48px; height: 48px;"), true); ?>
                </div>
                <div class="user_realname">
                    <?php echo $user->getRealname(); ?> <span class="user_username">(<?php echo $user->getUsername(); ?>)</span>
                    <div class="user_status"><?php echo pachno_get_userstate_image($user) . __($user->getState()->getName()); ?></div>
                    <?php if ($user->isEmailPublic() || $pachno_user->canAccessConfigurationPage(\pachno\core\framework\Settings::CONFIGURATION_SECTION_USERS)): ?>
                        <div class="user_email"><?php echo link_tag('mailto:'.$user->getEmail(), $user->getEmail()); ?></div>
                    <?php endif; ?>
                    <?php if (\pachno\core\entities\User::isThisGuest() == false): ?>
                        <div id="friends_message_<?php echo $user->getUsername() . '_' . $rnd_no; ?>" style="padding: 10px 0 0 0; font-size: 0.75em;"></div>
                        <?php if ($user->getID() != \pachno\core\framework\Context::getUser()->getID() && !(\pachno\core\framework\Context::getUser()->isFriend($user)) && !$user->isGuest()): ?>
                            <div id="friends_link_<?php echo $user->getUsername() . '_' . $rnd_no; ?>" class="friends_link">
                        <span style="padding: 2px; <?php if (\pachno\core\framework\Context::getUser()->isFriend($user)): ?> display: none;<?php endif; ?>" id="add_friend_<?php echo $user->getID() . '_' . $rnd_no; ?>">
                            <?php echo javascript_link_tag(__('Become friends'), array('onclick' => "Pachno.Main.Profile.addFriend('".make_url('toggle_friend', array('mode' => 'add', 'user_id' => $user->getID()))."', {$user->getID()}, {$rnd_no});")); ?>
                        </span>
                                <?php echo image_tag('spinning_16.gif', array('id' => "toggle_friend_{$user->getID()}_{$rnd_no}_indicator", 'style' => 'display: none;')); ?>
                                <span style="padding: 2px; <?php if (!\pachno\core\framework\Context::getUser()->isFriend($user)): ?> display: none;<?php endif; ?>" id="remove_friend_<?php echo $user->getID() . '_' . $rnd_no; ?>">
                            <?php echo javascript_link_tag(__('Remove this friend'), array('onclick' => "Pachno.Main.Profile.removeFriend('".make_url('toggle_friend', array('mode' => 'remove', 'user_id' => $user->getID()))."', {$user->getID()}, {$rnd_no});")); ?>
                        </span>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($pachno_user->canAccessConfigurationPage(\pachno\core\framework\Settings::CONFIGURATION_SECTION_USERS)): ?>
                        <div class="edit_user">
                            <form action="<?php echo make_url('configure_users'); ?>">
                                <input type="hidden" name="finduser" value="<?php echo $user->getUsername(); ?>">
                                <a href="javascript:void(0);" onclick="$(this).up('form').submit();"><?php echo __('Edit this user'); ?></a>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="user_details">
                <?php if (!$user->getJoinedDate()): ?>
                    <i><?php echo __('This user has been a member for a while'); ?></i>
                <?php else: ?>
                    <?php echo '<b>' . __('This user has been a member since %date', array('%date' => '</b>' . \pachno\core\framework\Context::getI18n()->formatTime($user->getJoinedDate(), 11))); ?>
                <?php endif; ?>
                <br>
                <?php if (!$user->getLastSeen()): ?>
                    <i><?php echo __('This user has not logged in yet'); ?></i>
                <?php else: ?>
                    <?php echo '<b>' . __('This user was last seen online at %time', array('%time' => '</b>' . \pachno\core\framework\Context::getI18n()->formatTime($user->getLastSeen(), 11))); ?>
                <?php endif; ?>
                <br>
                <?php if (!$user->getLatestActions(1)): ?>
                    <i><?php echo __('There is no recent activity available for this user'); ?></i>
                <?php else: ?>
                    <?php foreach ($user->getLatestActions(1) as $action): ?>
                        <?php echo '<b>' . __('Last user activity was at %time', array('%time' => '</b>' . \pachno\core\framework\Context::getI18n()->formatTime($action->getTime(), 11))); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <br>
                <?php if (count($issues)): ?>
                    <?php echo __('This user has reported %issues issue(s)', array('%issues' => '<b>'.count($issues).'</b>')); ?>
                    <?php echo link_tag(make_url('search', array('search' => true, 'fs[posted_by]' => array('o' => '=', 'v' => $user->getID()))), __('Show issues'), array('class' => 'button', 'title' => __('Show issues reported by this user'))); ?>
                    <?php $seen = 0; ?>
                    <h4><?php echo __('Last reported issues:') . ' '; ?></h4>
                        <ul class="simple-list user_issues_list">
                        <?php foreach ($issues as $issue): ?>
                            <?php if ($issue->hasAccess()): ?>
                                <li>
                                    <span class="faded_out smaller"><?php echo link_tag(make_url('project_dashboard', array('project_key' => $issue->getProject()->getKey())), image_tag($issue->getProject()->getIconName(), array('class' => 'issuelog-project-logo'), true)); ?></span>
                                    <?php echo link_tag(make_url('viewissue', array('project_key' => $issue->getProject()->getKey(), 'issue_no' => $issue->getFormattedIssueNo())), pachno_truncateText($issue->getFormattedTitle(true), 100)); ?>
                                </li>
                                <?php if (++$seen == 7) break; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </ul>
                <?php else: ?>
                    <i><?php echo __('This user has not reported any issues yet'); ?></i>
                <?php endif; ?>
                <br>
                <?php if (count($user->getTeams())): ?>
                    <b><?php echo __('Member of the following teams:</b> %list_of_teams', array('%list_of_teams' => ''), true); ?></b><br>
                    <ul class="teamlist">
                        <?php foreach ($user->getTeams() as $team): ?>
                            <li><?php include_component('main/teamdropdown', array('team' => $team)); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <?php \pachno\core\framework\Event::createNew('core', 'usercardactions_top', $user)->trigger(); ?>
            <?php \pachno\core\framework\Event::createNew('core', 'usercardactions_bottom', $user)->trigger(); ?>
        <?php endif; ?>
    </div>
</div>
