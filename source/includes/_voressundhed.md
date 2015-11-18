# Vores Sundhed
## Members
```js
jQuery.ajax({
  url: "http://providi.eu/API/vs_members.php",
  data: {
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
      "type": "vs_members",
      "id": "123455",
      "attributes": {
        "address": "Main Street 17",
        "customerID": "221211240006",
        "kalorie_login": "http://www.kalorieregnskab.dk/login.php?customerID=313214470001&vu=1444061739&hash=d966c33806b0f28a7d2bbbe",
        "latest_login": "2015-11-07",
        "meal_price": "40 kroner/5 kroner",
        "name": "Carina Holm",
        "new_target_link": "http://www.voressundhed.dk/distributor/login.php?reference=31321447&hash=da305792bc0d113bd63e9&redirect=http%3A%2F%2Fwww.voressundhed.dk%2Fpersonificeret%2Fupdate.before_after_ny.php%3FcustRef%3D313214470001",
        "old_target_link": "http://providi.eu/sc.support_links/editCust.php?id=55229&refer=313214470001",
        "phone": "12345678",
        "points": "69",
        "sign_up_date": "2015-09-10",
        "unpaid_months": "2",
        "username": "abcd@email.com",
        "vs_login_link": "http://www.voressundhed.dk/distributor/customer_login.php?customerID=313212270001&vu=1444051539&hash=d18a31acfb7e2a1c6410d30bd"
      }
    },
    {
      "type": "vs_members",
      "id": "3464564",
      "attributes": {
        "address": "Side Avenue 86",
        "customerID": "221288760006",
        "kalorie_login": "http://www.kalorieregnskab.dk/login.php?customerID=313214470001&vu=1444061739&hash=d966c33806b0f28a7d2bbbe",
        "latest_login": "2015-11-07",
        "meal_price": "40 kroner/5 kroner",
        "name": "Bo Bech",
        "new_target_link": "http://www.voressundhed.dk/distributor/login.php?reference=31321447&hash=da305792bc0d113bd63e9&redirect=http%3A%2F%2Fwww.voressundhed.dk%2Fpersonificeret%2Fupdate.before_after_ny.php%3FcustRef%3D313214470001",
        "old_target_link": "http://providi.eu/sc.support_links/editCust.php?id=55229&refer=313214470001",
        "phone": "12345678",
        "points": "69",
        "sign_up_date": "2015-09-10",
        "unpaid_months": "2",
        "username": "bobech@example.com",
        "vs_login_link": "http://www.voressundhed.dk/distributor/customer_login.php?customerID=313212270001&vu=1444051539&hash=d18a31acfb7e2a1c6410d30bd"
      }
    }
  ]
}
```

Get all members of Vores Sundhed that the seller has signed up.

### HTTP Request
`GET http://providi.eu/API/vs_members.php`

### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
token     | Required  | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId    | Required  | The id of the current user. Must be paired with `token`.

### Response fields
The following information is included in the response:

| Information       | Key in JSON response |
| ----------------- | -------------------- |
| Array of members  | `members`            |


| Member Information           | Key in JSON response   |
| ---------------------------- | ---------------------- |
| Address                      | `address`              |
| Customer ID                  | `customerID`           |
| Debt period                  | `unpaid_months`        |
| Latest login date            | `latest_login`         |
| Link to Kalorieregnskab      | `kalorie_login`        |
| Link to new targets          | `new_target_link`      |
| Link to old targets          | `old_target_link`      |
| Link to Vores Sundhed        | `vs_login_link`        |
| Name                         | `name`                 |
| Name of original distributor | `original_distributor` |
| Phone                        | `phone`                |
| Price per meal               | `meal_price`           |
| Questionnaire points         | `points`               |
| Sign up date                 | `sign_up_date`         |
| VS Member ID                 | `id`                   |
| VS Member user name          | `username`             |
