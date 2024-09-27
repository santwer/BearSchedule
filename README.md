<p>
<img src="https://img.shields.io/badge/php-%5E8.2-blue?logo=php" >
<a href="https://bulma.io">
<img src="https://img.shields.io/badge/Laravel-%5E11.0-blue?logo=laravel" >
<img src="https://img.shields.io/badge/Vue.js-%5E3.4.30-blue?logo=vue.js" >
<img src="https://img.shields.io/badge/bootstrap-%5E5.3.3-blue?logo=buefy" >
</a>


</p>

<img src="https://raw.githubusercontent.com/santwer/BearSchedule/f579987d69dcafbb353bc38495c1f3054ff4efd8/.src/logo.svg" height="64">


# BearSchedule
BearSchedule is a PHP/Laravel Project for easy use to show and share Project schedules.

<img src="https://raw.githubusercontent.com/santwer/BearSchedule/refs/heads/main/.src/web_2024.jpg"/>


## Requierements
 - Composer
 - npm via Nodejs
 - php ^8.2

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

##### Migrate Database
```sh
$ php artisan migrate
```

##### config/users.php

It it possible to set up the product, that all Users can find each other or certain do. Base by Mail-Address.
The description is in the heading file.

## License
[MIT](https://choosealicense.com/licenses/mit/)
