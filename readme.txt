=== Review Ratings ===
Contributors: Joen
Tags: rating, stars, movies, reviews, shortcodes, wysiwyg, tinymce, quicktags
Requires at least: 2.6
Tested up to: 3.6
Stable tag: 1.6

Easily insert star ratings for your movie or book reviews.

== Description ==

Simple plugin for inserting ratings into WordPress posts. Adds a shortcode: `[rating=5]`, which allow you to insert a movie or book rating (a four star rating looks like this: &#9733;&#9733;&#9733;&#9733;)

== Installation ==

1. Upload the plugin to your wp-content/plugins directory
2. Activate the plugin

If you want to, you can replace the unicode symbols with images using only CSS (<a href="http://noscope.com/n/8">instructions</a>).

There's a button in both the visual and HTML editor to easily insert the tag. There's also an options page which, among other things, allows you to replace the default star character with any other unicode character, for instance, a &hearts;.


== Screenshots ==

1. This is a rating inserted using the shorttag.

== Changelog ==

* 1.0: First release
* 1.1: Made sure "empty" stars didn't show up in feeds, where the CSS would make it look as though they were black like the "full" stars, hence indicationg a 6 star rating always. Also fixed a problem with the default symbol not working.
* 1.2: Added shorter syntax.
* 1.2.5: Wrapped the rating in "wpautop" to ensure it flows properly in your content.
* 1.5: New feature: [relatedratings=x] shows y other posts containing a rating of x stars. Also added buttons in both the visual and HTML editor for inserting such ratings.
* 1.5.2: Tweaked relatedratings to filter out post revisions. Let me know if it doesn't work. Also made it the plugin translatable. Also renamed the plugin to better indicate what the plugin does. 
* 1.5.3: Small, not so elegant fix (for the moment, the button hover text may be untranslatable), which makes the adds 3.1 compatability.
* 1.6: Removed the "relatedratings" shortcode, I never got it to work properly performance wasn't great. 