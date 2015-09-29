# Errors

> Response data for failed request

```json
{
  "errors": [{
    "status": "401 Unauthorized"
  }]
}
```

The Providi API uses the following error codes:

Error Code | Meaning
---------- | -------
400 | Bad Request -- Your request sucks
401 | Unauthorized -- Your API key is wrong
403 | Forbidden -- The information requested is for administrators only
404 | Not Found -- The specified information could not be found
405 | Method Not Allowed -- You tried to access information with an invalid method
406 | Not Acceptable -- You requested a format that isn't json
410 | Gone -- The information requested has been removed from our servers
429 | Too Many Requests -- You're requesting too many requests! Slow down!
500 | Internal Server Error -- We had a problem with our server. Try again later.
503 | Service Unavailable -- We're temporarially offline for maintenance. Please try again later.
