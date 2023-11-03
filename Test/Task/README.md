# TestTask module

- Add a custom customer attribute “Hobby“ with possible options: “Yoga“, “Traveling“, “Hiking“. The attribute is not
  required.
- Add a possibility to fetch / edit the attribute using GraphQL.
- Admin must be able to edit the attribute in admin panel.
- Add a link in the customer account menu.
- The link must lead on the page “Edit Hobby“. There must be a form with one field “Hobby“ and submit button.
- “Hobby“ must be displayed in the top right corner in the format “Hobby: %s“ and must be correspond to the current
  customer hobby. Place it right away after the welcome message. NB! Notice that it must work correctly with all enabled
  Magento caches

## Installation

The Test_Task is dependent on the following modules:

- `Magento_GraphQl`
- `Magento_Customer`

### Information

1) - Add a custom customer attribute “Hobby“ with possible options: “Yoga“, “Traveling“, “Hiking“.

Data patch is used to add new eav attribute. Options are stored in a separate attribute source model.

2) - Add a possibility to fetch / edit the attribute using GraphQL

Attribute can be updated using updateCustomerV2 GraphQL mutation:

```
mutation {
  updateCustomerV2(
    input: {
      firstname: "John"
      hobby:100
    }
  ) {
    customer {
      firstname
      hobby
    }
  }
}
```

Response:

```
{
    "data": {
        "updateCustomerV2": {
            "customer": {
                "firstname": "John",
                "hobby": 100
            }
        }
    }
}
```

Additional validation was added to check submitted attribute value:

```
{
    "errors": [
        {
            "message": "\"123\" is not a valid hobby value.",
            "locations": [
                {
                    "line": 2,
                    "column": 3
                }
            ],
            "path": [
                "updateCustomerV2"
            ],
            "extensions": {
                "category": "graphql-input"
            }
        }
    ],
    "data": {
        "updateCustomerV2": null
    }
}
```

3) - Admin must be able to edit the attribute in admin panel.

Since new attribute is not an "extension" attribute, it can be edited via admin panel (is allowed in admin form)

4) - Add a link in the customer account menu.

Added via "customer_account.xml"

5) - The link must lead on the page “Edit Hobby“. There must be a form with one field “Hobby“ and submit button.

New controllers, layout and templates were added. Form is available for registered users only.
Additional validation was added to check submitted attribute value (only values from attribute options are allowed).

6) - “Hobby“ must be displayed in the top right corner in the format “Hobby: %s“ and must be correspond to the current
   customer hobby. Place it right away after the welcome message.

Default template "html/header.phtml" was replaced via layout. Attribute value is taken from the 'customer' data section
the same way as the welcome message.
Attribute value and label are added to the 'customer' data section via an "afterPlugin".

7) NB! Notice that it must work correctly with all enabled Magento caches.

Data is fetched via standard 'customer' section that works with full page cache enabled. This section is 
invalidated when customer submits new form (etc/frontend/routes.xml)
