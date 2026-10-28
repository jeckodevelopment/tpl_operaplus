<?php
/** Opera+ category blog override. Joomla 6 */
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\Component\Content\Site\Helper\RouteHelper;

$category = $this->category ?? null;
$isCourses = $category && strtolower((string) ($category->alias ?? '')) === 'corsi';
if (!$isCourses) {
    // Delegate untouched non-course categories to Joomla's native blog layout.
    $coreLayout = JPATH_SITE . '/components/com_content/tmpl/category/blog.php';
    if (is_file($coreLayout)) { include $coreLayout; return; }
}
$escape = static fn($s) => htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$articles = array_merge((array) ($this->lead_items ?? []), (array) ($this->intro_items ?? []));
?>
<section class="op-course-index" aria-label="<?php echo $escape($this->category->title ?? 'Corsi'); ?>">
  <header class="op-course-index__header">
    <p class="op-eyebrow">I PERCORSI DI OPERA+</p>
    <h1><?php echo $escape($this->category->title ?? 'I corsi'); ?></h1>
    <?php if (!empty($this->category->description)) : ?><div class="op-course-index__description"><?php echo $this->category->description; ?></div><?php endif; ?>
  </header>
  <?php if ($articles) : ?>
  <div class="op-course-index__items">
  <?php foreach ($articles as $item) :
      $images = json_decode((string) ($item->images ?? '{}')) ?: new stdClass();
      $image = (string) ($images->image_intro ?? '');
      $alt = (string) ($images->image_intro_alt ?? $item->title);
      $composer = trim((string) ($item->created_by_alias ?? ''));
      if ($composer === '' && !empty($item->author)) { $composer = (string) $item->author; }
      $url = Route::_(RouteHelper::getArticleRoute((int) $item->id, (int) $item->catid, (string) ($item->language ?? '*')));
      $tags = $item->tags->itemTags ?? [];
  ?>
    <article class="op-course-item">
      <a class="op-course-item__visual" href="<?php echo $escape($url); ?>" aria-label="<?php echo $escape($item->title); ?>">
        <?php if ($image !== '') : ?><img src="<?php echo $escape($image); ?>" alt="<?php echo $escape($alt); ?>" loading="lazy" /><?php else : ?><div class="op-course-item__placeholder">Opera+</div><?php endif; ?>
      </a>
      <div class="op-course-item__copy">
        <?php if ($composer !== '') : ?><p class="op-course-item__composer"><?php echo $escape($composer); ?></p><?php endif; ?>
        <h2><a href="<?php echo $escape($url); ?>"><?php echo $escape($item->title); ?></a></h2>
        <?php if (!empty($item->introtext)) : ?><div class="op-course-item__intro"><?php echo $item->introtext; ?></div><?php endif; ?>
        <?php if ($tags) : ?><div class="op-course-item__tags" aria-label="Tag"><?php foreach ($tags as $tag) : ?><span class="op-course-tag"><?php echo $escape($tag->title ?? ''); ?></span><?php endforeach; ?></div><?php endif; ?>
        <a class="op-button" href="<?php echo $escape($url); ?>">Scopri il corso <span aria-hidden="true">↗</span></a>
      </div>
    </article>
  <?php endforeach; ?>
  </div>
  <?php else : ?><p>Non ci sono ancora corsi pubblicati.</p><?php endif; ?>
  <?php if (!empty($this->pagination) && $this->pagination->pagesTotal > 1) : ?><nav class="op-pagination" aria-label="Pagine dei corsi"><?php echo $this->pagination->getPagesLinks(); ?></nav><?php endif; ?>
</section>