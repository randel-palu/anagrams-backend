## Installation instrucitons

- git clone git@github.com:randel-palu/anagrams-backend.git anagrams-backend
- cd anagrams-backend
- cp .env.example .env
- composer install
- sail up
- php artisan migrate
- php artisan db:seed

## Configuration

- If necessary, change ports 80 and 3306 in docker-compose.yml
- All the other configurations can be changed in .env file
