# Profile Subdomain

```js
jQuery.ajax({
  url: "http://providi.eu/API/profile_subdomain.php",
  data: {
      userId: 1,
      token: 'tokenAsdf',
  }
});
```

> Parameters used in the POST body on check and update

```json
{
    "subdomain": "newsubdomain"
}
```

### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
token     | Required  | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId    | Required  | The id of the current user. Must be paired with `token`.

## Get Profile Subdomain

> Successful response

```json
{
  "data": {
    "type": "profile_subdomain",
    "id": "SC000XXXXXXX",
    "attributes": {
      "subdomain": "gabrielmuresan"
    }
  }
}
```

Get the value of the profile subdomain

### HTTP Request
`GET http://providi.eu/API/profile_subdomain.php`

## Check Profile Subdomain

> Parameters used in the POST body

```json
{
    "subdomain": "gabrielmuresan"
}
```

> Successful response

```json
{
  "data": {
    "type": "profile_subdomain",
    "id": "SC000XXXXXXX",
    "attributes": {
      "subdomain": "gabrielmuresan",
      "available": false
    }
  }
}
```

Checks if the subdomain is available.

### HTTP Request
`POST http://providi.eu/API/check_profile_subdomain.php`

### Response fields
The response fields contained in the request

| Information                | Key in JSON request or response
| -------------------------- | -------------------------------
| Desired subdomain          | `subdomain`
| Availability of subdomain  | `available`


## Update Profile Subdomain

> Parameters used in the POST body

```json
{
    "subdomain": "newsubdomain"
}
```

> Successful response

```json
{
  "data": {
    "type": "profile_subdomain",
    "id": "SC000XXXXXXX",
    "attributes": {
      "subdomain": "newsubdomain",
      "available": true,
      "updated": true
    }
  }
}
```

Updates the user subdomain if and only of that subdomain is available.

### HTTP Request
`POST http://providi.eu/API/update_profile_subdomain.php`

### Sent fields
The response fields contained in the request

| Information                 | Key in JSON request or response
| --------------------------- | -------------------------------
| Desired subdomain           | `subdomain`
| Availability of subdomain   | `available`
| Updated status of subdomain | `updated`
