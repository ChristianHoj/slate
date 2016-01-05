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
            "count": 3,
            "total_count": 3
        }
    },
    "data": [{
        "type": "order",
        "id": "54645",
        "relationships": {
            "customer": {
                "data": {
                    "type": "customer",
                    "id": 1234567890,
                    "attributes": {
                        "first_name": "Gabriel Marcel",
                        "last_name": "Muresan",
                        "email": "email@providi.dk",
                        "phone": "33333333"
                    }
                }
            }
        },
        "attributes": {
            "order_number": 1,
            "order_date": "2015-09-28T13:49:16+02:00",
            "order_total": 1790,
            "currency": "DKK",
            "shipping_address": "In a galaxy far far away",
            "billing_address": "Another galaxy",
            "billing_name": "My billing name",
            "payment_method": "PayPal",
            "payment_status": "completed",
            "product_count": 2,
            "products": [{
                "name": "PAKKE 1",
                "count": 2,
                "price": 895
            }]
        }
    }, {
        "type": "order",
        "id": "12335",
        "relationships": {
            "customer": {
                "data": {
                    "type": "customer",
                    "id": 1234567890,
                    "attributes": {
                        "first_name": "Gabriel Marcel",
                        "last_name": "Muresan",
                        "email": "email@providi.dk",
                        "phone": "33333333"
                    }
                }
            }
        },
        "attributes": {
            "order_number": 2,
            "order_date": "2015-09-29T13:49:16+02:00",
            "order_total": 600,
            "currency": "DKK",
            "shipping_address": "In a galaxy far far away",
            "billing_address": "Another galaxy",
            "billing_name": "My billing name",
            "payment_method": "PayPal",
            "payment_status": "pending",
            "product_count": 1,
            "products": [{
                "name": "PAKKE 2",
                "count": 1,
                "price": 600
            }]
        }
    }, {
        "type": "order",
        "id": "51232",
        "relationships": {
            "customer": {
                "data": {
                    "type": "customer",
                    "id": 1234567890,
                    "attributes": {
                        "first_name": "Gabriel Marcel",
                        "last_name": "Muresan",
                        "email": "email@providi.dk",
                        "phone": "33333333"
                    }
                }
            }
        },
        "attributes": {
            "order_number": 3,
            "order_date": "2015-10-29T13:49:16+02:00",
            "order_total": 1000,
            "currency": "DKK",
            "shipping_address": "In a galaxy far far away",
            "billing_address": "Another galaxy",
            "billing_name": "My billing name",
            "payment_method": "PayPal",
            "payment_status": "canceled",
            "product_count": 2,
            "products": [{
                "name": "PAKKE 1",
                "count": 1,
                "price": 400
            }, {
                "name": "PAKKE 2",
                "count": 1,
                "price": 600
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
            "count": 1,
            "total_count": 3
        }
    },
    "data": [{
        "type": "order",
        "id": "12335",
        "relationships": {
            "customer": {
                "data": {
                    "type": "customer",
                    "id": 1234567890,
                    "attributes": {
                        "first_name": "Gabriel Marcel",
                        "last_name": "Muresan",
                        "email": "email@providi.dk",
                        "phone": "33333333"
                    }
                }
            }
        },
        "attributes": {
            "order_number": 2,
            "order_date": "2015-09-29T13:49:16+02:00",
            "order_total": 600,
            "currency": "DKK",
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
            "count": 1,
            "total_count": 1
        }
    },
    "data": [{
        "type": "order",
        "id": "12335",
        "relationships": {
            "customer": {
                "data": {
                    "type": "customer",
                    "id": 1234567890,
                    "attributes": {
                        "first_name": "Gabriel Marcel",
                        "last_name": "Muresan",
                        "email": "email@providi.dk",
                        "phone": "33333333"
                    }
                }
            }
        },
        "attributes": {
            "order_number": 2,
            "order_date": "2015-09-29T13:49:16+02:00",
            "order_total": 600,
            "currency": "DKK",
            "shipping_address": "In a galaxy far far away",
            "billing_address": "Another galaxy",
            "billing_name": "My billing name",
            "payment_method": "PayPal",
            "payment_status": "pending",
            "product_count": 1,
            "products": [{
                "name": "PAKKE 2",
                "count": 1,
                "price": 600
            }]
        }
    }]
}
```

Get all the orders for a specific user.

### HTTP Request
`GET http://providi.eu/API/order_list.php`

### Request Parameters

Parameter | Required? | Default   | Description
--------- | --------- | --------- | ----------------------------------------------------------------------------------------------------------------------------
token     | Required  |           | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId    | Required  |           | The id of the current user. Must be paired with `token`.
skip      | Optional  | 0         | (For pagination) The number of results to skip from result set.
take      | Optional  | 0         | (For pagination) The number of results to take in the result set. 0 for all
status    | Optional  | all       | Filter the results by status (all, canceled, pending, completed)
extended  | Optional  | `false`   | If extended is set to be `true`, the products are included in each order, else the `products` attribute should not be included

If `skip` and/or `take` are not specified they are considered as 0, meaning do not skip anything and take all.

### Response fields
The following information is included in the response:

JSON key             | Description
-------------------- | -----------------------------------
data                 | The wanted result, the `Orders`, are all located inside the `data` attribute in an array
relationships        | Each `Order` has a `Customer` relationship associated, the one who created and (possibly) received the order
meta                 | Some information about the way the `data` is structured, the `pagination`, the `status` filter and the `extended` option

#### Relationships
JSON key             | Description
-------------------- | -----------------------------------
customer             | The information about the `Customer` who created the `Order`

#### Customer
JSON Key             | Description
-------------------- | ------------------------
id                   | Customer ID           
first_name           | Customer first name   
last_name            | Customer last name    
email                | Customer email        
phone                | Customer phone        

#### Meta
JSON Key             | Description
-------------------- | -----------------------------------
status               | If the request was filtered by status, then this will appear in the response. For example if the `orderes` were filtered by "canceled" that this will be the value of the `status` property.
extended             | The request can be extended or not, meaning that if it is then it includes the products for each order as well
pagination           | The pagination is the attribute describing the values received by order in relation the whole set of data

#### Pagination
JSON Key             | Description
-------------------- | -----------------------------------
skipped              | The number of records skipped from the current query (skipped AFTER filters)
count                | The number of records AFTER filtered and skipped
total_count          | The total number of records BEFORE skipping, but AFTER filters

#### Product
JSON Key             | Description
-------------------- | -----------------------------------
name                 | The name or display name of the product
count                | The number of products of this type in the current order
price                | The price for 1 product of this kind (not the total)

#### Order
JSON Key             | Description
-------------------- | --------------------
order_number         | Order Number        
order_date           | Creation Date       
order_total          | Total value         
currency             | Total value currency
shipping_address     | The shipping address
billing_address      | The billing address
billing_name         | The billing name    
payment_method       | Method of payment   
payment_status       | Status of payment   
product_count        | Number of Products  
products             | Array of Products   

JSON Key             | Description
-------------------- | ---------------------
order_number         | Order Number         
order_date           | Creation Date        
order_total          | Total value          
currency             | Total value currency

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
    "relationships": {
        "customer": {
            "data": {
                "type": "customer",
                "id": 1234567890,
                "attributes": {
                    "first_name": "Gabriel Marcel",
                    "last_name": "Muresan",
                    "email": "email@providi.dk",
                    "phone": "33333333"
                }
            }
        }
    },
    "data": {
        "type": "order",
        "id": "12335",
        "attributes": {
            "order_number": 2,
            "order_date": "2015-09-29T13:49:16+02:00",
            "order_total": 600,
            "currency": "DKK",
            "shipping_address": "In a galaxy far far away",
            "billing_address": "Another galaxy",
            "billing_name": "My billing name",
            "payment_method": "PayPal",
            "payment_status": "pending",
            "product_count": 1,
            "products": [{
                "name": "PAKKE 2",
                "count": 1,
                "price": 600
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
    "relationships": {
        "customer": {
            "data": {
                "type": "customer",
                "id": 1234567890,
                "attributes": {
                    "first_name": "Gabriel Marcel",
                    "last_name": "Muresan",
                    "email": "email@providi.dk",
                    "phone": "33333333"
                }
            }
        }
    },
    "data": {
        "type": "order",
        "id": "12335",
        "attributes": {
            "order_number": 2,
            "order_date": "2015-09-29T13:49:16+02:00",
            "order_total": 600,
            "currency": "DKK",
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
