<?php

    use pachno\core\entities\Article;
    use pachno\core\modules\publish\Publish;

    /**
     * @var Article $article
     * @var Publish $publish
     */

    $article_name = $article->getName();
    $publish = \pachno\core\framework\Context::getModule('publish');

?>
<div class="header-container <?= $mode; ?>">
    <div class="title-container article-title">
        <div>
            <span class="title-name">
                <?php if ($article->isCategory()) echo fa_image_tag('layer-group', ['class' => 'icon category']); ?>
                <span><?= ($article->getName() == 'Main Page') ? __('Overview') : $article->getName(); ?></span>
            </span>
        </div>
    </div>
    <?php if ($article->getID() || $mode == 'edit'): ?>
        <?php if ($show_actions): ?>
            <div class="button-group">
                <?php if ($article->getID() && $mode != 'view'): ?>
                    <?= link_tag(make_url('publish_article', array('article_name' => $article->getName())), fa_image_tag('arrow-left'), ['class' => 'button icon secondary']); ?>
                <?php endif; ?>
                <?php if ((isset($article) && $article->canEdit()) || (!isset($article) && ((\pachno\core\framework\Context::isProjectContext() && !\pachno\core\framework\Context::getCurrentProject()->isArchived()) || (!\pachno\core\framework\Context::isProjectContext() && $publish->canUserEditArticle($article_name))))): ?>
                    <?php if ($mode == 'edit'): ?>
                        <?= javascript_link_tag(($article->getID()) ? __('Edit') : __('Create new article'), array('class' => 'button button-pressed')); ?>
                    <?php else: ?>
                        <?= link_tag($article->getLink('edit'), ($article->getID()) ? __('Edit') : fa_image_tag('plus'), ['class' => 'button secondary']); ?>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($mode != 'edit'): ?>
                    <div class="toggle-favourite">
                        <?php if ($pachno_user->isGuest()): ?>
                            <button class="button secondary disabled" disabled>
                                <?= fa_image_tag('star', ['class' => 'unsubscribed']); ?>
                            </button>
                            <div class="tooltip from-above leftie">
                                <?= __('Please log in to subscribe to updates for this article'); ?>
                            </div>
                        <?php else: ?>
                            <div class="tooltip from-above leftie">
                                <?= __('Click the star to toggle whether you want to be notified whenever this article updates or changes'); ?><br>
                            </div>
                            <?= fa_image_tag('spinner', array('id' => 'article_favourite_indicator_'.$article->getId(), 'style' => 'display: none;', 'class' => 'fa-spin')); ?>
                            <button class="button icon secondary" id="article_favourite_faded_<?= $article->getId(); ?>" style="<?= ($pachno_user->isArticleStarred($article->getID())) ? 'display: none;' : ''; ?>" onclick="Pachno.Main.toggleFavouriteArticle('<?= make_url('publish_toggle_favourite_article', ['article_id' => $article->getID(), 'user_id' => $pachno_user->getID()]); ?>', <?= $article->getID(); ?>);">
                                <?= fa_image_tag('star', ['class' => 'unsubscribed']); ?>
                            </button>
                            <button class="button icon secondary" id="article_favourite_normal_<?= $article->getId(); ?>" style="<?= (!$pachno_user->isArticleStarred($article->getID())) ? 'display: none;' : ''; ?>" onclick="Pachno.Main.toggleFavouriteArticle('<?= make_url('publish_toggle_favourite_article', ['article_id' => $article->getID(), 'user_id' => $pachno_user->getID()]); ?>', <?= $article->getID(); ?>);">
                                <?= fa_image_tag('star', ['class' => 'subscribed']); ?>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if (!isset($embedded) || !$embedded): ?>
                    <div class="dropper-container">
                        <a class="button dropper icon secondary"><?= fa_image_tag('ellipsis-v'); ?></a>
                        <div class="dropdown-container">
                            <div class="list-mode">
                                <?php  /*if ($mode == 'edit'): ?>
                                    <a href="javascript:void(0);" onclick="$('main_container').toggleClass('distraction-free');" class="list-item">
                                        <?= fa_image_tag('arrows-alt', ['class' => 'icon']); ?>
                                        <span class="name"><?= __('Toggle distraction-free writing'); ?></span>
                                    </a>
                                    <div class="separator"></div>
                                    <li class="parent_article_selector_menu_entry"><a href="javascript:void(0);" onclick="$('parent_selector_container').toggle();Pachno.Main.loadParentArticles();"><?= fa_image_tag('newspaper') . __('Select parent article'); ?></a></li>
                                <?php endif; */ ?>
                                <?php if ($article->getID()): ?>
                                    <?php if ($mode != 'history'): ?>
                                        <a href="<?= $article->getLink('history'); ?>" class="list-item">
                                            <?= fa_image_tag('history', ['class' => 'icon']); ?>
                                            <span class="name"><?= __('History'); ?></span>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (in_array($mode, array('show', 'edit')) && \pachno\core\framework\Settings::isUploadsEnabled() && $article->canEdit()): ?>
                                        <a href="javascript:void(0);" onclick="Pachno.Main.showUploader('<?= make_url('get_partial_for_backdrop', array('key' => 'uploader', 'mode' => 'article', 'article_name' => $article->getName())); ?>');" class="list-item">
                                            <?= fa_image_tag('paperclip', ['class' => 'icon']); ?>
                                            <span class="name"><?= __('Attach a file'); ?></span>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (isset($article) && $article->canEdit()): ?>
                                        <a href="<?= $article->getLink('permissions'); ?>" class="list-item">
                                            <?= fa_image_tag('lock', ['class' => 'icon']); ?>
                                            <span class="name"><?= __('Permissions'); ?></span>
                                        </a>
                                        <div class="separator"></div>
                                        <?php if (\pachno\core\framework\Context::isProjectContext()): ?>
                                            <?php if ($article->getParentArticle() instanceof Article): ?>
                                                <a href="<?= make_url('publish_project_article_edit', ['article_id' => 0, 'parent_article_id' => $article->getParentArticle()->getID(), 'project_key' => $article->getProject()->getKey()]); ?>" class="list-item">
                                                    <?= fa_image_tag('plus', ['class' => 'icon']); ?>
                                                    <span class="name"><?= __('Create new article here'); ?></span>
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?= make_url('publish_project_article_edit', ['article_id' => 0, 'parent_article_id' => $article->getID(), 'project_key' => $article->getProject()->getKey()]); ?>" class="list-item">
                                                <?= fa_image_tag('plus', ['class' => 'icon']); ?>
                                                <span class="name"><?= __('Create new sub-article'); ?></span>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= make_url('publish_project_article_edit', ['article_id' => 0, 'project_key' => $article->getProject()->getKey()]); ?>" class="list-item">
                                                <?= fa_image_tag('plus', ['class' => 'icon']); ?>
                                                <span class="name"><?= __('Create new top-level article'); ?></span>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <div class="separator"></div>
                                    <?php if ($article->canDelete()): ?>
                                        <?= javascript_link_tag(fa_image_tag('times', ['class' => 'icon']) . '<span class="name">'.__('Delete this article').'</span>', ['onclick' => "Pachno.UI.Dialog.show('".__('Please confirm')."', '".__('Do you really want to delete this article?')."', {yes: {click: function () { Pachno.Main.deleteArticle('".make_url('publish_article_delete', ['article_id' => $article->getID()])."') }}, no: {click: Pachno.UI.Dialog.dismiss}})", 'class' => 'list-item destroy']); ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
