# Changelog

## 2015-11-13
__Changed__

- Add link to API test runner in API documentation
- `organization_leads`: Remove `data.attributes.id` property
- `vs_members`: Add `customerID`, `latest_login`, `meal_price`, `original_distributor`, `points`, `sign_up_date`, `unpaid_months` properties to response


## 2015-11-06
__Changed__

- `leads`: Change response data structure to proper {json:api}
- `leads`: Reintroduce `weight_loss` properties to response
- `organization_leads`: Change response data structure to proper {json:api}
- `promotion_score`: Clarify how to use id property
- `promotions`: Change response data structure to proper {json:api}
- `user_profile`: Add properties `accountType` and `phone` to response documentation
- `vs_members`: Change response data structure to proper {json:api}

## 2015-10-27
__Changed__

- `leads`: Add `lead_assigned_date` property to response
- `leads`: Remove `order` and `weight_loss` properties from response
- `organization_leads`: Add `lead_assigned_date` property to response
- `organization_leads`: Remove `order` property from response
- `meeting_attendees`: Add `country`, `from_date`, `to_date`, `meeting_type` parameters to request
- `meeting_attendees`: Add `country` and `meeting_type` properties to response
- `profile`: Replace `name` property with `first_name`and `last_name` in response

## 2015-10-07
First version of API documentation
