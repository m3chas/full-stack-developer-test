# Parking App!

This app is based on a Micro-service Architecture which use an API gateway to perform operations again registered services.

I've used subdomain micro-services pattern.

# Requirements

In order to get the system running, you should have installed:

 - MongoDB
 - PHP 7.x
 - Laravel
 - NodeJS
 - Composer
 - NPM

## Run locally

There are 3 main services based on PHP (Vechiles & Tools services based on Laravel and parkinglogs on Lumen) and one API gateway based on NodeJS.

 - Run composer install on each vehicles, tools, parkinglogs folders in order to install Laravel framework and dependencies and npm install on api-gateway folder.
 - Copy .env.example to .env and update if necessary to match your MongoDB credentials.
 - Run php artisan serve on vehicles folder
 - Run php artisan serve --port=9000 on tools folder
 - Run php -S localhost:7000 -t ./public on parkinglogs folder.
 - Run npm start server on api-gateway folder

## Security
Since this is an example, because of time and lack of user auth model, I've not included any auth method for the API gateway as well the services machine to machine communication.

On production, this system can setup an auth service to allow end-users to login and get an auth token in order to authenticate on API gateway, the micro-services communication should be based on machine-to-machine client-secret auth.
