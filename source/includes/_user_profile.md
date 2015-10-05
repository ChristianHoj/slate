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
      "address": "Main Street 14",
      "city": "Lovkotsk",
      "company_name": "My Health Company",
      "country": "Slovenia",
      "imageUrl": "http://example.com/default-avatar.jpg",
      "name": "Gabriel Muresan",
      "partner_email": "partner@email.com",
      "partner_name": "Partner Muresan",
      "partner_skype_id": "partner_skype",
      "paypal_email": "mypaypal@email.com",
      "phone": "98765432",
      "quickpay_api_key": "klusfiuysbf74oha4bfauua42",
      "quickpay_merchant_id": 765234776,
      "recruit_firstname": "Recruiter",
      "recruit_lastname": "Muresan",
      "reference_code": 765384,
      "shipping_cost": 80,
      "skype_id": "seller_skype",
      "vs_link": "http://www.voressundhed.dk/?customerID=12345678",
      "vs_name": "My Vores Sundhed Name",
      "zipcode": 9230
    }
  }
}
```

<aside class="warning">
  <strong>Not implemented</strong>
</aside>

Manage profile information for a single user.

### HTTP Request
`GET https://providi.eu/API/profile.php`

`POST https://providi.eu/API/create_profile.php`

`POST https://providi.eu/API/update_profile.php`

### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
token | Required | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId | Required | The id of the current user. Must be paired with `token`.

### Response fields
The following information is included in the response and can be sent in the POST body when creating or updating a user:

| Information                 | Key in JSON response   |
| --------------------------- | ---------------------- |
| Address                     | `address`              |
| City                        | `city`                 |
| Company Name                | `company_name`         |
| Country                     | `country`              |
| First name for recruitment  | `recruit_firstname`    |
| Last name for recruitment   | `recruit_lastname`     |
| Link to Vores Sundhed       | `vs_link`              |
| Name on Vores Sundhed       | `vs_name`              |
| Partner email               | `partner_email`        |
| Partner name                | `partner_name`         |
| Partner Skype id            | `partner_skype_id`     |
| Paypal email                | `paypal_email`         |
| Profile image URL           | `imageUrl`             |
| Quickpay API key            | `quickpay_api_key`     |
| Quickpay merchant ID        | `quickpay_merchant_id` |
| Reference code              | `reference_code`       |
| Shipping cost               | `shipping_cost`        |
| Skype id                    | `skype_id`             |
| User id                     | `id`                   |
| User name                   | `name`                 |
| Zipcode                     | `zipcode`              |
