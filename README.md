Subscription
========================

This app manages the subscription process of mobile apps.
This project used to Symfony Framework(5.2 version)

# Getting Started

## Prerequisites

You are going to need:

- `Git` [learn more information Git][1]
- `Composer` [learn more information Composer][2]
- `Symfony Framework` [learn more information Symfony][3]

Requirements
------------

* PHP 7.3 or higher;
* ctype, iconv extensions installed;

Installation
------------

Download the repository and run this command for php packages install:

```bash
$ cd project_directory/
$ composer install
```

Run Project
-----

There's need to symfony-cli to run the application. If you don't have symfony-cli please follow [Symfony Download][4]

Then you need to run these commands:

```bash
$ cd project_directory/
$ symfony server:start
```
You will probably see output like: [OK] Web server listening http://127.0.0.1:8000 Project Run!

Usage
-----

#### Rover Create Method

```bash
POST http://127.0.0.1:8000/api/rover/create.json
```
Request Body:
```bash
{
  "name": "Rover 1",
  "target_plateau_id": 1,	
  "latitude": 100.145,
  "longitude": 101.456,
  "heading_direction": "N"
}
```

You will probably see results like:
```bash
{
  "status": true,
  "message": "Rover create successful."
}
```

#### Rover Get Method

```bash
POST http://127.0.0.1:8000/api/rover/get.json
```
Request Body:
```bash
{
  "id": 1
}
```

You will probably see results like:
```bash
{
  "status": true,
  "data": {
    "id": 1,
    "name": "Rover r60d65fea59592",
    "slug": "rover-r60d65fea59592",
    "created_at": "2021-06-25 22:59:54",
    "updated_at": null,
    "is_active": true
  }
}
```

#### Rover Get State Method

```bash
POST http://127.0.0.1:8000/api/rover/get-state.json
```
Request Body:
```bash
{
  "id": 1
}
```

You will probably see results like:
```bash
{
  "status": true,
  "data": {
    "plateau": {
      "id": 1,
      "name": "Rover 1"
    },
    "latitude": 100.145,
    "longitude": 101.456,
    "last_activity_time": "2021-06-25 20:11:36",
    "compass_direction": "N"
  }
}
```

#### Rover Send Command Method

```bash
POST http://127.0.0.1:8000/api/rover/send-command.json
```
Request Body:
```bash
{
  "rover_id": 1,
  "command": "LMMM"
}
```

You will probably see results like:
```bash
{
  "status": true,
  "data": {
    "plateau": {
      "id": 1,
      "name": "Test plateau r60d65fe9b5e15"
    },
    "latitude": 315.987,
    "longitude": 321.987,
    "last_activity_time": "2021-06-25 23:11:37",
    "compass_direction": "W"
  }
}
```

#### Plateau Create Method

```bash
POST http://127.0.0.1:8000/api/plateau/create.json
```
Request Body:
```bash
{
  "name": "Plateau 2",
  "latitude": 100.145,
  "longitude": 101.456
}
```

You will probably see results like:
```bash
{
  "status": true,
  "message": "Plateau create successful."
}
```

#### Plateau Get Method

```bash
POST http://127.0.0.1:8000/api/plateau/get.json
```
Request Body:
```bash
{
  "id": 1
}
```

You will probably see results like:
```bash
{
  "status": true,
  "data": {
    "id": 1,
    "name": "Test plateau r60d65fe9b5e15",
    "slug": "test-plateau-r60d65fe9b5e15",
    "latitude": 104.55,
    "longitude": 104.55,
    "created_at": "2021-06-25 22:59:53",
    "updated_at": null,
    "is_active": true
  }
}
```

#### Plateau List Method

```bash
GET http://127.0.0.1:8000/api/plateau/list.json
```
Request Body:
```bash
{}
```

You will probably see results like:
```bash
{
  "status": true,
  "data": [
    {
      "id": 1,
      "name": "Test plateau r60d65fe9b5e15",
      "slug": "test-plateau-r60d65fe9b5e15",
      "latitude": 104.55,
      "longitude": 104.55,
      "created_at": "2021-06-25 22:59:53",
      "updated_at": null,
      "is_active": true
    },
    {
      "id": 2,
      "name": "Test plateau r60d65fea2e5e1",
      "slug": "test-plateau-r60d65fea2e5e1",
      "latitude": 104.55,
      "longitude": 104.55,
      "created_at": "2021-06-25 22:59:54",
      "updated_at": null,
      "is_active": true
    }
  ]
}
```

Insomnia Collection
-----
You can download the Insomnia collection [here](Insomnia_collection.json)

If you want to test the Heroku application, you can choose the "Heroku (prod)" environment in insomnia.

For more information about Insomnia [click][5]

[1]: https://git-scm.com/
[2]: https://getcomposer.org/
[3]: https://symfony.com/
[4]: https://symfony.com/download
[5]: https://insomnia.rest/