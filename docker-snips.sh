docker run --name memcached -d memcached
docker run --name gcpug-mysql -p 3306:3306 -e MYSQL_ROOT_PASSWORD=sausage -d mysql
docker build -t php-guestbook  .
docker run --name php-guestbook -p 80:80 -d php-guestbook
docker run --name php-guestbook -p 80:80 -d --link gcpug-mysql:mysql --link memcached:memcached php-guestbook
