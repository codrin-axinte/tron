# Morphling CMS

## Installation

Create an `auth.json` file with NOVA License credentials.

```json
{
    "http-basic": {
        "nova.laravel.com": {
            "username": "{YOUR_EMAIL}",
            "password": "{NOVA_LICENCE_KEY}"
        }
    }
}
```

Install php dependencies

```shell
composer install
```

Link the storage folder

```shell
php artisan storage:link
```

Install node dependencies

```shell
 npm install && npm run prod
```

If you enabled queue using redis you'll have to start the worker or horizon.

```shell
php artisan queue:work
```

or

```shell
php artisan horizon
```

Install application. This will run migrations as well

```shell
php artisan app:install
```

## Versioning the app.

```shell
php artisan app:update
```

### Sail Installation

Run docker

```shell
sail up -d
```

Link storage

```shell
sail artisan storage:link
```

Run a worker

```shell
sail artisan queue:work
```

Run migrations

```shell
sail artisan migrate --seed
```


## References
- [Laravel Modules Docs](https://github.com/nWidart/laravel-modules)
- [Language Publisher Docs](https://publisher.laravel-lang.com/using/)
- [Nova Docs](https://nova.laravel.com/docs/4.0/installation.html)
- [GraphQL Lighthouse Docs](https://lighthouse-php.com/5/getting-started/installation.html)

## Tooling

### Panels

- Access the Nova panel at: [localhost/nova](http://localhost/nova)
- Access the Telescope panel at: [localhost/telescope](http://localhost/telescope)
- Access the Horizon panel at: [localhost/horizon](http://localhost/horizon)
- Access the Swagger UI panel at: [localhost/swagger](http://localhost/swagger)


### Postman
Import the specs file from `storage/app/specs` folder.


Add this the script on the top level of your workspace in order to get the bearer token automatically.

```javascript
const token = pm.environment.get("token");

if(token !== null && token !== undefined && token.trim() !== ''){
    return;
}

const baseUrl = pm.variables.get('baseUrl');
const userId = pm.environment.get('userId') || 1;

const postRequest = {
url: baseUrl + "/api/tokens/create",
method: 'POST',
header: {
    'Content-Type': 'application/json',
    'accept': "application/json",
},
body: {
    mode: 'raw',
    raw: JSON.stringify({ token_name: 'Postman', 'user_id': userId })
}
};

pm.sendRequest(postRequest, function (err, response) {
    const data = response.json();
    pm.environment.set('token', data.token);
    console.log("New token saved!");
});
```
