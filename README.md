## Getting started

### Pre-requisites
- docker
- docker-compose

### Check out this repository

### Run composer to kickstart laravel sail

```bash
docker run --rm \
    --pull=always \
    -v "$(pwd)":/opt \
    -w /opt \
    laravelsail/php82-composer:latest \
    bash -c "composer install"
```

### Run the application
`cp .env.example .env`

`./vendor/bin/sail up -d`

`./vendor/bin/sail artisan key:generate`

`./vendor/bin/sail artisan migrate`

### Generate some test data
`./vendor/bin/sail artisan db:seed --class=RecipesSeeder`

### Kickstart the nuxt frontend
`./vendor/bin/sail npm install --prefix frontend`

### Run the frontend
`./vendor/bin/sail npm run dev --prefix frontend`

### Confirm your application
visit the frontend http://localhost:3000

visit the backend http://localhost:8888


## Testing the backend
Listing and search

```shell

// list all recipes
http://localhost:8888/api/recipes

// search by ingredients
http://localhost:8888/api/recipes/?search[ingredients][0]=Cod

// serach by email
http://localhost:8888/api/recipes?search[author_email]=raul.von@example.org

// combined search
http://localhost:8888/api/recipes?search[author_email]=raul.von@example.org&search[ingredients][0]=Sockeye

// fetching a specific recipe by slug
http://localhost:8888/api/recipes/qui-consequatur-itaque-hb2tu
```

### Connecting to your database from localhost
`docker exec -it laravel-mysql-1 bash -c "mysql -uroot -ppassword"`

Or use any database GUI and connect to 127.0.0.1 port 3333


### Other tips
`./vendor/bin/sail down` to bring down the stack

Sometimes it's necessary to restart the nuxt app when adding new routes. Simply `ctrl+c` on the npm command execute
`./vendor/bin/sail npm run dev --prefix frontend` again
