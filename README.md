Acetone
=======

Laravel4 package for purging, refreshing &amp; banning cached items in Varnish

Using web accelerators such as varnish to speed up your web application has become common place. This small package is designed to alleviate the problem of cache expiration with dynamic data generated sites.

Installation
=======

Begin by adding the following to your `composer.json`

    "require": {
        "jdare/acetone": "dev-master"
    }
  
Run a composer update `composer update`


Add it to your list of providers in laravel `/app/app.php`

    'providers' => array(
        ...
        'JDare\Acetone\AcetoneServiceProvider',
    ),



Publish the configuration file using `php artisan config:publish jdare/acetone`

Configuration
=======

Open the newly generated config file `app/config/packages/jdare/acetone/config.php`

The configurable options are:

* Server Address
* Force Exceptions
* Ban URL Header

The documentation for each option is available inside the file.


Usage 
=======

_Please Note_: All these functions assume some sort of standard VCL setup for purging/refreshing/banning. Please check [the docs folder](/docs/sample.default.vcl) for a reference on how your varnish should interpret these requests.

For the differences between Purge, Refresh and Ban, please check the [Varnish documentation](https://www.varnish-cache.org/docs/3.0/tutorial/purging.html).

##Purge &amp; Refresh
When you need to invalidate your cache, you can provide a URL to be removed as a parameter for Acetones functions. Here are some sample usages:
    
    function someAction()
    {
        //update some information in a template or database
        
        Acetone::purge("/post/my-updated-post"); //Removed old version from Varnish
        
        //Alternatively use Acetone::refresh("/post/my-updated-post") if it suits your caching needs better.
    }

Both Purge and Refresh will accept arrays of URL's, however be warned each url will need an individual request to varnish. This can end up having a large overhead and can massively increase your response time.

To better invalidate mass URL's use Ban or BanMany

##Ban &amp; BanMany

Ban will work the same way as Purge and Refresh if needed, however it can also accept an optional parameter to make it match a Regex string rather than a URL.

    function someAction()
    {
        //update some information in a template or database
        
        Acetone::ban("^/post", true); //will remove any URL's matching "/post(.*)"
    }

A helper function is available for mass banning, called "banMany", e.g.

    Acetone::banMany("/post"); //will ban any URL starting with /post
    
ban &amp; banMany are much more efficient ways to remove lots of URL's due to Varnish' pattern matching, rather than having to make a single request for every URL.

FAQ
=========

Purge returns a 404 exception.

`Client error response [status code] 404 [reason phrase] Not in cache [url] *URL*`

If your VCL is setup to throw a 404 when a purge item is not in the cache, Guzzle will throw an exception. These will only occur locally and be supressed automatically when used in a production environment. However you can disable them permanently, by setting `force_exceptions` to `false` in the config.php



