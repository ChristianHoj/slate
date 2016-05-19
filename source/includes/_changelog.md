# Changelog
## 2016-05-19
__New functions__

- `order_list`: See [documentation and example successful responses](#view-order-list)
- `order`: See [documentation and example successful responses](#view-single-order)
- `shop_access_codes`: Added definitions for [Shop Access Codes](#shop-access-codes)


__Changed__

- `leads`: Added 2 more parameters (`notes`, `status_change_date`) and 3 more statuses (`sms_sent`, `registered_as_new_supervisor`, `contact_later`) [Updated documentation](#view-leads)
- `update_leads`: Added the `notes` parameter and 3 more possible statuses `sms_sent`, `registered_as_new_supervisor`, `contact_later`) [Updated documentation](#update-lead)
- `update_user_profile`: Add example response and expand [documentation](#user-profile)
- `user_profile`: Added `cockpit_link`, `front_page_html`, `management_link` and `role` parameters to [User Profile](#user-profile) [Response fields and POST Body](#response-fields-and-post-body).
  - [More info on `front_page_html`](https://providi.atlassian.net/projects/PROV/issues/PROV-61)
- `user_profile`: Added `role` description to [Introduction](#introduction) in [User Roles](#user-roles)
- `user_profile`: Added upload image information to [User Profile](#user-profile) so that profile image can be updated

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
