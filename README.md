# TinyMCE Communicate

An ExpressionEngine extension that adds TinyMCE WYSIWYG editing to the Communicate tab. (Requires Moxiecode's [Tiny MCE](http://tinymce.moxiecode.com/))

## Requirements

TinyMCE Communicate is an [ExpressionEngine](http://expressionengine.com/) extension that can share the settings of [LG TinyMce](http://leevigraham.com/cms-customisation/expressionengine/lg-tinymce/). So far only tested on ExpressionEngine v1.6.7.

Requires Moxiecode's [Tiny MCE](http://tinymce.moxiecode.com/) and [LG TinyMce](http://leevigraham.com/cms-customisation/expressionengine/lg-tinymce/) if "setting sharing" is used (default).

## Installation and activation

1. Upload `extensions/ext.tinymce_communicate.php` to your `system/extensions` folder.
2. Upload `language/english/lang.tinymce_communicate.php` to your `system/language/english` folder.
3. Log into your site's administration, open the **Extensions Manager** and **Enable** the extension.

By default it will search for the LG TinyMce settings, but you can override these by selecting the **Settings**.

## Usage

Log into your site's administration, open the **Communicate** tab and enjoy the WYSIWYG goodness.

If you intend to use this in conjunction with the ImageManager or FileManager plugins then you should ensure TinyMCE is using absolute URLs. Within the LG TinyMCE Config, use:

    // URL
    relative_urls : false,
    remove_script_host : false,
    document_base_url : 'http://www.domain.com/',

This extension is hot off the press so hasn't been tested in a real-life production environment yet. Tread carefully!

## License

Dual licensed under the [MIT](http://www.opensource.org/licenses/mit-license.php) and [GPL](http://www.opensource.org/licenses/gpl-license.php) licenses.

Copyright (c) 2009 [Aaron Russell](http://www.aaronrussell.co.uk).