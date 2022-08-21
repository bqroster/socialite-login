## About Socialite Login

This is a custom extension to transparent handle Laravel Socialite package,


## Install and Configuration 
```bash
composer require bqroster/socialite-login
```

###### Publish config file
```bash
php artisan vendor:publish --tag=socialite-login-config
```

###### Run migrations (review config socialite-login file, before run migrations)
```bash
php artisan migrate
```

###### In your Laravel Project, set some variables on `.env` file
```bash
// this should be the same domain name
// set on the social network callback url
APP_URL=https://yourdomainname.com
```

- Add CLIENT_ID and CLIENT_SECRET for every Social Network you need to __Login / Register__
```bash
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
 
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
 
TWITTER_CLIENT_ID=
TWITTER_CLIENT_SECRET=
```
 - You do not need to set __config.services keys,__ the package will automatically register for you.

##### In your Relationship Model (User Model, normally), include Trait
```php
use Bqroster\SocialiteLogin\Traits\Models\UseSocialite;
```


## Author

Jose Burgos [jose@bqroster.com](mailto:jose@bqroster.com)

## License

The Socialite Login package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
