<?php
/** Opera+ — Single course article override, Joomla 6. */
defined('_JEXEC') or die;
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;
$item = $this->item;
$isCourse = strtolower((string) ($item->category_alias ?? '')) === 'corsi';
if (!$isCourse) {
    $core = JPATH_SITE . '/components/com_content/tmpl/article/default.php';
    if (is_file($core)) { include $core; return; }
}
$escape = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$images = json_decode((string) ($item->images ?? '{}')) ?: new stdClass();
$cover = (string) (($images->image_fulltext ?? '') ?: ($images->image_intro ?? ''));
$alt = (string) (($images->image_fulltext_alt ?? '') ?: ($images->image_intro_alt ?? $item->title));
$author = trim((string) ($item->created_by_alias ?? ''));
if ($author === '') { $author = trim((string) ($item->author ?? '')); }
$tags = $item->tags->itemTags ?? [];
?>
<article class="op-course-detail" itemscope itemtype="https://schema.org/Article">
 <header class="op-course-detail__header">
  <p class="op-eyebrow">I CORSI DI OPERA+</p>
  <?php if ($author !== '') : ?><p class="op-course-detail__composer"><?php echo $escape($author); ?></p><?php endif; ?>
  <h1 class="op-course-detail__title" itemprop="headline"><?php echo $escape($item->title); ?></h1>
  <?php if ($tags) : ?><div class="op-course-detail__tags" aria-label="Tag">
   <?php foreach ($tags as $tag) : ?><span class="op-course-tag"><?php echo $escape($tag->title ?? ''); ?></span><?php endforeach; ?>
  </div><?php endif; ?>
 </header>
 <?php if ($cover !== '') : ?><img class="op-course-detail__image" src="<?php echo $escape($cover); ?>" alt="<?php echo $escape($alt); ?>" itemprop="image" /><?php endif; ?>
 <div class="op-course-detail__body" itemprop="articleBody">
  <?php echo $item->text ?? (($item->introtext ?? '') . ($item->fulltext ?? '')); ?>
 </div>
</article>