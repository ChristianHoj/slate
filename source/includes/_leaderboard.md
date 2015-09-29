# Leaderboard

```js
jQuery.ajax({
  url: "https://providi.eu/API/leaderboard.php",
  data: {
    period: ['this-week', 'previous-week', 'last-30-days'],
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
    "id": 0,
    "type": "leaderboards",
    "attributes": {
      "thisWeek": {
        "period": {
          "from": "2015-09-07",
          "until": "2015-09-13"
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

<aside class="warning">
  Implemented September 23 2015.
  <br>
  <strong>Changes on September 28 2015</strong>
  <ul>
    <li>'country' added as request parameter</li>
    <li>Format of 'from' and 'to' dates in response changed to ISO 8601</li>
  </ul>
</aside>

Get the top ten sellers in Providi for specified periods.

### HTTP Request
`GET https://providi.eu/API/leaderboard.php`

### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
country | Required | ISO 3166-1 alpha-2 country code for the country the leaderboard is requested for.
period | Required | Array of requested lists. Valid values are `this-week`, `previous-week`, `last-30-days`.
token | Required | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId | Required | The id of the current user. Must be paired with `token`.
