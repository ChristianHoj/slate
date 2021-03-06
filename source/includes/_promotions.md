# Promotions

## Active Promotions

```js
jQuery.ajax({
  url: "http://providi.eu/API/promotions.php",
  data: {
    country: 'dk',
    userId: 1,
    token: 'tokenAsdf'
  }
});
```

> Successful response

```json
{
  "data": [
    {
      "id": "0",
      "type": "promotion",
      "attributes": {
        "title": "Cruise fra New York 2016"
      }
    },
    {
      "id": "1",
      "type": "promotion",
      "attributes": {
        "title": "Rejse til Thailand 2016"
      }
    }
  ]
}
```

Get the active Providi promotions.

### HTTP Request
`GET http://providi.eu/API/promotions.php`

### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
country   | Required  | ISO 3166-1 alpha-2 country code for the country the leaderboard is requested for.
token     | Required  | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId    | Required  | The id of the current user. Must be paired with `token`.

### Response fields and POST Body
The following information is included in the response and can be sent in the POST body when creating or updating a user:

| Information    | Key in JSON response |
| -------------- | -------------------- |
| Promotion id   | `id`                 |
| Promotion name | `title`              |


## Promotion Scores

```js
jQuery.ajax({
  url: "http://providi.eu/API/promotion_score.php",
  data: {
    promotion_id: 1,
    country: 'dk',
    userId: 1,
    token: 'tokenAsdf'
  }
});
```

> Successful response

```json
{
  "data": {
    "id": "1",
    "type": "promotion_score",
    "attributes": {
      "positions": [
        {
          "position": "2",
          "name": "Seller",
          "points": "2",
          "image": "http://example.com/default-avatar.jpg"
        },
        {
          "position": "1",
          "name": "Seller 1",
          "points": "2",
          "image": "http://example.com/default-avatar.jpg"
        },
        {
          "position": "3",
          "name": "Seller 3",
          "points": "2",
          "image": "http://example.com/default-avatar.jpg"
        },
        {
          "position": "5",
          "name": "Seller 5",
          "points": "2",
          "image": "http://example.com/default-avatar.jpg"
        },
        {
          "position": "6",
          "name": "Seller 6",
          "points": "2",
          "image": "http://example.com/default-avatar.jpg"
        },
        {
          "position": "7",
          "name": "Seller 7",
          "points": "2",
          "image": "http://example.com/default-avatar.jpg"
        },
        {
          "position": "8",
          "name": "Seller 8",
          "points": "2",
          "image": "http://example.com/default-avatar.jpg"
        },
        {
          "position": "9",
          "name": "Seller 9",
          "points": "2",
          "image": "http://example.com/default-avatar.jpg"
        },
        {
          "position": "10",
          "name": "Seller 10",
          "points": "2",
          "image": "http://example.com/default-avatar.jpg"
        }
      ]
    }
  }
}
```

Get the top ten places in the specified Providi promotion.

### HTTP Request
`GET http://providi.eu/API/promotion_score.php`

### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
country   | Required  | ISO 3166-1 alpha-2 country code for the country the leaderboard is requested for.
promotion | Required  | Id of requested promotion names. Valid ids are obtained from calling [`promotions`](#active-promotions).
token     | Required  | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId    | Required  | The id of the current user. Must be paired with `token`.

### Response fields and POST Body
The following information is included in the response and can be sent in the POST body when creating or updating a user:

| Information     | Key in JSON response |
| --------------- | -------------------- |
| Array of scores | `positions[]`        |
| Points          | `points`             |
| Position        | `position`           |
| Promotion id    | `id`                 |
| Seller image    | `image`              |
| Seller name     | `name`               |
