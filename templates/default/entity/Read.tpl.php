<article class="h-entry h-read">
    <?php

        if (\Idno\Core\site()->template()->getTemplateType() == 'default') {
            ?>
            <h2 class="p-name e-content">
                <a class="u-url" href="<?= $vars['object']->getDisplayURL() ?>">
                   <i class="fa <?= $vars['object']->getCategoryIcon() ?>"></i>
                    <?= htmlentities(strip_tags($vars['object']->getTitle()), ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </h2>
            <?php
        }
    ?>

            <div class="e-content">
                <p><data class="p-read-status" value="<?= $vars['object']->getCategory() ?>"><?php if ($vars['object']->getCategory() == "to-read"){$readState = "Want to Read: ";} elseif ($vars['object']->getCategory() == "reading") {$readState = "Reading: ";} elseif ($vars['object']->getCategory() == "finished") {$readState = "Finished Reading: ";}
 echo $readState; ?></data> <cite class="p-read-of h-cite"><a href="https://isbnsearch.org/isbn/<?= preg_replace("/[^a-zA-Z0-9]+/", "", $vars['object']->getIsbn()) ?>"><?= htmlentities(strip_tags($vars['object']->getTitle()), ENT_QUOTES, 'UTF-8'); ?></a><cite> by <?= htmlentities(strip_tags($vars['object']->getAuthor()), ENT_QUOTES, 'UTF-8'); ?></p><br />
                <?= $this->__(['value' => $vars['object']->body, 'object' => $vars['object']])->draw('forms/output/richtext'); ?>
            </div>

            <div style="display: none;">
                <p class="h-card vcard p-author">
                    <a href="<?= $vars['object']->getOwner()->getURL(); ?>" class="icon-container">
                        <img class="u-logo logo u-photo photo" src="<?= $vars['object']->getOwner()->getIcon(); ?>"/>
                    </a>
                    <a class="p-name fn u-url url" href="<?= $vars['object']->getOwner()->getURL(); ?>"><?= $vars['object']->getOwner()->getName(); ?></a>
                    <a class="u-url" href="<?= $vars['object']->getOwner()->getURL(); ?>">
                        <!-- This is here to force the hand of your MF2 parser --></a>
                </p>
            </div>
</article>
