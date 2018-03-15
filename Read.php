<?php


    namespace IdnoPlugins\Read {

        use Idno\Core\Autosave;

        class Read extends \Idno\Common\Entity
        {
            public $mapping = array(
                'to-read' => 'fa-bookmark',
                'reading' => 'fa-book',
                'finished' => 'fa-book',
            );

            function getTitle()
            {
                if (empty($this->title)) return 'Untitled';

                return $this->title;
            }

            function getAuthor()
            {
                if (empty($this->author)) return 'Unkown';

                return $this->author;
            }

            function getIsbn()
            {
                if (empty($this->isbn)) return '';

                return $this->isbn;
            }

            function getCategoryIcon()
            {
                return $this->mapping[$this->category];
            }

            function getDescription()
            {
                if (!empty($this->body)) return $this->body;

                return '';
            }

            function getCategory()
            {
                if (!empty($this->category)) return $this->category;

                return '';
            }

            function getURL()
            {

                // If we have a URL override, use it
                if (!empty($this->url)) {
                    return $this->url;
                }

                if (!empty($this->canonical)) {
                    return $this->canonical;
                }

                if (!$this->getSlug() && ($this->getID())) {
                    return \Idno\Core\site()->config()->url . 'read/' . $this->getID() . '/' . $this->getPrettyURLTitle();
                } else {
                    return parent::getURL();
                }

            }

            /**
             * Read objects have type 'read'
             * @return 'read'
             */
            function getActivityStreamsObjectType()
            {
                return 'read';
            }

            function saveDataFromInput()
            {

                if (empty($this->_id)) {
                    $new = true;
                } else {
                    $new = false;
                }
                $body = \Idno\Core\site()->currentPage()->getInput('body');

                $this->body            = $body;
                $this->title           = \Idno\Core\site()->currentPage()->getInput('title');
                $this->category        = \Idno\Core\site()->currentPage()->getInput('category');
                $this->author          = \Idno\Core\site()->currentPage()->getInput('author');
                $this->isbn          = \Idno\Core\site()->currentPage()->getInput('isbn');
                $access                = \Idno\Core\site()->currentPage()->getInput('access');
                $this->setAccess($access);

                if (empty($this->category)) {
                    $this->category = 'ate';
                }

                if ($time = \Idno\Core\site()->currentPage()->getInput('created')) {
                    if ($time = strtotime($time)) {
                        $this->created = $time;
                    }
                }

                if ($new) {
                    if (!empty($_FILES['photo']['tmp_name'])) {
                        if (\Idno\Entities\File::isImage($_FILES['photo']['tmp_name'])) {

                            // Extract exif data so we can rotate
                            if (is_callable('exif_read_data') && $_FILES['photo']['type'] == 'image/jpeg') {
                                try {
                                    if (function_exists('exif_read_data')) {
                                        if ($exif = exif_read_data($_FILES['photo']['tmp_name'])) {
                                            $this->exif = base64_encode(serialize($exif)); // Yes, this is rough, but exif contains binary data that can not be saved in mongo
                                        }
                                    }
                                } catch (\Exception $e) {
                                    $exif = false;
                                }
                            } else {
                                $exif = false;
                            }

                            if ($photo = \Idno\Entities\File::createFromFile($_FILES['photo']['tmp_name'], $_FILES['photo']['name'], $_FILES['photo']['type'], true, true)) {
                                $this->attachFile($photo);

                                // Now get some smaller thumbnails, with the option to override sizes
                                $sizes = \Idno\Core\site()->events()->dispatch('photo/thumbnail/getsizes', new \Idno\Core\Event(array('sizes' => array('large' => 800, 'medium' => 400, 'small' => 200))));
                                $eventdata = $sizes->data();
                                foreach ($eventdata['sizes'] as $label => $size) {

                                    $filename = $_FILES['photo']['name'];

                                    if ($thumbnail = \Idno\Entities\File::createThumbnailFromFile($_FILES['photo']['tmp_name'], "{$filename}_{$label}", $size, false)) {
                                        $varname        = "thumbnail_{$label}";
                                        $this->$varname = \Idno\Core\site()->config()->url . 'file/' . $thumbnail;

                                        $varname        = "thumbnail_{$label}_id";
                                        $this->$varname = substr($thumbnail, 0, strpos($thumbnail, '/'));
                                    }
                                }
                            }
                        } else {
                            \Idno\Core\site()->session()->addErrorMessage('This doesn\'t seem to be an image ..');
                        }
                    }
                }

                if ($this->save($new)) {

                    $autosave = new Autosave();
                    $autosave->clearContext('read');

                    \Idno\Core\Webmention::pingMentions($this->getURL(), \Idno\Core\site()->template()->parseURLs($this->getTitle() . ' ' . $this->getDescription()));

                    return true;
                }

                return false;
            }

            function deleteData()
            {
                \Idno\Core\Webmention::pingMentions($this->getURL(), \Idno\Core\site()->template()->parseURLs($this->getTitle() . ' ' . $this->getDescription()));
            }

        }

    }
