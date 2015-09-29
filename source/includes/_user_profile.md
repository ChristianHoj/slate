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
      "accountType": 0,
      "imageUrl": "http://example.com/default-avatar.jpg",
      "name": "Gabriel Muresan",
      "partner_email": "partner@email.com",
      "partner_skype_id": "partner_skype",
      "reference_code": 765384,
      "skype_id": "seller_skype",
      "vs_link": "http://www.voressundhed.dk/?customerID=12345678"
    }
  }
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

### Response fields
The following information is included in the response:

| Information           | Key in JSON response |
| --------------------- | -------------------- |
| Link to Vores Sundhed | `vs_link`            |
| Partner email         | `partner_email`      |
| Partner Skype id      | `partner_skype_id`   |
| Profile image         | `imageUrl`           |
| Referencce code       | `reference_code`     |
| Skype id              | `skype_id`           |
| User id               | `id`                 |
| User name             | `name`               |
