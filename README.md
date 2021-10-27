## About Project

A simple tool for user to grab the feed data from AWS with Laravel Backend and display the stale data on the Frontend by using Vue.js.

## Build

- Install php7.4 or higher on the machine
- Install composer, see the [link](https://getcomposer.org/doc/00-intro.md)
- Install NVM see [here](https://github.com/nvm-sh/nvm)
- Install nodejs and npm by running `nvm install node`
- Clone the project, and start building by running following commands
  - `composer install`
  - `npm install`
  - `npm run prod`
  - `php artisan serve`
- Go to the page here: http://127.0.0.1:8000

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
