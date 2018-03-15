<?= $this->draw('entity/edit/header'); ?>
<?php

    $autosave = new \Idno\Core\Autosave();
    if (!empty($vars['object']->body)) {
        $body = $vars['object']->body;
    } else {
        $body = $autosave->getValue('read', 'bodyautosave');
    }
    if (!empty($vars['object']->title)) {
        $title = $vars['object']->title;
    } else {
        $title = $autosave->getValue('read', 'title');
    }
    if (!empty($vars['object']->author)) {
        $author = $vars['object']->author;
    } else {
        $author = $autosave->getValue('read', 'author');
    }
    if (!empty($vars['object']->isbn)) {
        $isbn = $vars['object']->isbn;
    } else {
        $isbn = $autosave->getValue('read', 'isbn');
    }
    if (!empty($vars['object']->category)) {
        $category = $vars['object']->category;
    } else {
        $category = $autosave->getValue('read', 'category');
    }
    if (!empty($vars['object'])) {
        $object = $vars['object'];
    } else {
        $object = false;
    }

    /* @var \Idno\Core\Template $this */

?>
    <form action="<?= $vars['object']->getURL() ?>" method="post" enctype="multipart/form-data">

        <div class="row">

            <div class="col-md-8 col-md-offset-2 edit-pane">


                <?php

                    if (empty($vars['object']->_id)) {

                        ?>
                        <h4>Log Books or Articles Read</h4>
                    <?php

                    } else {

                        ?>
                        <h4>Edit Log</h4>
                    <?php

                    }

                ?>


                <div class="content-form">

                    <style>
                        .category-block {
                            margin-bottom: 1em;
                        }
                    </style>
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" placeholder="What the book?" value="<?= htmlspecialchars($title) ?>" class="form-control"/>
                    <label for="author">Author</label>
                    <input type="text" name="author" id="author" placeholder="What wrote it?" value="<?= htmlspecialchars($author) ?>" class="form-control"/>
                    <label for="isbn">isbn</label>
                    <input type="text" name="isbn" id="isbn" placeholder="ISBN" value="<?= htmlspecialchars($isbn) ?>" class="form-control"/>
                    <!-- styled category -->
                    <label for="category">Category</label>
                    <div class="category-block">
                        <input type="hidden" name="category" id="category-id" value="<?= $category ?>">
                        <div id="category" class="category">
                            <div class="btn-group">
                                <a class="btn dropdown-toggle category" data-toggle="dropdown" href="#" id="category-button" aria-expanded="false">
                                    <i class="fa fa-book"></i> Status? <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="#" data-category="to-read" class="category-option"><i class="fa fa-bookmark"></i> To Read </a></li>
                                    <li><a href="#" data-category="reading" class="category-option"><i class="fa fa-book"></i> Reading </a></li>
                                    <li><a href="#" data-category="finished" class="category-option"><i class="fa fa-book"></i> Finished </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <style>
                        a.category {
                            background-color: #fff;
                            background-image: none;
                            border: 1px solid #cccccc;
                            box-shadow: none;
                            text-shadow: none;
                            color: #555555;
                        }

                        .category .caret {
                            border-top: 4px solid #555;
                        }
                    </style>
                    <script>
                        $(document).ready(function () {
                            $('.category-option').each(function () {
                                if ($(this).data('category') == $('#category-id').val()) {
                                    $('#category-button').html($(this).html() + ' <span class="caret"></span>');
                                }
                            })
                        });
                        $('.category-option').on('click', function () {
                            $('#category-id').val($(this).data('category'));
                            $('#category-button').html($(this).html() + ' <span class="caret"></span>');
                            $('#category-button').click();
                            return false;
                        });

                        $('#category-id').on('change', function () {
                        });
                    </script>
                    <!-- end styled category -->
                </div>

                <label for="body">Notes or Review</label>
                <?= $this->__([
                    'name' => 'body',
                    'value' => $body,
                    'object' => $object,
                    'wordcount' => true
                ])->draw('forms/input/richtext')?>
                <?= $this->draw('entity/tags/input'); ?>

                <?php if (empty($vars['object']->_id)) echo $this->drawSyndication('article'); ?>
                <?php if (empty($vars['object']->_id)) { ?><input type="hidden" name="forward-to" value="<?= \Idno\Core\site()->config()->getDisplayURL() . 'content/all/'; ?>" /><?php } ?>

                <?= $this->draw('content/access'); ?>

                <p class="button-bar ">

                    <?= \Idno\Core\site()->actions()->signForm('/read/edit') ?>
                    <input type="button" class="btn btn-cancel" value="Cancel" onclick="tinymce.EditorManager.execCommand('mceRemoveEditor',true, 'body'); hideContentCreateForm();"/>
                    <input type="submit" class="btn btn-primary" value="Publish"/>

                </p>

            </div>

        </div>
    </form>

    <div id="bodyautosave" style="display:none"></div>
<?= $this->draw('entity/edit/footer'); ?>
