=== Autonomie ===
Contributors: pfefferle
Tags: custom-menu, custom-colors, custom-header, custom-logo, featured-image-header, flexible-header, sticky-post, microformats, rtl-language-support, translation-ready, full-width-template, post-formats, threaded-comments, footer-widgets, one-column, editor-style, featured-images, theme-options, blog, news, photography, entertainment, wide-blocks
Requires at least: 4.0
Tested up to: 5.5
Stable tag: 1.0.0
Requires PHP: 5.6
Author: Matthias Pfefferle
Author URI: https://notiz.blog/
Donate link: https://notiz.blog/donate/
License: MIT
License URI: https://opensource.org/licenses/mit

Autonomie is a highly semantic, responsive, accessible and search engine optimized WordPress Theme. It provides HTML5 templates refined with microformats, microformats2 and microdata (Schema.org). Autonomie supports a lot of OpenWeb plugins and is fully IndieWeb compatible.

== Description ==

"Autonomie" is a "living theme". I use it for my own blog and so it may change completely over time. Why Autonomie?

"Autonomie" is the german word for "autonomy" which is a synonym to independent!

The theme is based on the (in the IndieWeb community) popular SemPress Theme - <https://github.com/pfefferle/SemPress>

= Thanks =

* Greg Tangey ([Ruxton](https://github.com/Ruxton)) - for the "Post Kinds" and "Syndication Links" implementations

== Frequently Asked Questions ==

= Supported Plugins =

* Post Kinds: <https://wordpress.org/plugins/indieweb-post-kinds/>
* Syndication Links: <https://wordpress.org/plugins/syndication-links/>
* ActivityPub: <https://wordpress.org/plugins/activitypub/>
* PWA: <https://wordpress.org/plugins/pwa/>

= How to install/update the theme =

**Composer**

Install:

	$ composer require pfefferle/wordpress-autonomie --save

Update:

	$ composer update

**GitHub Updater**

You can also use the [GitHub Updater](https://github.com/afragen/github-updater) plugin to install and update the theme.

= Supported Websemantics =

Autonomie' code is marked-up with microformats and microdata:

* used [microformats](http://microformats.org/):
	* [hAtom](http://microformats.org/wiki/hatom)
	* [hCard](http://microformats.org/wiki/hcard)
	* [rel-tag](http://microformats.org/wiki/rel-tag)
	* [XFN](http://microformats.org/wiki/xfn)
* used [microformats version 2](http://microformats.org/wiki/microformats-2):
	* [h-feed](http://microformats.org/wiki/h-feed)/[h-entry](http://microformats.org/wiki/h-entry)
	* [h-card](http://microformats.org/wiki/h-card)
	* [Comment Draft](http://microformats.org/wiki/comment-brainstorming#microformats2_h-feed_p-comments)
* used [microdata](http://www.whatwg.org/specs/web-apps/current-work/multipage/microdata.html):
	* https://schema.org/Blog
	* https://schema.org/BlogPosting
	* https://schema.org/Comment
	* https://schema.org/WebPage
	* https://schema.org/Person

Planned formats:

* micormats (v2): hAudio and hMedia
* microdata: https://schema.org/MediaObject

= IndieWeb Specs =

* [Webactions](https://indieweb.org/webactions) - A web action is the interface and user experience of taking a specific discrete action, across the web, from one site to another site or application.

= What are the WordPress features supported by Autonomie? =

Autonomie supports:

* [Custom Post Formats](http://codex.wordpress.org/Post_Formats): aside, status, gallery, video, audio, chat, quote, link and image
* [Post-Thumbnails](http://codex.wordpress.org/Post_Thumbnails)
* [Editor-Style](http://codex.wordpress.org/Function_Reference/add_editor_style)
* [Navigation Menus](http://codex.wordpress.org/Navigation_Menus)
* [Automatic Feed Links](http://codex.wordpress.org/Automatic_Feed_Links)
* [Custom-Header](http://codex.wordpress.org/Custom_Headers)
* [Custom-Backgrounds](http://codex.wordpress.org/Custom_Backgrounds)
* [Gutenberg/Block-Editor](https://wordpress.org/gutenberg/)
	* [Editor Colors](https://wordpress.org/gutenberg/handbook/extensibility/theme-support/)
	* [Wide Alignment](https://wordpress.org/gutenberg/handbook/extensibility/theme-support/#wide-alignment	)


= What is POSH =

From the [micrormats wiki](http://microformats.org/wiki/posh):

> The term semantic-html is a mouthful, and belies both how simple it is, how well established
> it is among modern web designers, and the fact that it has benefits far beyond the obvious doing
> the right thing for the Web by using semantic markup. We need a simple short mnemonic term that
> captures the essence of the concept, and is easily verbed (to posh, poshify, poshed up).

Autonomie is fully HTML5 compatible and uses a lot of the new tags, semantics and input-types.

== Changelog ==

= 1.0.0 =

* initial release

== Copyright ==

Autonomie is distributed under the terms of the MIT License

Copyright (c) 2021 Matthias Pfefferle

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

Autonomie bundles the following third-party resources:

Some Bootstrap CSS, Copyright Twitter, Inc., The Bootstrap Authors
License: MIT
Source: https://github.com/twbs/bootstrap/

Bundled images (starter content), Copyright Hendrik Cvetko
License: GPL-2.0-or-later
