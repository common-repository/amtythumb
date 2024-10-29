=== amtyThumb ===
Contributors: Amit Gupta
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KQJAX48SPUKNC
Tags: thumbnail, amty, image, first-image, Youtube, Vimeo, Dailymotion, Metacafe, Veoh
Requires at least: 2.5
Tested up to: 4.6.1
Stable tag: 4.2.0

Fetch first image of a post and Resize it. Otherwise resize an image.

== Description ==

This plugin lets you fetch first image of any post. It is fully configurable. You can crop an image or resize on the basis of its height, or width, or both.

Ataached/uploaded images might not be displayed in case of localhost.

Features over other plugins:

1. Can extract an image which is either on same server or on remote server
2. Can extract attached images
3. It can fetch first image from the post even if it is a video from 
	a) Youtube
	b) Vimeo
	c) Dailymotion
	d) Metacafe
	e) Veoh
4. You can use it to resize/crop an image instead of fetching it from any post.

visit [my post](https://articlestack.wordpress.com/2012/04/12/why-not-timthumb-but-amtythumb/)	


Remember to pass default image path.

*Note : I am pausing it's development for some time due to other priorities. However I'll keep supporting for any bug, small features, and any request.*

You can know more about furhter releases, support, plans, and my other developments on [git](https://github.com/NaturalIntelligence).


== Installation ==

Installation of plugin is similar to other wordpress plugin.

e.g.

1. Extract `amtyThumb.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress


You may also add amtyThumb_post anywehre in your post or page using short code. For his;

To fetch image from specific post. Resize to half.
	[amtyThumbOnly percent=50 post_id=282]
To fetch image from current post. Resize by its width.(adjust height automatically)
	[amtyThumbOnly width=50]

For any doubt or query visit [wp-thumb-extractor](https://github.com/NaturalIntelligence/wp-thumb-extractor)

Usage examples:

Importatnt:
Width and Height both are supposed to be provided
if constrain is 0 then image will be streched, if it is 1 then it'll be resized in ratio

Resize an image to 25 x 25; default zoom
amty_lead_img(25,25,'','path/to/image.jpg');

Resize an image to 25 x 25, cropping
amty_lead_img(25,25,'','path/to/image.jpg','',1);

Resize an image to 50% the size
amty_lead_img('','','','path/to/image.jpg',50);

Resize an image to 50 pixels wide and autocompute the height
amty_lead_img(50,'','','path/to/image.jpg');

Resize an image to 100 pixels tall and autocompute the width
amty_lead_img('',50,'','path/to/image.jpg');

Resize to 50 pixels width OR 100 pixels tall, whichever resulting image is smaller
amty_lead_img(50,100,1,'path/to/image.jpg');

Resize first image of current post
amty_lead_img($w,$h,1,'','',0);

For direct recaching
use getAmtyThumbRecacheLink($pid) somewhere in your theme.

== Frequently Asked Questions ==


For any doubt or query visit [wp-thumb-extractor](https://github.com/NaturalIntelligence/wp-thumb-extractor)


== Changelog ==

= 4.2.0 =
* Restrict direct access to the admin page

= 4.1.2 =
* bug fix : there were 2 dots before file extension

= 4.1.1 =
* bug fix : auto cache clear on plugin update is failing
* you are required to clear soft cache from plugin admin page after installing the plugin

= 4.1.0 =
* Performance improvment 4 times.

= 4.0.5 =
* bug fix : in saving resized PNG images

= 4.0.4 =
* bug fix

= 4.0.3 =
* fixed a bug in retriving an image from cache

= 4.0.2 =
* fixed a bug in saving all resized images in jpg by default

= 4.0.1 =
* fixed a bug in returning cached image path

= 4.0.0 =
* cache outside plugin folder. So your cache doesnt get empty on plugin update
* offline cache - You can rebuild cache for all or particular posts directly through option page. It saves client response time for posts which are not cached yet.
* HTML design fix amty thumb option page.

= 3.2.0 =
* improved quality of PNG and GIF files resizing

= 3.1.2 =
* fixed syntax error

= 3.1.1 =
* direct recache link.

= 3.0.1 =
* fixed a bug when it tries to resize invalid or broken images

= 3.0.0 =
* performance improvement
* You can see broken cached images from amty Thumb admin page.
* first image will be cached whenever the page gets published.

= 2.1.2 =
* added missed files

= 2.1.1 =
* fixed a bug when image url is not valid or it is removed. A thumbnail will not be generated.

= 2.0.1 =
* just modified the docs for end user to tell how to use this plugin

= 2.0 =
* security - image resizing utility can be called internally using post id instead of independent url. So noone else can use your bandwidth.
* caching - It caches first image url of all posts and tagged with post itself to save runtime processing.
* filesystem - Now it caches resized images on your site itself. It saves repeated on the fly resizing thus high CPU and memory.

= 1.2.1 =
* 404 error resolved

= 1.2 =
* Performance Improvement.
* Support for more video services like dailymotion,metacafe,veoh,vimeo etc.

= 1.1 =
* missing code is added to fetch uploaded and attached images.

== Upgrade Notice ==

= 4.1.2 =
* perofrmance improvment
* you require to clear soft cache from plugin admin page after installing the plugin

= 4.0.5 =
* bug fix : in saving resized PNG images

= 4.0.4 =
* bug fix

= 4.0.3 =
* fixed a bug in retriving an image from cache

= 4.0.2 =
* fixed a bug in saving all resized images in jpg by default

= 4.0.1 =
* fixed a bug in returning cached image path

= 4.0.0 =
* cache outside plugin folder. So your cache doesnt get empty on plugin update
* offline cache - You can rebuild cache for all or particular posts directly through option page. It saves client response time for posts which are not cached yet.
* HTML design fix amty thumb option page.