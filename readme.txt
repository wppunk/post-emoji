=== Post emoji ===
Contributors: wppunk
Donate link: https://www.liqpay.ua/api/3/checkout?data=eyJ2ZXJzaW9uIjozLCJhY3Rpb24iOiJwYXlkb25hdGUiLCJwdWJsaWNfa2V5IjoiaTM0ODU5MzcyNjEwIiwiYW1vdW50IjoiMCIsImN1cnJlbmN5IjoiVUFIIiwiZGVzY3JpcHRpb24iOiLQodC%2F0LDRgdC40LHQviDQsNCy0YLQvtGA0YMg0LfQsCBTaGlwcGluZyBOb3ZhIFBvc2h0YSBmb3IgV29vQ29tbWVyY2UiLCJ0eXBlIjoiZG9uYXRlIiwibGFuZ3VhZ2UiOiJydSJ9&signature=rGy8tJ7N1bDPT8o0wxvI0G59vRw%3D
Tags: emoji, posts vote, эмоции, голосование
Requires at least: 5.5
Tested up to: 5.5
Stable tag: 1.0.0
Requires PHP: 5.6
License: MIT

Plugin description in search plugin.

== Description ==

Plugin description on plugin page.

= Features =
* Emojies for the posts

== Installation ==

1. Upload `emoji` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= How to add emoji to the posts =
In the `single.php` you can add the next code

```
if ( function_exists( 'the_emoji' ) ) {
	the_emoji();
}
```

Or in `functions.php`

```
add_filter( 'the_content', 'add_emoji_after_content' );
function add_emoji_after_content( $content ) {
	if ( ! function_exists( 'the_emoji' ) ) {
		return $content;
	}
	return $content . get_emoji();
}
```

= How to print emoji count in the loop =

```
if ( function_exists( 'the_emoji_count' ) {
	the_emoji_count( get_the_ID() );
}
```

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
* Initial release

== Screenshots ==

1. /assets/img/screenshot-1.png
