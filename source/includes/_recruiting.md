# Recruiting

## View Leads
```js
jQuery.ajax({
  url: "https://providi.eu/API/organization_leads.php",
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
    "type": "organization_leads",
    "id": 1,
    "attributes": {
      "leads": [
        {
          "age": 24,
          "email": "candidate@email.com",
          "expected_earnings": "3-5000",
          "id": 123456,
          "interest_date": "2015-10-02",
          "message": "To earn extra. Get more time with family.",
          "name": "Candi Date",
          "order": 1,
          "origin": "arbejd.dk",
          "phone": "12345678",
          "zipcode": "9235"
        },
        {
          ...
        }
      ]
    }
  }
}
```

<aside class="warning">
  <strong>Not implemented</strong>
</aside>

Get all organizational leads (for recruitment) the seller has acquired for specified period.

### HTTP Request
`GET https://providi.eu/API/organization_leads.php`

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


| Lead Information  | Key in JSON response |
| ----------------- | -------------------- |
| Age               | `age`                |
| Email             | `email`              |
| Expected earnings | `expected_earnings`  |
| ID                | `id`                 |
| Lead sign up date | `interest_date`      |
| Message from lead | `message`            |
| Name              | `name`               |
| Origin of lead    | `orgin`              |
| Phone             | `phone`              |
| Sort order        | `order`              |
| Zipcode           | `zipcode`            |


## Meeting attendees
```js
jQuery.ajax({
  url: "https://providi.eu/API/meeting_attendees.php",
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
    "type": "meeting_attendees",
    "id": 1,
    "attributes": {
      "meetings": [
        {
          "city": "København",
          "date": "2015-10-20",
          "attendees": [
            { "name": "Attendee Numberone" },
            { "name": "Attendee Numbertwo" }
          ]
        },
        {
          "city": "Odense",
          "date": "2015-11-04",
          "attendees": [
            { "name": "Attendee Numberthree" },
            { "name": "Attendee Numberfour" }
          ]
        },
        ...
      ]
    }
  }
}
```

<aside class="warning">
  <strong>Not implemented</strong>
</aside>

Get all organizational members that are attending upcoming meetings.

### HTTP Request
`GET https://providi.eu/API/meeting_attendees.php`

### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
token     | Required  | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId    | Required  | The id of the current user. Must be paired with `token`.

### Response fields
The following information is included in the response:

| Information       | Key in JSON response |
| ----------------- | -------------------- |
| Array of meetings | `meetings`           |


| Meeting Information     | Key in JSON response |
| ----------------------- | -------------------- |
| City for meeting        | `city`               |
| Date for meeting        | `date`               |
| Array of attendee names | `attendees`          |
