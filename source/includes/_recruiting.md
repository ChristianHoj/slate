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
  "data": [
    {
      "type": "organization_leads",
      "id": "1",
      "attributes": {
        "age": "24",
        "email": "candidate@email.com",
        "expected_earnings": "3-5000",
        "id": "123456",
        "lead_assigned_date": "2015-10-27T14:25:16+01:00",
        "message": "To earn extra. Get more time with family.",
        "name": "Candi Date",
        "origin": "arbejd.dk",
        "phone": "12345678",
        "zipcode": "9235"
      }
    },
    {
      "type": "organization_leads",
      "id": "2",
      "attributes": {
        "age": "42",
        "email": "number4@example.com",
        "expected_earnings": "3-5000",
        "id": "123456",
        "lead_assigned_date": "2015-11-27T14:25:16+01:00",
        "message": "To earn extra. Get more time with family.",
        "name": "John Candi",
        "origin": "arbejd.dk",
        "phone": "12345678",
        "zipcode": "9235"
      }
    },
    { ... }
  ]
}
```

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


| Lead Information   | Key in JSON response |
| ------------------ | -------------------- |
| Age                | `age`                |
| Email              | `email`              |
| Expected earnings  | `expected_earnings`  |
| ID                 | `id`                 |
| Lead assigned date | `lead_assigned_date` |
| Message from lead  | `message`            |
| Name               | `name`               |
| Origin of lead     | `origin`             |
| Phone              | `phone`              |
| Zipcode            | `zipcode`            |


## Meeting attendees
```js
jQuery.ajax({
  url: "https://providi.eu/API/meeting_attendees.php",
  data: {
      userId: 1,
      token: 'tokenAsdf',
      country: 'dk',
      meeting_type: 'intro',
      from_date: '2015-09-01',
      to_date: '2015-09-15'
  }
});
```

> Successful response

```json
{
  "data": {
    "type": "meeting_attendees",
    "id": "1",
    "attributes": {
      "meetings": [
        {
          "city": "København",
          "country": "DK",
          "date": "2015-10-20",
          "meeting_type": "intro",
          "attendees": [
            { "name": "Attendee Numberone" },
            { "name": "Attendee Numbertwo" }
          ]
        },
        {
          "city": "Odense",
          "country": "DK",
          "date": "2015-11-04",
          "meeting_type": "startup",
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
Parameter    | Required? | Description
------------ | --------- | -----------
token        | Required  | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId       | Required  | The id of the current user. Must be paired with `token`.
country      | Optional  | List attendees of meetings in this country.
from_date    | Optional  | Date for earliest meeting.
to_date      | Optional  | Date for latest meeting.
meeting_type | Optional  | List meetings of this type. Possible values: "intro", "startup", "seminar".

### Response fields
The following information is included in the response:

| Information       | Key in JSON response |
| ----------------- | -------------------- |
| Array of meetings | `meetings`           |


| Meeting Information     | Key in JSON response |
| ----------------------- | -------------------- |
| City for meeting        | `city`               |
| Country for meeting     | `country`            |
| Date for meeting        | `date`               |
| Type of meeting         | `meeting_type`       |
| Array of attendee names | `attendees`          |
