**FOR A CLEAN INSTALL USE THE FOLLOWINF INSTRUCTIONS**

1. Clone the project from [here](https://github.com/Behnamfe76/symfony-blog.git)
2. install the dependencies using the following orders:
   `composer install`
   `npm install`
3. prepare the database configurations in .env file
4. run the migrations:
   `symfony console doctrine:migrations:migrate --no-interaction`
5. run fixtures, in order to have some initial data:
   `symfony console doctrine:fixtures:load --no-interaction`
6. now you can run the symfony's server to work with app:
   `symfony server:start`
   **-- note that symfony CLI must be installed to run the local server.**