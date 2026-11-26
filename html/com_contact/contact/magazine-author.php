<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Multilanguage;
use Joomla\Component\Content\Site\Helper\RouteHelper;

$app = Factory::getApplication();
if ($app->getTemplate(true)->params->get('presentationMode', 'standard') !== 'magazine') {
    $opLayout = $this->getLayout();
    $this->setLayout('default');
    try { require JPATH_SITE . '/components/com_contact/tmpl/contact/default.php'; }
    finally { $this->setLayout($opLayout); }
    return;
}
// Only the contact's explicitly public editorial biography, never account profile,
// email, phone or other contact details. The native contact view owns contact access.
$contact = $this->item;
if (!$contact->params->get('access-view')) {
    throw new \RuntimeException(Text::_('JERROR_ALERTNOAUTHOR'), 403);
}
require_once __DIR__ . '/../../magazine/context.php';
$opMasthead = opMagazineRegisterMasthead($app);
$e = static fn ($v): string => htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
// Resolve the explicit option from the actual contact menu. Inherited values
// use Joomla's merged contact parameters, not another component's settings.
$active = $app->getMenu()->getActive();
$matches = $active && $active->component === 'com_contact'
    && ($active->query['view'] ?? '') === 'contact'
    && (int) ($active->query['id'] ?? 0) === (int) $contact->id;
$menuParams = $matches ? $active->getParams() : null;
$menuShow = $menuParams?->get('show_articles');
$showArticles = in_array((string) $menuShow, ['0', '1'], true)
    ? (bool) $menuShow : (bool) $contact->params->get('show_articles', ComponentHelper::getParams('com_contact')->get('show_articles', 0));
$articles = [];
if ((int) $contact->user_id > 0 && $showArticles) {
    // Reuse Joomla's article model, including category ACL and publication windows.
    // Do not use ContactModel's preloaded list, which has different category filters.
    $model = $app->bootComponent('com_content')->getMVCFactory()->createModel('Articles', 'Site', ['ignore_request' => true]);
    $model->setState('params', clone ComponentHelper::getParams('com_content'));
    $model->setState('filter.author_id', (int) $contact->user_id);
    $model->setState('filter.author_id.include', true);
    $model->setState('filter.published', 1);
    $model->setState('filter.access', true);
    $model->setState('filter.featured', 'show');
    $model->setState('load_tags', false);
    $model->setState('filter.language', Multilanguage::isEnabled());
    $model->setState('list.ordering', 'a.publish_up');
    $model->setState('list.direction', 'DESC');
    $model->setState('list.start', 0);
    $limit = $menuParams?->get('articles_display_num');
    if ($limit === null || $limit === '' || $limit === 'use_contact') {
        $limit = $contact->params->get('articles_display_num');
    }
    if (!is_numeric($limit)) {
        $limit = ComponentHelper::getParams('com_contact')->get('articles_display_num', 10);
    }
    $model->setState('list.limit', max(0, min(300, (int) $limit)));
    $articles = $model->getItems() ?: [];
}
?>
<section class="op-mag-author">
    <header class="op-mag-author__header">
        <?php if ($contact->params->get('show_image') && $contact->image) : ?>
            <div class="op-mag-author__portrait"><?php echo LayoutHelper::render('joomla.html.image', ['src' => $contact->image, 'alt' => $contact->name]); ?></div>
        <?php endif; ?>
        <div class="op-mag-author__bio">
            <p class="op-mag-card__category"><?php echo $e(Text::_('TPL_OPERAPLUS_MAG_AUTHOR_LABEL')); ?></p>
            <?php if (!$opMasthead) : ?><h1><?php echo $e($contact->name); ?></h1><?php elseif ($opMasthead['title'] !== trim((string) $contact->name)) : ?><h2><?php echo $e($contact->name); ?></h2><?php endif; ?>
            <?php if ($contact->params->get('show_position') && $contact->con_position) : ?><p class="op-mag-author__role"><?php echo $e($contact->con_position); ?></p><?php endif; ?>
            <?php if ($contact->params->get('show_misc') && $contact->misc) : ?><div><?php echo $contact->misc; ?></div><?php endif; ?>
        </div>
    </header>
    <?php if ($showArticles) : ?>
    <section class="op-mag-author__articles">
        <h2><?php echo $e(Text::_('TPL_OPERAPLUS_MAG_AUTHOR_ARTICLES')); ?></h2>
        <?php if (!$articles) : ?><p><?php echo $e(Text::_('TPL_OPERAPLUS_MAG_AUTHOR_EMPTY')); ?></p><?php endif; ?>
        <?php foreach ($articles as $article) : ?>
            <article class="op-mag-author__article">
                <?php echo LayoutHelper::render('joomla.content.intro_image', $article); ?>
                <div><p class="op-mag-card__category"><?php echo $e($article->category_title ?? ''); ?></p>
                <h3><a href="<?php echo $e(Route::_(RouteHelper::getArticleRoute((int) $article->id . ':' . $article->alias, (int) $article->catid, $article->language), false)); ?>"><?php echo $e($article->title); ?></a></h3>
                <?php if ($article->publish_up) : ?><time datetime="<?php echo $e(str_replace(' ', 'T', $article->publish_up)); ?>"><?php echo $e(HTMLHelper::_('date', $article->publish_up, Text::_('DATE_FORMAT_LC3'))); ?></time><?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>
</section>
