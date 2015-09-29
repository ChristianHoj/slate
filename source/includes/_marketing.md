# Marketing / Sales

## Seller's own leads
```js
jQuery.ajax({
  url: "https://providi.eu/API/seller_leads.php",
  data: {
      userId: 1,
      token: 'tokenAsdf',
      from_date: '2015-09-01',
      to_date: '2015-09-15'
  }
});
```

> Successful response

```json
{
  "data": {
    "type": "seller_leads",
    "id": 1,
    "attributes": {
      "leads": [
        {
          "address": "Candidate Street 23",
          "city": "Candidation",
          "email": "candidate@email.com",
          "name": "Candi Date",
          "phone": "12345678",
          "zipcode": "92345"
        },
        {
          "address": "Seller Avenue 87",
          "city": "Aalborg",
          "email": "candidate2@email.com",
          "name": "Will Buyer",
          "phone": "87654321",
          "zipcode": "54329"
        }
      ]
    }
  }
}
```

<aside class="warning">
  <strong>Not implemented</strong>
</aside>

Get all leads the seller has acquired by his own marketing efforts for specified period.


### HTTP Request
`GET https://providi.eu/API/seller_leads.php`

### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
token     | Required  | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId    | Required  | The id of the current user. Must be paired with `token`.
from_date | Optional  | Date for earliest lead signup.
to_date   | Optional  | Date for latest lead signup.

If either `from_date` or `to_date` is not supplied the response will be for the past 7 days.

### Response fields
The following information is included in the response:

| Information       | Key in JSON response |
| ----------------- | -------------------- |
| Array of leads    | `leads`              |
| Lead address      | `leads[0].address`   |
| Lead city         | `leads[0].city`      |
| Lead email        | `leads[0].email`     |
| Lead name         | `leads[0].name`      |
| Lead phone        | `leads[0].phone`     |
| Lead zipcode      | `leads[0].zipcode`   |



## Bonus leads
```js
jQuery.ajax({
  url: "https://providi.eu/API/bonus_leads.php",
  data: {
      userId: 1,
      token: 'tokenAsdf',
      from_date: '2015-09-01',
      to_date: '2015-09-15'
  }
});
```

> Successful response

```json
{
  "data": {
    "type": "bonus_leads",
    "id": 1,
    "attributes": {
      "leads": [
        {
          "address": "Candidate Street 23",
          "city": "Candidation",
          "email": "candidate@email.com",
          "name": "Candi Date",
          "phone": "12345678",
          "zipcode": "92345"
        },
        {
          "address": "Seller Avenue 87",
          "city": "Aalborg",
          "email": "candidate2@email.com",
          "name": "Will Buyer",
          "phone": "87654321",
          "zipcode": "54329"
        }
      ]
    }
  }
}
```

<aside class="warning">
  <strong>Not implemented</strong>
</aside>

Get all bonus leads the seller has earned for specified period.


### HTTP Request
`GET https://providi.eu/API/bonus_leads.php`

### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
token     | Required  | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId    | Required  | The id of the current user. Must be paired with `token`.
from_date | Optional  | Date for earliest lead signup.
to_date   | Optional  | Date for latest lead signup.

If either `from_date` or `to_date` is not supplied the response will be for the past 7 days.


## Reference leads
```js
jQuery.ajax({
  url: "https://providi.eu/API/reference_leads.php",
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
    "type": "reference_leads",
    "id": 1,
    "attributes": {
      "leads": [
        {
          "address": "Candidate Street 23",
          "city": "Candidation",
          "email": "candidate@email.com",
          "name": "Candi Date",
          "phone": "12345678",
          "zipcode": "92345"
        },
        {
          "address": "Seller Avenue 87",
          "city": "Aalborg",
          "email": "candidate2@email.com",
          "name": "Will Buyer",
          "phone": "87654321",
          "zipcode": "54329"
        }
      ]
    }
  }
}
```

<aside class="warning">
  <strong>Not implemented</strong>
</aside>

Get all reference leads the seller has earned for specified period.


### HTTP Request
`GET https://providi.eu/API/reference_leads.php`

### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
token     | Required  | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId    | Required  | The id of the current user. Must be paired with `token`.
