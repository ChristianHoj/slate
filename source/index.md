---
title: Providi API Reference

language_tabs:
  - php: PHP
  - javascript: Javascript
---

# Introduction
The Providi API consists of the described endpoints/functions that return responses according to the [{json:api} specification](http://jsonapi.org).

Every call to the API must contain an authentication token (`token`) provided by an initial call to the [`authenticate`](#authentication) endpoint (of course this call will not include `token`).

# Authentication

```php
// Example of usage in WordPress
<?php
$username = "chrhoj";
$password = "asd";
$response = wp_safe_remote_post( "api.providi.com/authenticate.php", array( 'body' => array( 'user' => $username, 'pass' => $password ) ) );
$ext_auth = json_decode( $response['body'], true );
if( isset( $ext_auth['data'] ) ) {
  // External user exists, try to load the user info from the WordPress user table
  $userobj = new WP_User();
  $user = $userobj->get_data_by( 'email', $ext_auth['data']['attributes']['email'] );
  $user = new WP_User($user->ID); // Attempt to load up the user with that ID

  // ...
} else if( isset( $ext_auth['error'] ) ) {
  // User does not exist, show an error message
  $user = new WP_Error( 'denied', __("ERROR: User/pass bad") );
}
```

> Response data at successful authentication

```json
{
  "data": {
    "type": "user",
    "id": "123456",
    "attributes": {
      "username": "chrhoj",
      "first_name": "Christian",
      "last_name": "Høj",
      "email": "cbh@nightcirque.com",
      "auth_token": "iuTaoiy78wksjERk9qe"
    }
  }
}
```

> Response data at failed authentication

```json
{
  "errors": [{
    "status": "401 Unauthorized"
  }]
}
```

<aside class="warning">
  <strong>Not implemented</strong>
</aside>

This endpoint is used to authenticate a user against the Providi database. Upon successful authentication the response includes a `auth_token` that must be used in all subsequent calls to the API.

### HTTP Request
`POST https://api.providi.com/authenticate.php`

### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
user | Required | The username to be authenticated
pass | Required | The password to use for authentication


# Leaderboard

```js
jQuery.ajax({
  url: "https://api.providi.com/leaderboard.php",
  data: {
    period: ['this-week', 'previous-week', 'last-30-days'],
    userId: 1,
    token: 'tokenAsdf'
  }
});
```

> Successful response

```json
{
  "data": {
    "id": 0,
    "type": "leaderboards",
    "attributes": {
      "thisWeek": {
        "period": {
          "from": "09/07/2015",
          "until": "09/13/2015"
        },
        "positions": [
          {
            "position": 2,
            "name": "tw Gabriel Muresan 2 222",
            "newMembers": 2,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 1,
            "name": "tw Gabriel Muresan 1",
            "newMembers": 1,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 3,
            "name": "tw Gabriel Muresan 3",
            "newMembers": 3,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 5,
            "name": "tw Gabriel Muresan 5",
            "newMembers": 5,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 6,
            "name": "tw Gabriel Muresan 6",
            "newMembers": 6,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 7,
            "name": "tw Gabriel Muresan 7",
            "newMembers": 7,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 8,
            "name": "tw Gabriel Muresan 8",
            "newMembers": 8,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 9,
            "name": "tw Gabriel Muresan 9",
            "newMembers": 9,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 10,
            "name": "tw Gabriel Muresan 10",
            "newMembers": 10,
            "image": "http://example.com/default-avatar.jpg"
          }
        ]
      },
      "previousWeek": {
        "period": {
          "from": "",
          "until": ""
        },
        "positions": [
          {
            "position": 1,
            "name": "pw Gabriel Muresan 1",
            "newMembers": 1,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 2,
            "name": "pw Gabriel Muresan 2",
            "newMembers": 2,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 3,
            "name": "pw Gabriel Muresan 3",
            "newMembers": 3,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 4,
            "name": "pw Gabriel Muresan 4",
            "newMembers": 4,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 5,
            "name": "pw Gabriel Muresan 5",
            "newMembers": 5,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 6,
            "name": "pw Gabriel Muresan 6",
            "newMembers": 6,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 7,
            "name": "pw Gabriel Muresan 7",
            "newMembers": 7,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 8,
            "name": "pw Gabriel Muresan 8",
            "newMembers": 8,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 9,
            "name": "pw Gabriel Muresan 9",
            "newMembers": 9,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 10,
            "name": "pw Gabriel Muresan 10",
            "newMembers": 10,
            "image": "http://example.com/default-avatar.jpg"
          }
        ]
      },
      "last30Days": {
        "period": {
          "from": "",
          "until": ""
        },
        "positions": [
          {
            "position": 1,
            "name": "l3d Gabriel Muresan 1",
            "newMembers": 1,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 3,
            "name": "l3d Gabriel Muresan 3",
            "newMembers": 3,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 4,
            "name": "l3d Gabriel Muresan 4",
            "newMembers": 4,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 5,
            "name": "l3d Gabriel Muresan 5",
            "newMembers": 5,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 6,
            "name": "l3d Gabriel Muresan 6",
            "newMembers": 6,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 7,
            "name": "l3d Gabriel Muresan 7",
            "newMembers": 7,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 8,
            "name": "l3d Gabriel Muresan 8",
            "newMembers": 8,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 9,
            "name": "l3d Gabriel Muresan 9",
            "newMembers": 9,
            "image": "http://example.com/default-avatar.jpg"
          },
          {
            "position": 10,
            "name": "l3d Gabriel Muresan 10",
            "newMembers": 10,
            "image": "http://example.com/default-avatar.jpg"
          }
        ]
      }
    }
  }
}
```

> Response data for failed request

```json
{
  "errors": [{
    "status": "401 Unauthorized"
  }]
}
```

<aside class="warning">
  <strong>Not implemented</strong>
</aside>

This endpoint is used to get the top ten sellers in Providi for specified periods.

### HTTP Request
`GET https://api.providi.com/leaderboard.php`

### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
period | Required | Array of requested lists. Valid values are `this-week`, `previous-week`, `last-30-days`.
token | Required | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId | Required | The id of the current user. Must be paired with `token`.


# User Profile

```js
jQuery.ajax({
  url: "https://api.providi.com/profile.php",
  data: {
      userId: 1,
      token: 'tokenAsdf'
  }
});
```

> Successful response

```json
{
  "data": {
    "type": "user",
    "id": "SC000XXXXXXX",
    "attributes": {
      "imageUrl": "http://example.com/default-avatar.jpg",
      "accountType": 0,
      "name": "Gabriel Muresan"
    }
  }
}
```

> Response data for failed request

```json
{
  "errors": [{
    "status": "401 Unauthorized"
  }]
}
```

<aside class="warning">
  <strong>Not implemented</strong>
</aside>

This endpoint is used to get all profile information about a single user.

### HTTP Request
`GET https://api.providi.com/profile.php`

### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
token | Required | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId | Required | The id of the current user. Must be paired with `token`.
