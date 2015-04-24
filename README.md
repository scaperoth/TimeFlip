# TimeFlip

I didn't want to use an application to look at old facebook posts because I don't trust them with my information, so I made my own.

### Usage

All you have to do to use this application (besides having a php server running) is to go into the application/config/ folder and create a file named facebook.php. In this file place the following lines

```

<?php

$config['appId'] = {app ID};
$config['secret'] = {secret app key};
$config['facebook']['redirect_url'] = 'http://localhost/TimeFlip/flip';
$config['facebook']['permissions'] = array(
    'public_profile ',
    'user_photos',
    'user_posts',
    'user_status',
);

```

the app id and secret come from the [Facebook Developer pages](https://developers.facebook.com/)