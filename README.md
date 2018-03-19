Read for Known
==============

Adds support for publishing a log of what you eat and drink to the [Known
CMS](http://withknown.com). To install, simply clone this repository to your
`IdnoPlugins` subdirectory of your Known installation, using the directory name
`Read`, and enable the plugin.

This is currently using a <a href...> to a php file outside of the plugin in order
to link the book to Amazon, if you don't want to make that you will need to remove
that linking in `templates/default/entity/Read/Read.tpl.php`

Read more about read posts: https://indieweb.org/read

Based on https://github.com/cleverdevil/Known-Food

TODO:
-----
- Put link support in plugin (move isbn.php into plugin)
- Add POSSE for Goodreads
  - https://silo.pub/developers#vocab-goodreads
  - Or write it using API (looks like this is the better option, as it is more
    flexible. For example it will allow for the ability to mark progress, etc.
    Just a lot more work.)
- Format (Not sure on the best option)
  - Add u-in-reply-to linking the finished with to-read, reading
  - Create a custom page that groups all the states for each book together
- Clean up big bad if...elseif for $readState
- Add if statement for link, only put in link if ISBN has a value.

DONE:
-----
- Add Author variable
- Add ISBN variable
