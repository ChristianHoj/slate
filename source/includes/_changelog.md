# Changelog
## 2016-05-19 (unreleased)
__Changed__

- `shop_access_codes`: added definitions for [Shop Access Codes](#shop-access-codes)

## 2016-05-17 (unreleased)
__Changed__

- `user_profile`: added upload image information to [User Profile](#user-profile) so that profile image can be updated

## 2016-04-27 (unreleased)
__Changed__

- `user_profile`: Added `front_page_html` parameter to [User Profile](#user-profile) [Response fields and POST Body](#response-fields-and-post-body)
## 2016-03-01 (unreleased)
__Changed__

- `user_profile`: Added `role` description to [Introduction](#introduction) in [User Roles](#user-roles)
- `user_profile`: Added the `role` parameter to [User Profile](#user-profile) [Response fields and POST Body](#response-fields-and-post-body)
- `user_profile`: Added the `cockpit_link` parameter to [User Profile](#user-profile) [Response fields and POST Body](#response-fields-and-post-body)
- `user_profile`: Added the `management_link` parameter to [User Profile](#user-profile) [Response fields and POST Body](#response-fields-and-post-body)
-`user_profile`: Changed examples and added new ones to exemplify the `role`

## 2016-01-27 (unreleased)
__Changed__

- `leads`: Added 2 more paremeters (`notes`, `status_change_date`) and 3 more statuses (`sms_sent`,`registered_as_new_supervisor`,`contact_later`).
- `leads`: Changed example to represent changes.
- `update_leads`: Added the `notes` paremeter and 3 more possible statuses `sms_sent`,`registered_as_new_supervisor`,`contact_later`).

## 2015-12-10 (unreleased)
__Changed__

- `order_list`: Added documentation and example successful responses for `order_list` endpoint.
- `order`: Added documentation and example successful responses for `order` endpoint.

## 2015-12-04
__Changed__

- `update_user_profile`: Add example response and expand documentation

## 2015-11-18
__Changed__

- `leads`: Add `not_contacted` as possible status
- `update_lead`: Add `not_contacted` as possible status
- `user_profile`: Add property `has_webpackage` to response
- Add `create_event` and `event_attend` to API


## 2015-11-13
__Changed__

- Add link to API test runner in API documentation
- `organization_leads`: Remove `data.attributes.id` property
- `vs_members`: Add `customerID`, `latest_login`, `meal_price`, `original_distributor`, `points`, `sign_up_date`, `unpaid_months` properties to response


## 2015-11-06
__Changed__

- `leads`: Change response data structure to proper {json:api}
- `leads`: Reintroduce `weight_loss` property to response
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
