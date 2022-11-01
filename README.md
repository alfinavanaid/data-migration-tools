# Data Migration Tools

This is the package to migrate data from the on database to the other using symfony 5.4 based on the master table column value. Sometimes we need to test some issue happen on the production but it's not possible to do because will impacted the real data on the real user side.

## Requirements
PHP 7.3.33

## Configuration

### Schema

#### 1. Data Source

We use json file to gather some column and table information of a schema. This file contain all table name and column name as an array. The sample of file can be refered here.

#### 2. Custom Relations Field

We have relationship between one table to the other on the schema, but there will relationship that defined only by the column name without any touch on DDL level. To solve this we need the custom relations definition, the example can be seen here.

On the custom_relations_field.json file, we have main key "table_not_found" and "custom_relations_field", the following will explains: 

##### a. table_not_found

Before we add some table here, please do initial analyze first to genereate some list of table not found on the `table_not_found.json` file. If some table name was listed there, we can ignore table or aliases here.

	"table_not_found": {
		"order": {
			"type": "alias",
			"table_name" : "customer_order"
		},
		"shipper_area": {
			"type": "ignored",
			"table_name": "shipper_area",
			"column_name": "shipper_area_id"
		}
	}

There are two type of the how the analyze system understand the table not found, as `alias` or as `ignored`. Based on the example above, `order` table listed as not found, the actual name is `customer_order`, so we'll writed as :

	"order": {
		"type": "alias",
		"table_name" : "customer_order"
	}

Then, how if we have unused table or it was created by accidentaly but already on production ? We'll ignore these table, and write it as :

	"shipper_area": {
		"type": "ignored",
		"table_name": "shipper_area",
		"column_name": "shipper_area_id" // just add this in case the primary key is different with the table name
	}

If we want to ignore the table relationship only on the specific table ? Just add the key `table_name_called_from` after the `ignored` value. Like this

	"shipper_area": {
		"type": "ignored",
		"table_name": "shipper_area",
		"column_name": "shipper_area_id",
		"table_name_called_from": "shipping_address"
	}


##### b. custom_relations_field

The case above sometimes happen when the column name is far different between the foreign key and primary key, if this was happen then our analyze system will define the relations as table not found. But, how if there was `created_country_new_id` column and `created_country_new` table and we want to referenced it to the countries, not `created_country_new` ? The following example will explain :

	"custom_relations_field": {
		"user": {
			"local_column_name": "created_country_new_id", // the foreign column name defined on current system
			"referal_column_name": "countries_id", // the referal column name
			"referal_table_name": "countries" // the referal table name
		}
	}
	
#### 3. Table Not Found

This list will be generated after we run the analysis. We can see it on the `table_not_found.json` file. From the result, we can mark it as ignore or alias on the `custom_relations_field`. The `table_not_found.json` file will look like : 

	[
		{
        "table": "unique_user",
        "foreign_column": "unique_user_id",
        "foreign_table": "whatsapp_analytics"
    }
	]

#### 4. Data Source Object

The analysis also generate result after analysis on the table, contains several key that important to the next data extraction process. The object of data source will be look like this :


    "theme_template": {
        "table_name": "theme_template", // name of the table
        "column": [ // list of column inside this table
            "theme_template_id",
            "theme_id",
            "name",
            "type",
            "created_at",
            "updated_at",
        ],
        "relations_child": [
            "theme_template.theme_template_id = theme_template_body.theme_template_id",
            "theme_template.theme_template_id = theme_template_body_bk.theme_template_id"
        ], // list of relations one to many / one to one
        "relations_parent": [
            "theme_template.theme_id = theme.theme_id"
        ], // list of relations many to one / one to one
        "extract_layer": 5,
        "primary_key": "theme_template_id"
    },


### Extract

Those schema are all **required** before we start extract the data, make sure all schema were correct to avoid wrong data gathered from the database.
