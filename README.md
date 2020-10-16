<p>
<img src="https://img.shields.io/badge/php-%5E7.4-blue?logo=php" >
<a href="https://bulma.io">
<img src="https://img.shields.io/badge/Laravel-%5E8.1.0-blue?logo=laravel" >
<img src="https://img.shields.io/badge/Vue.js-%5E2.6.11-blue?logo=vue.js" >
<img src="https://img.shields.io/badge/buefy-%5E0.8.19-blue?logo=buefy" >
<img src="https://img.shields.io/badge/bulma-%5E0.8.2-blue?logo=bulma" >
</a>


</p>

#BearSchedule
BearSchedule is a PHP/Laravel Project for easy use to show and share Project schedules.

## Requierements
 - Composer
 - npm via Nodejs
 - php ^7.4

## Installation

```sh
$ git clone https://github.com/santwer/BearSchedule.git
$ cd BearSchedule
$ composer install
$ cp .env.example .env
$ php artisan key:generate
```

Add all add all database connection data to env. Also its possible to add Microsoft Graph API Key.
So you are able to login with Mircosoft.

#####Migrate Database
```sh
$ php artisan migrate
```

#####config/users.php

It it possible to set up the product, that all Users can find each other or certain do. Base by Mail-Address.
The description is in the heading file.

## License
[MIT](https://choosealicense.com/licenses/mit/)
