<?php if ($pachno_user->hasProjectPageAccess('project_planning', $project) || $pachno_user->hasProjectPageAccess('project_only_planning', $project)): ?>
    <div class="list-item expandable <?php if (in_array($pachno_response->getPage(), ['project_planning', 'agile_board', 'agile_whiteboard'])): ?> selected expanded<?php endif; ?>">
        <a href="<?= make_url('agile_index', ['project_key' => $project->getKey()]); ?>">
            <?= fa_image_tag('calendar-check', ['class' => 'icon']); ?>
            <span class="name"><?= __('Planning & boards'); ?></span>
        </a>
        <a href="<?= make_url('agile_index', ['project_key' => $project->getKey()]); ?>" class="icon"><?= fa_image_tag('cog'); ?></a>
        <?= fa_image_tag('angle-down', ['class' => 'expander']); ?>
    </div>
    <div class="submenu list-mode">
        <div class="header"><?= __('Project boards'); ?></div>
        <?php if (count($boards)): ?>
            <?php foreach ($boards as $board): ?>
                <a href="<?= make_url((!$pachno_user->hasProjectPageAccess('project_planning', $project) && $pachno_user->hasProjectPageAccess('project_only_planning', $project) ? 'agile_board' : 'agile_whiteboard'), ['project_key' => $board->getProject()->getKey(), 'board_id' => $board->getID()]); ?>" class="list-item <?php if ($pachno_request['board_id'] == $board->getID()) echo ' selected'; ?>"><span class="name"><?= $board->getName(); ?></span></a>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="list-item disabled"><span class="name"><?= __('No project boards available'); ?></span></div>
        <?php endif; ?>
    </div>
<?php endif; ?>
