# Installing module

Add repository to your `composer.json` file repositories list:
```
    "repositories": [
        ...
        {
            "type": "vcs",
            "url":  "https://github.com/turek/reqres_users.git"
        }
    ],
```

Then require module as follows:

```
$ composer require turek/reqres_users:dev-main
```

Enable module in Drupal:
```
$ drush en -y reqres_users
```

# Importing users

Check if migration appears properly:

```
$ drush migrate:status
```

Execute migration:
```
$ drush migrate:import reqres_users
```

Possibly add above to cronjob.

# Using block

Just place a block into content area on some page, enter configuration values
as preferred and visit that page to see block in action.

# Altering the query

You can alter the query by implementing below hook in your module.

```
function hook_query_reqres_users_query_alter(\Drupal\Core\Database\Query\AlterableInterface $query) {
  // Skip Emma from the list.
  $query->condition('first_name', 'Emma', '!=');
}
```

# Altering output of the block

