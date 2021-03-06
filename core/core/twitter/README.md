codebird-php
============
*A Twitter library in PHP.*

Copyright (C) 2010-2015 Jublo Solutions <support@jublo.net>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

### Requirements

- PHP 5.4.0 or higher
- OpenSSL extension


Summary
-------

Use Codebird to connect to the Twitter REST **and Streaming API :sparkles:** from your PHP code.  
Codebird supports full 3-way OAuth as well as application-only auth.


Authentication
--------------

To authenticate your API requests on behalf of a certain Twitter user
(following OAuth 1.0a), take a look at these steps:

```php
require_once ('codebird.php');
\Codebird\Codebird::setConsumerKey('YOURKEY', 'YOURSECRET'); // static, see 'Using multiple Codebird instances'

$cb = \Codebird\Codebird::getInstance();
```

You may either set the OAuth token and secret, if you already have them:
```php
$cb->setToken('YOURTOKEN', 'YOURTOKENSECRET');
```

Or you authenticate, like this:

```php
session_start();

if (! isset($_SESSION['oauth_token'])) {
    // get the request token
    $reply = $cb->oauth_requestToken([
        'oauth_callback' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
    ]);

    // store the token
    $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
    $_SESSION['oauth_token'] = $reply->oauth_token;
    $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;
    $_SESSION['oauth_verify'] = true;

    // redirect to auth website
    $auth_url = $cb->oauth_authorize();
    header('Location: ' . $auth_url);
    die();

} elseif (isset($_GET['oauth_verifier']) && isset($_SESSION['oauth_verify'])) {
    // verify the token
    $cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    unset($_SESSION['oauth_verify']);

    // get the access token
    $reply = $cb->oauth_accessToken([
        'oauth_verifier' => $_GET['oauth_verifier']
    ]);

    // store the token (which is different from the request token!)
    $_SESSION['oauth_token'] = $reply->oauth_token;
    $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;

    // send to same URL, without oauth GET parameters
    header('Location: ' . basename(__FILE__));
    die();
}

// assign access token on each page load
$cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
```

### Logging out

In case you want to log out the current user (to log in a different user without
creating a new Codebird object), just call the `logout()` method.

```
$cb->logout();
```

### Application-only auth

Some API methods also support authenticating on a per-application level.
This is useful for getting data that are not directly related to a specific
Twitter user, but generic to the Twitter ecosystem (such as ```search/tweets```).

To obtain an app-only bearer token, call the appropriate API:

```php
$reply = $cb->oauth2_token();
$bearer_token = $reply->access_token;
```

I strongly recommend that you store the obtained bearer token in your database.
There is no need to re-obtain the token with each page load, as it becomes invalid
only when you call the ```oauth2/invalidate_token``` method.

If you already have your token, tell Codebird to use it:
```php
\Codebird\Codebird::setBearerToken('YOURBEARERTOKEN');
```
In this case, you don't need to set the consumer key and secret.
For sending an API request with app-only auth, see the ???Usage examples??? section.


Usage examples
--------------

When you have an access token, calling the API is simple:

```php
$cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']); // see above

$reply = (array) $cb->statuses_homeTimeline();
print_r($reply);
```

Tweeting is as easy as this:

```php
$reply = $cb->statuses_update('status=Whohoo, I just tweeted!');
```

:warning: *Make sure to urlencode any parameter values that contain
query-reserved characters, like tweeting the `&` sign:*

```php
$reply = $cb->statuses_update('status=' . urlencode('Fish & chips'));
// will result in this:
$reply = $cb->statuses_update('status=Fish+%26+chips');
```

In most cases, giving all parameters in an array is easier,
because no encoding is needed:

```php
$params = [
    'status' => 'Fish & chips'
];
$reply = $cb->statuses_update($params);
```

```php
$params = [
    'status' => 'I love London',
    'lat'    => 51.5033,
    'long'   => 0.1197
];
$reply = $cb->statuses_update($params);
```

```php
$params = [
    'screen_name' => 'jublonet'
];
$reply = $cb->users_show($params);
```
This is the [resulting tweet](https://twitter.com/LarryMcTweet/status/482239971399835648)
sent with the code above.

### Uploading media to Twitter

Tweet media can be uploaded in a 2-step process:

**First** you send each media to Twitter. For **images**, it works like this:

```php
// these files to upload. You can also just upload 1 image!
$media_files = [
    'bird1.jpg', 'bird2.jpg', 'bird3.jpg'
];
// will hold the uploaded IDs
$media_ids = [];

foreach ($media_files as $file) {
    // upload all media files
    $reply = $cb->media_upload([
        'media' => $file
    ]);
    // and collect their IDs
    $media_ids[] = $reply->media_id_string;
}
```

Uploading **videos** requires you to send the data in chunks. See the next section on this.

**Second,** you attach the collected media ids for all images to your call
to ```statuses/update```, like this:

```php
// convert media ids to string list
$media_ids = implode(',', $media_ids);

// send tweet with these medias
$reply = $cb->statuses_update([
    'status' => 'These are some of my relatives.',
    'media_ids' => $media_ids
]);
print_r($reply);
);
```

Here is a [sample tweet](https://twitter.com/LarryMcTweet/status/475276535386365952)
sent with the code above.

More [documentation for uploading media](https://dev.twitter.com/rest/public/uploading-media) is available on the Twitter Developer site.

#### Remote files

Remote files received from `http` and `https` servers are supported, too:
```php
$reply = $cb->media_upload(array(
    'media' => 'http://www.bing.com/az/hprichbg/rb/BilbaoGuggenheim_EN-US11232447099_1366x768.jpg'
));
```

:warning: *URLs containing Unicode characters should be normalised. A sample normalisation function can be found at http://stackoverflow.com/a/6059053/1816603*

#### Video files

Uploading videos to Twitter (??? 15MB, MP4) requires you to send them in chunks.
You need to perform at least 3 calls to obtain your `media_id` for the video:

1. Send an `INIT` event to get a `media_id` draft.
2. Upload your chunks with `APPEND` events, each one up to 5MB in size.
3. Send a `FINALIZE` event to convert the draft to a ready-to-tweet `media_id`.
4. Post your tweet with video attached.

Here???s a sample for video uploads:

```php
$file       = 'demo-video.mp4';
$size_bytes = filesize($file);
$fp         = fopen($file, 'r');

// INIT the upload

$reply = $cb->media_upload([
    'command'     => 'INIT',
    'media_type'  => 'video/mp4',
    'total_bytes' => $size_bytes
]);

$media_id = $reply->media_id_string;

// APPEND data to the upload

$segment_id = 0;

while (! feof($fp)) {
    $chunk = fread($fp, 1048576); // 1MB per chunk for this sample

    $reply = $cb->media_upload([
        'command'       => 'APPEND',
        'media_id'      => $media_id,
        'segment_index' => $segment_id,
        'media'         => $chunk
    ]);

    $segment_id++;
}

fclose($fp);

// FINALIZE the upload

$reply = $cb->media_upload([
    'command'       => 'FINALIZE',
    'media_id'      => $media_id
]);

var_dump($reply);

if ($reply->httpstatus < 200 || $reply->httpstatus > 299) {
    die();
}

// Now use the media_id in a tweet
$reply = $cb->statuses_update([
    'status'    => 'Twitter now accepts video uploads.',
    'media_ids' => $media_id
]);

```

:warning: The Twitter API reproducibly rejected some MP4 videos even though they are valid. It???s currently undocumented which video codecs are supported and which are not.

:warning: When uploading a video in multiple chunks, you may run into an error `The validation of media ids failed.` even though the `media_id` is correct. This is known. Please check back with this [Twitter community forums thread](https://twittercommunity.com/t/video-uploads-via-rest-api/38177/5).


### Requests with app-only auth

To send API requests without an access token for a user (app-only auth),
add a second parameter to your method call, like this:

```php
$reply = $cb->search_tweets('q=Twitter', true);
```

Bear in mind that not all API methods support application-only auth.

Mapping API methods to Codebird function calls
----------------------------------------------

As you can see from the last example, there is a general way how Twitter???s API methods
map to Codebird function calls. The general rules are:

1. For each slash in a Twitter API method, use an underscore in the Codebird function.

    Example: ```statuses/update``` maps to ```Codebird::statuses_update()```.

2. For each underscore in a Twitter API method, use camelCase in the Codebird function.

    Example: ```statuses/home_timeline``` maps to ```Codebird::statuses_homeTimeline()```.

3. For each parameter template in method, use UPPERCASE in the Codebird function.
    Also don???t forget to include the parameter in your parameter list.

    Examples:
    - ```statuses/show/:id``` maps to ```Codebird::statuses_show_ID('id=12345')```.
    - ```users/profile_image/:screen_name``` maps to
      `Codebird::users_profileImage_SCREEN_NAME('screen_name=jublonet')`.

HTTP methods (GET, POST, DELETE etc.)
-------------------------------------

Never care about which HTTP method (verb) to use when calling a Twitter API.
Codebird is intelligent enough to find out on its own.

Response codes
--------------

The HTTP response code that the API gave is included in any return values.
You can find it within the return object???s ```httpstatus``` property.

### Dealing with rate-limits

Basically, Codebird leaves it up to you to handle Twitter???s rate limit.
The library returns the response HTTP status code, so you can detect rate limits.

I suggest you to check if the ```$reply->httpstatus``` property is ```400```
and check with the Twitter API to find out if you are currently being
rate-limited.
See the [Rate Limiting FAQ](https://dev.twitter.com/rest/public/rate-limiting)
for more information.

Unless your return format is JSON, you will receive rate-limiting details
in the returned data???s ```$reply->rate``` property,
if the Twitter API responds with rate-limiting HTTP headers.

Return formats
--------------
The default return format for API calls is a PHP object.
For API methods returning multiple data (like ```statuses/home_timeline```),
you should cast the reply to array, like this:

```php
$reply = $cb->statuses_homeTimeline();
$data = (array) $reply;
```

Upon your choice, you may also get PHP arrays directly:

```php
$cb->setReturnFormat(CODEBIRD_RETURNFORMAT_ARRAY);
```

The Twitter API natively responds to API calls in JSON (JS Object Notation).
To get a JSON string, set the corresponding return format:

```php
$cb->setReturnFormat(CODEBIRD_RETURNFORMAT_JSON);
```

Using multiple Codebird instances
---------------------------------

By default, Codebird works with just one instance. This programming paradigma is
called a *singleton*.

Getting the main Codebird object is done like this:

```php
$cb = \Codebird\Codebird::getInstance();
```

If you need to run requests to the Twitter API for multiple users at once,
Codebird supports this as well. Instead of getting the instance like shown above,
create a new object:

```php
$cb1 = new \Codebird\Codebird;
$cb2 = new \Codebird\Codebird;
```

Please note that your OAuth consumer key and secret is shared within
multiple Codebird instances, while the OAuth request and access tokens with their
secrets are *not* shared.


Consuming the Twitter Streaming API
-----------------------------------

The Streaming APIs give developers low latency access to Twitter???s global stream of
Tweet data. A proper implementation of a streaming client will be pushed messages
indicating Tweets and other events have occurred, without any of the overhead
associated with polling a REST endpoint.

To consume one of the available Twitter streams, follow these **two steps:**

1. Set up a callback function that gets called for every new streaming message that arrives.

   Codebird also calls this function once per second, to allow you to work on any due tasks, and to give you the chance to cancel the stream even if no new messages appear.

2. After creating the callback, tell Codebird about it using a [callable](http://php.net/manual/en/language.types.callable.php). Then start consuming the stream.

```php
// First, create a callback function:

function some_callback($message)
{
    // gets called for every new streamed message
    // gets called with $message = NULL once per second

    if ($message !== null) {
        print_r($message);
        flush();
    }

    // return false to continue streaming
    // return true to close the stream

    // close streaming after 1 minute for this simple sample
    // don't rely on globals in your code!
    if (time() - $GLOBALS['time_start'] >= 60) {
        return true;
    }

    return false;
}

// set the streaming callback in Codebird
$cb->setStreamingCallback('some_callback');

// any callable is accepted:
// $cb->setStreamingCallback(['MyClass', 'some_callback']);

// for canceling, see callback function body
// not considered good practice in real world!
$GLOBALS['time_start'] = time();

// Second, start consuming the stream:
$reply = $cb->user();

// See the *Mapping API methods to Codebird function calls* section for method names.
// $reply = $cb->statuses_filter('track=Windows');
```

Find more information on the [Streaming API](https://dev.twitter.com/streaming/overview)
in the developer documentation website.


How Do I????
----------

### ???access a user???s profile image?

First retrieve the user object using

```$reply = $cb->users_show("screen_name=$username");```


with ```$username``` being the username of the account you wish to retrieve the profile image from.

Then get the value from the index ```profile_image_url``` or ```profile_image_url_https``` of the user object previously retrieved.


For example:

```$reply['profile_image_url']``` will then return the profile image url without https.

### ???get user ID, screen name and more details about the current user?

When the user returns from the authentication screen, you need to trade
the obtained request token for an access token, using the OAuth verifier.
As discussed in the section ???Usage example,??? you use a call to
```oauth/access_token``` to do that.

The API reply to this method call tells you details about the user that just logged in.
These details contain the **user ID** and the **screen name.**

Take a look at the returned data as follows:

```
stdClass Object
(
    [oauth_token] => 14648265-rPn8EJwfB**********************
    [oauth_token_secret] => agvf3L3**************************
    [user_id] => 14648265
    [screen_name] => jublonet
    [httpstatus] => 200
)
```

If you need to get more details, such as the user???s latest tweet,
you should fetch the complete User Entity.  The simplest way to get the
user entity of the currently authenticated user is to use the
```account/verify_credentials``` API method.  In Codebird, it works like this:

```php
$reply = $cb->account_verifyCredentials();
print_r($reply);
```

I suggest to cache the User Entity after obtaining it, as the
```account/verify_credentials``` method is rate-limited by 15 calls per 15 minutes.

### ???walk through cursored results?

The Twitter REST API utilizes a technique called ???cursoring??? to paginate
large result sets. Cursoring separates results into pages of no more than
5000 results at a time, and provides a means to move backwards and
forwards through these pages.

Here is how you can walk through cursored results with Codebird.

1. Get the first result set of a cursored method:
```php
$result1 = $cb->followers_list();
```

2. To navigate forth, take the ```next_cursor_str```:
```php
$nextCursor = $result1->next_cursor_str;
```

3. If ```$nextCursor``` is not 0, use this cursor to request the next result page:
```php
    if ($nextCursor > 0) {
        $result2 = $cb->followers_list('cursor=' . $nextCursor);
    }
```

To navigate back instead of forth, use the field ```$resultX->previous_cursor_str```
instead of ```next_cursor_str```.

It might make sense to use the cursors in a loop.  Watch out, though,
not to send more than the allowed number of requests to ```followers/list```
per rate-limit timeframe, or else you will hit your rate-limit.

### ???use xAuth with Codebird?

Codebird supports xAuth just like every other authentication used at Twitter.
Remember that your application needs to be whitelisted to be able to use xAuth.

Here???s an example:
```php
$reply = $cb->oauth_accessToken([
    'x_auth_username' => 'username',
    'x_auth_password' => '4h3_p4$$w0rd',
    'x_auth_mode' => 'client_auth'
]);
```

Are you getting a strange error message?  If the user is enrolled in
login verification, the server will return a HTTP 401 error with a custom body.
If you are using the ```send_error_codes``` parameter, you will receive the
following error message in the response body:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<errors>
<error code="231">User must verify login</error>
</errors>
```

Otherwise, the response body will contain a plaintext response:
```
User must verify login
```

When this error occurs, advise the user to
[generate a temporary password](https://twitter.com/settings/applications)
on twitter.com and use that to complete signing in to the application.

### ???know what cacert.pem is for?

Connections to the Twitter API are done over a secured SSL connection.
Since 2.4.0, codebird-php checks if the Twitter API server has a valid
SSL certificate. Valid certificates have a correct signature-chain.
The cacert.pem file contains a list of all public certificates for root
certificate authorities. You can find more information about this file
at http://curl.haxx.se/docs/caextract.html.

### ???set the timeout for requests to the Twitter API?

For connecting to Twitter, Codebird uses the cURL library, if available.
You can specify both the connection timeout and the request timeout,
in milliseconds:

```php
$cb->setConnectionTimeout(2000);
$cb->setTimeout(5000);
```

If you don't specify the timeout, codebird uses these values:

- connection time = 3000 ms = 3 s
- timeout = 10000 ms = 10 s

### ???disable cURL?

Codebird automatically detects whether you have the PHP cURL extension enabled.
If not, the library will try to connect to Twitter via socket.
For this to work, the PHP setting `allow_url_fopen` must be enabled.

You may also manually disable cURL.  Use the following call:

```php
$cb->setUseCurl(false);
```

### ???use a proxy?

Codebird allows proxy support for both cURL handles and sockets.

To activate proxy mode, use the following call:

```php
$cb->setProxy('<host>', '<port>');
```

You may also use an authenticated proxy. Use the following call:

```php
$cb->setProxy('<host>', '<port>');
$cb->setProxyAuthentication('<username>:<password>');
```
