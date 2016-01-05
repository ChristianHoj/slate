# Sponsors
## Lookup Sponsor

```js
jQuery.ajax({
  url: "http://providi.eu/API/lookup_sponsor.php",
  data: {
      username: "username"
  }
});
```

> Successful response

```json
{
    "data": {
        "type": "sponsor",
        "id": "12335",
        "attributes": {
            "name": "Gabriel Marcel Muresan"
        }
    }
}
```

> Error Response 404 Not Found

```json
{
  "errors": [{
    "status": "404 Not Found"
  }]
}
```

Verify a sponsor ID by requesting the name associated with that ID.
<aside class="notice">
This request is `unauthorized` and does not require a `token` and `userId` for authentication.
</aside>
### HTTP Request
`GET http://providi.eu/API/lookup_sponsor.php`

### Request Parameters

Parameter   | Required? | Description
----------- | --------- | -------------------------------------
username    | Required  | The sponsor ID to check if it exists.

### Response fields
The following information is included in the response:

JSON key             | Description
-------------------- | -----------------------------------
name                 | Represents the name of the sponsor with the specified ID.

### Error Not Found

For all the errors check [`error page`](#errors).
