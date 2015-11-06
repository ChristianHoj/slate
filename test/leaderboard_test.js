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
        "id": {
          "id": "id",
          "type": "string"
        },
        "type": {
          "id": "type",
          "type": "string"
        },
        "attributes": {
          "id": "attributes",
          "type": "object",
          "properties": {
            "thisWeek": {
              "id": "thisWeek",
              "type": "object",
              "properties": {
                "period": {
                  "id": "period",
                  "type": "object",
                  "properties": {
                    "from": {
                      "id": "from",
                      "type": "string"
                    },
                    "until": {
                      "id": "until",
                      "type": "string"
                    }
                  },
                  "additionalProperties": false
                },
                "positions": {
                  "id": "positions",
                  "type": "array",
                  "items": {
                    "id": "8",
                    "type": "object",
                    "properties": {
                      "position": {
                        "id": "position",
                        "type": "string"
                      },
                      "name": {
                        "id": "name",
                        "type": "string"
                      },
                      "newMembers": {
                        "id": "newMembers",
                        "type": "string"
                      },
                      "image": {
                        "id": "image",
                        "type": "string"
                      }
                    },
                    "additionalProperties": false
                  },
                  "additionalItems": false
                }
              },
              "additionalProperties": false
            },
            "previousWeek": {
              "id": "previousWeek",
              "type": "object",
              "properties": {
                "period": {
                  "id": "period",
                  "type": "object",
                  "properties": {
                    "from": {
                      "id": "from",
                      "type": "string"
                    },
                    "until": {
                      "id": "until",
                      "type": "string"
                    }
                  },
                  "additionalProperties": false
                },
                "positions": {
                  "id": "positions",
                  "type": "array",
                  "items": {
                    "id": "9",
                    "type": "object",
                    "properties": {
                      "position": {
                        "id": "position",
                        "type": "string"
                      },
                      "name": {
                        "id": "name",
                        "type": "string"
                      },
                      "newMembers": {
                        "id": "newMembers",
                        "type": "string"
                      },
                      "image": {
                        "id": "image",
                        "type": "string"
                      }
                    },
                    "additionalProperties": false
                  },
                  "additionalItems": false
                }
              },
              "additionalProperties": false
            },
            "last30Days": {
              "id": "last30Days",
              "type": "object",
              "properties": {
                "period": {
                  "id": "period",
                  "type": "object",
                  "properties": {
                    "from": {
                      "id": "from",
                      "type": "string"
                    },
                    "until": {
                      "id": "until",
                      "type": "string"
                    }
                  },
                  "additionalProperties": false
                },
                "positions": {
                  "id": "positions",
                  "type": "array",
                  "items": {
                    "id": "8",
                    "type": "object",
                    "properties": {
                      "position": {
                        "id": "position",
                        "type": "string"
                      },
                      "name": {
                        "id": "name",
                        "type": "string"
                      },
                      "newMembers": {
                        "id": "newMembers",
                        "type": "string"
                      },
                      "image": {
                        "id": "image",
                        "type": "string"
                      }
                    },
                    "additionalProperties": false
                  },
                  "additionalItems": false
                }
              },
              "additionalProperties": false
            }
          },
          "additionalProperties": false
        }
      },
      "additionalProperties": false,
      "required": [
        "id",
        "type",
        "attributes"
      ]
    }
  },
  "additionalProperties": false,
  "required": [
    "data"
  ]
}
;

  var response = JSON.parse(responseBody);

  tests["Valid response format"] = tv4.validate(response, schema);
  if (tv4.error) {
    var message = tv4.error.message + (tv4.error.params? ". Parameter: " + tv4.error.params.key : "") + ". Path: " + tv4.error.dataPath;
    tests[message] = false;
  }
}
