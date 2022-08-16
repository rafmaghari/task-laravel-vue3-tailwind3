## Task Management

### Installation Laravel
   - composer install
   - cp .env.example .env
   - php artisan key:generate
   - change db credentials
   - php artisan migrate
   - php artisan db:seed (create a user that has 20 tasks)
   - change the APP_URL to your url in local (to prevent CORS error)

### Installation Vue Js
   - npm install
   - npm run dev

### Testing
   - npm run build (to produce vite manifest)
   - php artisan test
