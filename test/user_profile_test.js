/* Schema generated at http://jsonschema.net/index.html */

tests["Status code is 200"] = responseCode.code === 200;

if (responseCode.code === 200) {
  var schema = {
    "$schema": "http://json-schema.org/draft-04/schema#",
    "id": "/",
    "type": "object",
    "properties": {
      "data": {
        "id": "data",
        "type": "object",
        "properties": {
          "type": {
            "id": "type",
            "type": "string"
          },
          "id": {
            "id": "id",
            "type": "string"
          },
          "attributes": {
            "id": "attributes",
            "type": "object",
            "properties": {
              "accountType": {
                "id": "accountType",
                "type": "string"
              },
              "address": {
                "id": "address",
                "type": "string"
              },
              "city": {
                "id": "city",
                "type": "string"
              },
              "company_name": {
                "id": "company_name",
                "type": "string"
              },
              "country": {
                "id": "country",
                "type": "string"
              },
              "first_name": {
                "id": "first_name",
                "type": "string"
              },
              "imageUrl": {
                "id": "imageUrl",
                "type": "string"
              },
              "last_name": {
                "id": "last_name",
                "type": "string"
              },
              "partner_email": {
                "id": "partner_email",
                "type": "string"
              },
              "partner_name": {
                "id": "partner_name",
                "type": "string"
              },
              "partner_skype_id": {
                "id": "partner_skype_id",
                "type": "string"
              },
              "paypal_email": {
                "id": "paypal_email",
                "type": "string"
              },
              "phone": {
                "id": "phone",
                "type": "string"
              },
              "quickpay_api_key": {
                "id": "quickpay_api_key",
                "type": "string"
              },
              "quickpay_merchant_id": {
                "id": "quickpay_merchant_id",
                "type": "string"
              },
              "recruit_firstname": {
                "id": "recruit_firstname",
                "type": "string"
              },
              "recruit_lastname": {
                "id": "recruit_lastname",
                "type": "string"
              },
              "reference_code": {
                "id": "reference_code",
                "type": "string"
              },
              "shipping_cost": {
                "id": "shipping_cost",
                "type": "string"
              },
              "skype_id": {
                "id": "skype_id",
                "type": "string"
              },
              "vs_link": {
                "id": "vs_link",
                "type": "string"
              },
              "vs_name": {
                "id": "vs_name",
                "type": "string"
              },
              "zipcode": {
                "id": "zipcode",
                "type": "string"
              }
            },
            "additionalProperties": false
          }
        },
        "additionalProperties": false,
        "required": [
          "type",
          "id",
          "attributes"
        ]
      }
    },
    "additionalProperties": false,
    "required": [
      "data"
    ]
  };

  var response = JSON.parse(responseBody);

  tests["Valid response format"] = tv4.validate(response, schema);
  if (tv4.error) {
    var message = tv4.error.message + (tv4.error.params? ". Parameter: " + tv4.error.params.key : "") + ". Path: " + tv4.error.dataPath;
    tests[message] = false;
  }
}
