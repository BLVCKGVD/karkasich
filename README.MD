## Требования для запуска

1. Необходим PHP >= 8.1
2. Нужен Composer
3. Необходим npm для билда фронта
4. СУБД MySQL
5. Symfony CLI

## Как правильно запустить проект

1. composer install
2. npm install
3. npm run build
4. php bin/console doctrine:database:create (не забудьте указать в файле .env вашу БД)
5. php bin/console doctrine:migrations:migrate
5. symfony server:start
