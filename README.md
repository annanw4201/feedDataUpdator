## About Project

A simple tool for user to grab the feed data from AWS with Laravel Backend and display the stale data on the Frontend by using Vue.js.

## Build & Run

- Install php7.2 or higher on the machine (MacOS has a default php. Install php if needed.)
- Install composer globally with following script, see the [link](https://getcomposer.org/doc/00-intro.md) for details
  - ```shell
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
    ```
  - ```shell
    sudo mv composer.phar /usr/local/bin/composer
    ```
- Install NVM with following script, see [link](https://github.com/nvm-sh/nvm) for details
  - ```shell
    curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
    ```
  - Make sure `.bashrc, .zshrc or .profile` file exist on your machine, and need to restart shell or run `source ~/.bashrc` 
  - Verify NVM works by running `command -v nvm`, you will see `nvm` returned
- Install nodejs and npm by running `nvm install --lts`
- Clone the project, and start building by running following commands
  - `composer install`
  - `npm install`
  - `npm run prod`
  - `cp .env.example .env`
  - `php artisan key:generate`
  - `php artisan serve`
- Go to the page here: http://127.0.0.1:8000
- Generated CSV, JSON files are located at the `./public`, these files are prepended with unix timestamp

## Assumptions

- All timestamp on server is in UTC
- Current date is set to 2020-11-26
- No database storage, all data is in memory

## Improvement

- Implement pagination if data is large on frontend and backend
- Implement partial updates for some out of date data instead of bulk updating all the time
- Implement queue process for large data
- Integrate OAuth on users and apis for better security
- Integrate database/disk for backend storage

## Test

- Under the project at the project root level, ex. /Documents/feedDataUpdator/, run `./vendor/bin/phpunit --filter unit`
