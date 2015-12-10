# Orders
## View Order List

```js
jQuery.ajax({
  url: "http://providi.eu/API/order_list.php",
  data: {
      userId: 1,
      token: 'tokenAsdf',
  }
});
```

> Successful extended, not paginated, not filtered response

```json
{
    "meta": {
        "status": "all",
        "extended": true,
        "pagination": {
            "skipped": 0,
            "taken": 3,
            "count": 3
        }
    },
    "data": [{
        "type": "order",
        "id": "54645",
        "attributes": {
            "order_number": 1,
            "order_date": "2015-09-28T13:49:16+02:00",
            "order_total": 1790,
            "currency": "DKK",
            "customer_id": 1234567890,
            "customer_name": "Gabriel Muresan",
            "customer_email": "email@providi.dk",
            "customer_phone": "33333333",
            "shipping_address": "In a galaxy far far away",
            "billing_address": "Another galaxy",
            "billing_name": "My billing name",
            "payment_method": "PayPal",
            "payment_status": "completed",
            "product_count": 2,
            "products": [{
                "name": "PAKKE 1",
                "count": 2
            }]
        }
    }, {
        "type": "order",
        "id": "12335",
        "attributes": {
            "order_number": 2,
            "order_date": "2015-09-29T13:49:16+02:00",
            "order_total": 600,
            "currency": "DKK",
            "customer_id": 1234567890,
            "customer_name": "Gabriel Muresan",
            "customer_email": "email@providi.dk",
            "customer_phone": "33333333",
            "shipping_address": "In a galaxy far far away",
            "billing_address": "Another galaxy",
            "billing_name": "My billing name",
            "payment_method": "PayPal",
            "payment_status": "pending",
            "product_count": 1,
            "products": [{
                "name": "PAKKE 2",
                "count": 1
            }]
        }
    }, {
        "type": "order",
        "id": "51232",
        "attributes": {
            "order_number": 3,
            "order_date": "2015-10-29T13:49:16+02:00",
            "order_total": 1000,
            "currency": "DKK",
            "customer_id": 1234567890,
            "customer_name": "Gabriel Muresan",
            "customer_email": "email@providi.dk",
            "customer_phone": "33333333",
            "shipping_address": "In a galaxy far far away",
            "billing_address": "Another galaxy",
            "billing_name": "My billing name",
            "payment_method": "PayPal",
            "payment_status": "canceled",
            "product_count": 2,
            "products": [{
                "name": "PAKKE 1",
                "count": 1
            }, {
                "name": "PAKKE 2",
                "count": 1
            }]
        }
    }]
}
```

> Successful not extended, paginated, not filtered response

```json
{
    "meta": {
        "status": "all",
        "extended": false,
        "pagination": {
            "skipped": 1,
            "taken": 1,
            "count": 3
        }
    },
    "data": [{
        "type": "order",
        "id": "12335",
        "attributes": {
            "order_number": 2,
            "order_date": "2015-09-29T13:49:16+02:00",
            "order_total": 600,
            "currency": "DKK",
            "customer_id": 1234567890,
            "customer_name": "Gabriel Muresan",
            "customer_email": "email@providi.dk",
            "customer_phone": "33333333",
            "shipping_address": "In a galaxy far far away",
            "billing_address": "Another galaxy",
            "billing_name": "My billing name",
            "payment_method": "PayPal",
            "payment_status": "pending",
            "product_count": 1
        }
    }]
}
```

> Successful extended, not paginated, filtered response

```json
{
    "meta": {
        "status": "pending",
        "extended": true,
        "pagination": {
            "skipped": 0,
            "taken": 1,
            "count": 1
        }
    },
    "data": [{
        "type": "order",
        "id": "12335",
        "attributes": {
            "order_number": 2,
            "order_date": "2015-09-29T13:49:16+02:00",
            "order_total": 600,
            "currency": "DKK",
            "customer_id": 1234567890,
            "customer_name": "Gabriel Muresan",
            "customer_email": "email@providi.dk",
            "customer_phone": "33333333",
            "shipping_address": "In a galaxy far far away",
            "billing_address": "Another galaxy",
            "billing_name": "My billing name",
            "payment_method": "PayPal",
            "payment_status": "pending",
            "product_count": 1,
            "products": [{
                "name": "PAKKE 2",
                "count": 1
            }]
        }
    }]
}
```

Get all the orders for a specific user

### HTTP Request
`GET http://providi.eu/API/order_list.php`

### Request Parameters

Parameter | Required? | Default | Description
--------- | --------- | ------- | ----------------------------------------------------------------------------------------------------------------------------
token     | Required  |         | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId    | Required  |         | The id of the current user. Must be paired with `token`.
skip      | Optional  | 0       | (For pagination) The number of results to skip from result set.
take      | Optional  | 0       | (For pagination) The number of results to take in the result set. 0 for all
status    | Optional  | all     | Filter the results by status (all, canceled, pending, completed)
extended  | Optional  | false   | If extended is set to be true, the products are included in each order, else the `products` attribute should not be included

If `skip` and/or `take` are not specified they are considered as 0, meaning do not skip anything and take all.

### Response fields
The following information is included in the response:

Information     | Key in JSON response
--------------- | --------------------
Array of orders | `data`

Order Information    | Key in JSON response
-------------------- | --------------------
Order Number         | `order_number`
Creation Date        | `order_date`
Total value          | `order_total`
Total value currency | `currency`
Customer ID          | `customer_id`
Customer name        | `customer_name`
Customer email       | `customer_email`
Customer phone       | `customer_phone`
The shipping address | `shipping_address`
The billing address  | `billing_address`
The billing name     | `billing_name`
Method of payment    | `payment_method`
Status of payment    | `payment_status`
Number of products   | `product_count`
Product List         | `products`

## View Single Order

```js
jQuery.ajax({
  url: "http://providi.eu/API/order.php",
  data: {
      userId: 1,
      token: 'tokenAsdf',
      orderId: 12335
  }
});
```

> Successful extended response

```json
{
    "meta": {
        "extended": true
    },
    "data": {
        "type": "order",
        "id": "12335",
        "attributes": {
            "order_number": 2,
            "order_date": "2015-09-29T13:49:16+02:00",
            "order_total": 600,
            "currency": "DKK",
            "customer_id": 1234567890,
            "customer_name": "Gabriel Muresan",
            "customer_email": "email@providi.dk",
            "customer_phone": "33333333",
            "shipping_address": "In a galaxy far far away",
            "billing_address": "Another galaxy",
            "billing_name": "My billing name",
            "payment_method": "PayPal",
            "payment_status": "pending",
            "product_count": 1,
            "products": [{
                "name": "PAKKE 2",
                "count": 1
            }]
        }
    }
}
```

> Successful not extended response

```json
{
    "meta": {
        "extended": false
    },
    "data": {
        "type": "order",
        "id": "12335",
        "attributes": {
            "order_number": 2,
            "order_date": "2015-09-29T13:49:16+02:00",
            "order_total": 600,
            "currency": "DKK",
            "customer_id": 1234567890,
            "customer_name": "Gabriel Muresan",
            "customer_email": "email@providi.dk",
            "customer_phone": "33333333",
            "shipping_address": "In a galaxy far far away",
            "billing_address": "Another galaxy",
            "billing_name": "My billing name",
            "payment_method": "PayPal",
            "payment_status": "pending",
            "product_count": 1
        }
    }
}
```

Get one single order by id

### HTTP Request
`GET http://providi.eu/API/order.php`

### Request Parameters

Parameter | Required? | Default | Description
--------- | --------- | ------- | ----------------------------------------------------------------------------------------------------------------------------
token     | Required  |         | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId    | Required  |         | The id of the current user. Must be paired with `token`.
extended  | Optional  | true    | If extended is set to be false, the products are excluded in each order, else the `products` attribute should not be included NOTICE: this is the opposite way on the order_list endpoint where extended defaults to false.
