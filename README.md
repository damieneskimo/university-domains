## Brief
This project uses Laravel(8.12) to build API to retrieve a list of universities with their domains for a particular country and a single university record. The data is from Hipo University Domains and Names API. You can take a closer look at its github repo: https://github.com/Hipo/university-domains-list. <br /><br />
The results from Hipo API are cached in the local database with each record set to random TTL(Time To Live) from 5 to 15 minutes. After the TTL expires, the UpdateUniversityCache job will be dispatched and if the data record has been updated or deleted in the Hipo API, then the record will be updated or deleted from local database as well. UniversityCacheDeleted or UniversityCacheUpdated event will also be broadcast to the frontend accordingly. <br /><br />
For the Broadcasting, this project uses Laravel WebSockets (https://github.com/beyondcode/laravel-websockets). To receive the events and update UI in real time, please use its sibling frontend Vue SPA project (https://github.com/damieneskimo/university-domains-vue-spa).

## Project Setup
1. clone the project and run it
```
composer install
php artisan migrate
```
> Note: Since this project uses model event created/updated to dispatch delayed UpdateUniversityCache job to update a single record when TTL expired, please make sure <strong>NOT</strong> to use sync as the queue driver. Otherwise, it will be super slow when getting data

2. Please change you local hosts to uni.test, since the frontend project is using this domain name. Or you can change it as you need.
Example as below:
```
sudo nano /etc/hosts
192.168.10.10 uni.test
```

3. Start Laravel queue worker to process jobs. https://laravel.com/docs/8.x/queues#running-the-queue-worker
```
php artisan queue:work
```

4. Start the WebSocket server. For more details, please see Laravel WebSockets doc: https://beyondco.de/docs/laravel-websockets/getting-started/introduction
```
php artisan websockets:serve
```

> Note:: In your local development, you can achieve running multiple processes by opening multiple termial tabs. In production, you can use a process manager daemon like Supervisor (https://laravel.com/docs/8.x/queues#supervisor-configuration)
