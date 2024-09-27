
# Blogs-app Backend

A Blogs backend api used to authenticate user (login and register), show user profile. User can create his blogs, see the ratings, and also able to create the category, and sub_category inside the blog, here there are two types of user as Admin and User where admin is able to delete and view all the blogs, while the user can perform CRUD on his own blog. Everyone is also able to see the ratings of the blog, and they are also able to do nested comments on the blog. User is also able to upload the profile image. 
## API Reference

#### Get user register

```http
  POST /api/auth/register
```

| Parameter               | Type     | Description   |
| :--------               | :------- | :-------------|
| `name`                  | `string` | **Required**. |
| `email`                 | `string` | **Required**. |
| `password`              | `string` | **Required**. |
| `password_confirmation` | `string` | **Required**. |
| `type`                  | `string` | **Required**. |

#### Get user login

```http
  GET /api/auth/login
```

| Parameter               | Type     | Description   |
| :--------               | :------- | :-------------|
| `email`                 | `string` | **Required**. |
| `password`              | `string` | **Required**. |

#### Get user profile
```http
    GET  /api/user/profile
```

Headers
| Parameter       | Type               | Description   |
| :--------       | :-------           | :-------------|
| `accept`        | `application/json` | **Required**. |
| `authorization` | `Bearer` Token     | **Required**. |


#### Get user logout
```http
    GET  /api/user/logout
```

Headers
| Parameter       | Type               | Description   |
| :--------       | :-------           | :-------------|
| `accept`        | `application/json` | **Required**. |
| `authorization` | `Bearer` Token     | **Required**. |

#### Get blog 
```http
    GET  /api/blog/create
```

Headers
| Parameter       | Type               | Description   |
| :--------       | :-------           | :-------------|
| `accept`        | `application/json` | **Required**. |
| `authorization` | `Bearer` Token     | **Required**. |

Form Data

#### Blog create
```http
    POST  /api/blog/create
```
Headers
| Parameter       | Type               | Description   |
| :--------       | :-------           | :-------------|
| `accept`        | `application/json` | **Required**. |
| `authorization` | `Bearer` Token     | **Required**. |


Body
| Parameter       | Type               | Description   |
| :--------       | :-------           | :-------------|
| `title`         | `string`           | **Required**. |
| `description`   | `string`           | **Required**. |
| `image`         | `image`            | **Nullable**. |
| `category`      | `string`           | **Required**. |
| `tag`           | `string`           | **Required**. |
| `sub_category`  | `string`           | **Required**. |
| `slug`          | `string`           | **Nullable**. |
| `draft`         | `Boolean`          | **Sometimes**.|
| `publish`       | `Boolean`          | **Sometimes**.|


#### Blog update
```http
    PUT  /api/blog/update
```
Headers
| Parameter       | Type               | Description   |
| :--------       | :-------           | :-------------|
| `accept`        | `application/json` | **Required**. |
| `authorization` | `Bearer` Token     | **Required**. |

Body
| Parameter       | Type               | Description   |
| :--------       | :-------           | :-------------|
| `title`         | `string`           | **Nullable**. |
| `description`   | `string`           | **Nullable**. |
| `blog_id`       | `image`            | **Required**. |

#### Blog Get
```http
    GET  /api/blog/user
```
Headers
| Parameter       | Type               | Description   |
| :--------       | :-------           | :-------------|
| `accept`        | `application/json` | **Required**. |
| `authorization` | `Bearer` Token     | **Required**. |

#### Blog update
```http
    DELETE  /api/blog/delete/{id}
```
Headers
| Parameter       | Type               | Description   |
| :--------       | :-------           | :-------------|
| `accept`        | `application/json` | **Required**. |
| `authorization` | `Bearer` Token     | **Required**. |

#### Blog Dislay
```http
    GET  /api/blog
```
Headers
| Parameter       | Type               | Description   |
| :--------       | :-------           | :-------------|
| `accept`        | `application/json` | **Required**. |
| `authorization` | `Bearer` Token     | **Required**. |

#### Display Speciifc Blog
```http
    GET  /api/blog/{slug}
```
Headers
| Parameter       | Type               | Description   |
| :--------       | :-------           | :-------------|
| `accept`        | `application/json` | **Required**. |
| `authorization` | `Bearer` Token     | **Required**. |



## Run Locally

Clone the project

```bash
  git clone https://github.com/arihant-getgrahak/laravel-blog-backend
```

Go to the project directory

```bash
  cd laravel-blog-backend
```

Install dependencies

```bash
  composer install
```

Migrate Data to Database

```bash
    php artisan migrate
```
Start the server

```bash
    php artisan migrate
```


## Tech Stack

**Client:** PHP, Laravel, Reverb

**Server:** Herd


## Documentation

[Herd](https://herd.laravel.com/docs/windows/1/getting-started/about-herd)

[Laravel](https://laravel.com/docs/11.x/installation)

[Localization](https://laravel.com/docs/11.x/localization)


## Contributing

1. [@arihant-getgrahak](https://github.com/arihant-getgrahak/laravel-blog-backend)

2. [@sonaljain01](https://github.com/arihant-getgrahak/laravel-blog-backend)
