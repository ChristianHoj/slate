# Shop Access Codes

## Create Code

```js
jQuery.ajax({
  url: "http://providi.eu/API/customer_shop_access_codes.php",
  data: {
      distributorRef: 10000,
      name: "Gabriel Muresan",
      phone: "12345678",
      email: "gabriel@email.com",
  }
});
```

> Successful response

```json
{
  "data": {
    "type": "create_shop_access_code",
    "id": "31321227",
    "attributes": {
      "status": "OK",
      "email": "gabriel@email.com",
      "name": "Gabriel Muresan",
      "phone": "12345678"
    }
  }
}
```

Creates a new shop access code for a specific distributor
<aside class="notice">
This request is `unauthorized` and does not require a `token` and `userId` for authentication.
</aside>
### HTTP Request
`POST http://providi.eu/API/customer_shop_access_codes.php`

### Request Parameters

Parameter       | Required? | Description
--------------- | --------- | -------------------------------------
distributorRef  | Required  | The distributor reference code
name            | Required  | Name of the customer
phone           | Required  | Mobile phone number of the customer
email           | Required  | Email of the customer

### Response fields
The following information is included in the response:

JSON key             | Description
-------------------- | -----------------------------------
status               | The status of the request
name                 | The name entered in the request
email                | The email entered in the request
phone                | The phone entered in the request

## Get all codes

```js
jQuery.ajax({
  url: "http://providi.eu/API/customer_shop_access_codes.php",
  data: {
      token: "tokenAsdf",
      userId: 1
  }
});
```

> Successful response

```json
{
    "data": {
        "type": "shop_access_code",
        "id": "0",
        "attributes": {
          "codes": [
            {
              "email": "gabriel@email.com",
              "name": "Gabriel Marcel Muresan",
              "phone": "12345678",
              "code": "",
              "requested_date": "2015-09-28T13:49:16+02:00"
            },{
              "email": "phantom@email.com",
              "name": "Spooky Adam",
              "phone": "12345678",
              "code": "SoScared",
              "requested_date": "2015-09-28T13:49:16+02:00"
            }
          ]
        }
    }
}
```

Get all shop access codes addressed to a specific distributor
### HTTP Request
`GET http://providi.eu/API/customer_shop_access_codes.php`

### Request Parameters

Parameter   | Required? | Description
----------- | --------- | -------------------------------------
token       | Required  | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId      | Required  | The id of the current user. Must be paired with `token`.

### Response fields
The following information is included in the response:

JSON key             | Description
-------------------- | -----------------------------------
requests             | Represents a list of requests for the distributor.

## Update code

```js
jQuery.ajax({
  url: "http://providi.eu/API/customer_shop_access_codes.php",
  data: {
      token: "tokenAsdf",
      userId: 1,
      requestEmail: "gabriel@email.com",
      code: "NewCodeForGabriel"
  }
});
```

> Successful response

```json
{
  "data": {
    "type": "shop_access_code",
    "id": 1,
    "attributes": {
      "status": "OK",
      "email": "gabriel@email.com",
      "code": "NewCodeForGabriel"
    }
  }
}
```

Updates a shop access code for a specific distributor and customer
### HTTP Request
`PUT http://providi.eu/API/customer_shop_access_codes.php`

### Request Parameters

Parameter       | Required? | Description
--------------- | --------- | -------------------------------------
token           | Required  | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId          | Required  | The id of the current user. Must be paired with `token`.
email           | Required  | Email of the customer to which the code will be updated
code            | Required  | The code to attach to the request so that the user will be able to access a distributor shop using it.

### Response fields
The following information is included in the response:

JSON key             | Description
-------------------- | -----------------------------------
status               | The status of the request
email                | The customer email
code                 | The code that will be used by the customer to enter shop

## Validate code

```js
jQuery.ajax({
  url: "http://providi.eu/API/validate_shop_access_code.php",
  data: {
      code: "NewCodeForGabriel",
  }
});
```

> Successful response

```json
{
  "data": {
    "type": "validate_shop_access_code",
    "id": 0,
    "attributes": {
      "status": "OK"
    }
  }
}
```

> Unsuccessfull response

```json
{
  "errors": [{
    "status": "401 Unauthorized"
  }]
}
```

Validates a shop access code
<aside class="notice">
This request is `unauthorized` and does not require a `token` and `userId` for authentication.
</aside>
### HTTP Request
`POST http://providi.eu/API/validate_shop_access_code.php`

### Request Parameters

Parameter       | Required? | Description
--------------- | --------- | -------------------------------------
distributorRef  | Required  | The distributor reference code
code            | Required  | The code to request validation for

### Response fields
The following information is included in the response:

JSON key             | Description
-------------------- | -----------------------------------
status               | The status of the request
