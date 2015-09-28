# User Profile

```js
jQuery.ajax({
  url: "https://providi.eu/API/profile.php",
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

Get all profile information about a single user.

### HTTP Request
`GET https://providi.eu/API/profile.php`

### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
token | Required | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId | Required | The id of the current user. Must be paired with `token`.
