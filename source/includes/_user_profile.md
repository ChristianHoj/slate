# User Profile

```php
  //upload image endpoint minimal example
  <?php
    $content = $_POST['image']['data'];
    $upload_location = $_SERVER['DOCUMENT_ROOT'] . "/";
    // remove the "image/" from "image/png" for example
    $upload_name = uniqid() .".". str_replace("image/", "", $_POST['image']['type']);
    $fp = fopen($upload_location . $upload_name, "wb");
    fwrite($fp, $content);
    fclose($fp);
  ?>
```

```js
jQuery.ajax({
  url: "http://providi.eu/API/user_profile.php",
  data: {
      userId: 1,
      token: 'tokenAsdf'
  }
});
```

> Successful response member

```json
{
  "data": {
    "type": "user",
    "id": "SC000XXXXXXX",
    "attributes": {
      "accountType": "0",
      "address": "Main Street 14",
      "city": "Lovkotsk",
      "company_name": "My Health Company",
      "country": "Slovenia",
      "first_name": "Gabriel",
      "has_webpackage": "true",
      "imageUrl": "http://example.com/default-avatar.jpg",
      "last_name": "Muresan",
      "partner_email": "partner@email.com",
      "partner_name": "Partner Muresan",
      "partner_skype_id": "partner_skype",
      "paypal_email": "mypaypal@email.com",
      "phone": "98765432",
      "quickpay_api_key": "klusfiuysbf74oha4bfauua42",
      "quickpay_merchant_id": "765234776",
      "recruit_firstname": "Recruiter",
      "recruit_lastname": "Muresan",
      "reference_code": "765384",
      "shipping_cost": "80",
      "skype_id": "seller_skype",
      "vs_link": "http://www.voressundhed.dk/?customerID=12345678",
      "vs_name": "My Vores Sundhed Name",
      "zipcode": "9230",
      "role": "member",
      "front_page_html": "<div><h3>Hello World</h3></div>",
      "front_page_description": "My description that will appear on the bhip shop frontpage"
    }
  }
}
```

> Parameters used in the POST body when updating

```json
{
    "address": "Main Street 14",
    "city": "Lovkotsk",
    "company_name": "My Health Company",
    "country": "Slovenia",
    "first_name": "Gabriel",
    "last_name": "Muresan",
    "partner_email": "partner@email.com",
    "partner_name": "Partner Muresan",
    "partner_skype_id": "partner_skype",
    "paypal_email": "mypaypal@email.com",
    "phone": "98765432",
    "quickpay_api_key": "klusfiuysbf74oha4bfauua42",
    "quickpay_merchant_id": "765234776",
    "recruit_firstname": "Recruiter",
    "recruit_lastname": "Muresan",
    "shipping_cost": "80",
    "skype_id": "seller_skype",
    "vs_link": "http://www.voressundhed.dk/?customerID=12345678",
    "vs_name": "My Vores Sundhed Name",
    "zipcode": "9230",
    "front_page_html": "<div><h3>Hello World</h3></div>",
    "front_page_description": "My NEW description that will appear on the bhip shop frontpage",
    "image": {
      "type": "image/png",
      "size": 5132,
      "data": "THE BINARY FILE CONTENT THAT CAN BE WRITTEN TO RECREATE THE FILE"
    }
}
```

> Successful response superuser

```json
{
  "data": {
    "type": "user",
    "id": "SC000XXXXXXX",
    "attributes": {
      ...
      "role": "superuser",
      "cockpit_link": "http://example.com/cockpit"
    }
  }
}
```

> Successful response administrator

```json
{
  "data": {
    "type": "user",
    "id": "SC000XXXXXXX",
    "attributes": {
      ...
      "role": "administrator",
      "cockpit_link": "http://example.com/cockpit",
      "management_link": "http://example.com/management"
    }
  }
}
```

Manage profile information for a single user.

### HTTP Request
`GET http://providi.eu/API/user_profile.php`

`POST http://providi.eu/API/create_user_profile.php`

`POST http://providi.eu/API/update_user_profile.php`

### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
token     | Required  | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId    | Required  | The id of the current user. Must be paired with `token`.

### Response fields and POST Body
The following information is included in the response and can be sent in the POST body when creating or updating a user. Any field under `attributes` is optional for `update_user_profile` and `create_user_profile` allowing for partial information updates.

| Information                | Key in JSON request or response
| -------------------------- | -------------------------------
| Account type                    | `accountType`
| Address                         | `address`
| City                            | `city`
| Company Name                    | `company_name`
| Country                         | `country`
| First name for recruitment      | `recruit_firstname`
| Has bought web package          | `has_webpackage`
| Last name for recruitment       | `recruit_lastname`
| Link to Vores Sundhed           | `vs_link`
| Name on Vores Sundhed           | `vs_name`
| Partner email                   | `partner_email`
| Partner name                    | `partner_name`
| Partner Skype id                | `partner_skype_id`
| Paypal email                    | `paypal_email`
| Phone                           | `phone`
| Profile image URL               | `imageUrl`
| Quickpay API key                | `quickpay_api_key`
| Quickpay merchant ID            | `quickpay_merchant_id`
| Reference code                  | `reference_code`
| Shipping cost                   | `shipping_cost`
| Skype id                        | `skype_id`
| User first name                 | `first_name`
| User id                         | `id`
| User last name                  | `last_name`
| Zipcode                         | `zipcode`
| User role                       | `role` see [User Roles](#user-roles)
| Link to cockpit                 | `cockpit_link`
| Link to management              | `management_link`
| Shop front page html            | `front_page_html`
| Shop front page description     | `front_page_description`
| Profile image meta and content  | `image` ( `image['data']`, `image['size']`, `image['type']` )

<aside class="notice">
The link to <b>cockpit</b> and <b>management</b> are only received for the users that have the necessary <a href="#user-roles">user role</a>.
</aside>
