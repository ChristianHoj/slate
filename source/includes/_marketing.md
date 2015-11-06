# Marketing

## View Leads
```js
jQuery.ajax({
  url: "https://providi.eu/API/leads.php",
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
  "data": [
    {
      "type": "leads",
      "id": "123456",
      "attributes": {
        "email": "candidate@email.com",
        "lead_assigned_date": "2015-10-27T14:25:16+01:00",
        "lead_type": "own",
        "message": "Some explanation of why weight loss is desired.",
        "name": "Candi Date",
        "origin": "idealvaegt.dk",
        "phone": "12345678",
        "serious": "yes",
        "status": "signed_up",
        "weight_loss": "10-15",
        "zipcode": "9235"
      }
    },
    {
      ...
    }
  ]
}
```

<aside class="warning">
  <strong>Implemented. Test not passing.</strong>
</aside>

Get all leads the seller has acquired both by own marketing efforts and as bonus for specified period.

### HTTP Request
`GET https://providi.eu/API/leads.php`

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


| Lead Information     | Key in JSON response | Possible values
| -------------------- | -------------------- | ---------------
| Email                | `email`              |
| Expected weight loss | `weight_loss`        |
| ID                   | `id`                 |
| Lead assigned date   | `lead_assigned_date` |
| Lead type            | `lead_type`          | `bonus`, `own`
| Message from lead    | `message`            |
| Name                 | `name`               |
| Phone                | `phone`              |
| Status               | `status`             | "", `signed_up`, `not_available`, `no_show`, `no_money`, `not_interested`, `non_existing`, `never_asked_for_contact`, `no`
| Weight loss interest | `serious`            | `yes`, `maybe`, `no`
| Zipcode              | `zipcode`            |


## Update Lead
```js
jQuery.post({
  url: "https://providi.eu/API/update_lead.php",
  data: {
      userId: 1,
      token: 'tokenAsdf',
      leadId: 123456,
      status: 'non_existing'
  }
});
```

> Successful response

```json
{
  "data": {
    "type": "update_lead",
    "id": "123456",
    "attributes": {
      "status": "ok"
    }
  }
}
```

<aside class="warning">
  <strong>Not tested.</strong>
</aside>

Update status for specific lead.

### HTTP Request
`POST https://providi.eu/API/update_lead.php`

### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
token     | Required  | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId    | Required  | The id of the current user. Must be paired with `token`.
leadId    | Required  | The id of the lead to update.
status    | Required  | Possible values: "", `signed_up`, `not_available`, `no_show`, `no_money`, `not_interested`, `non_existing`, `never_asked_for_contact`, `no`
