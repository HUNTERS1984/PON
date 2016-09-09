# PON

In progress..

## Project setup

### Prerequisites:

* Virtualbox (latest)
* Vagrant >=1.4

### Start

To start a vm simply run:

```
vagrant up
```
```
vagrant provision
```

Install data

When box is prepared to install data login to the VM.

```
vagrant ssh
```

By default you should be redirected directly to you project space in VM,
 run the commands below to insert data:

```
composer install

```

```
console doctrine:schema:update --force

```

Create Dummy Data

```
console dummy:data
```

```
npm install

```

```
bower install

```

```
gulp

```

```

When we reboot machine, we have to remember that :

 vagrant ssh
 restart

```

```

NOTE: user/password connect to database from client: docker/docker

```
mysql connect
```
mysql -u docker -p'docker' -h 127.0.0.1

```

mysql dump

```
mysqldump -u docker -p'docker' -h 127.0.0.1 pon > ./pon.sql

```

mysql import

```
mysql -u docker -p'docker' -h 127.0.0.1 pon < ./pon.sql
```

Open http://pon.dev/api/doc in browser

Get access token:

```
http POST http://pon.dev/oauth/v2/token \
    grant_type=password \
    client_id=1_3bcbxd9e24g0gk4swg0kwgcwg4o8k8g4g888kwc44gcc0gwwk4 \
    client_secret=4ok2x70rlfokc8g0wws8c8kwcokw80k44sg48goc0ok4w0so0k \
    username=admin@pon.dev \
    password=admin
````

Use access token which just got:

```
http GET http://pon.dev/api/v1/home \
    "Authorization:Bearer MDFjZGI1MTg4MTk3YmEwOWJmMzA4NmRiMTgxNTM0ZDc1MGI3NDgzYjIwNmI3NGQ0NGE0YTQ5YTVhNmNlNDZhZQ"
```
