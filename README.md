Drupal module that provides a block listing the users from a
third party service. For the purposes of this test, please
integrate with the https://reqres.in/ dummy API.

The block provides a paginated listing of users retrieved
from the API, basic configuration of the output is possible
when placing the block.

There are three extension points exposed to change sql query
before it is executed, hook or event for altering response.

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

Check the `ReqresUsersAlterSubscriber.php` file for example implementation of
event based alteration of returned data.

Another example is to use hooks, check `reqres_users.api.php` file for  example
hook implementation: `hook_reqres_users_data_alter`.

# Altering output of the view generated block

Similar to 'Altering the query' above, but acts on the view block.

```
function hook_views_query_alter(Drupal\views\ViewExecutable $view, Drupal\views\Plugin\views\query\QueryPluginBase $query) {
  // Check if we are altering the correct view. Replace 'your_view_id' with the actual ID of your view.
  if ($view->id() === 'reqres_users') {
    // Example: Add a condition to exclude users with the first name 'Emma'.
    $query->addWhere('your_group', 'first_name', 'Emma', '<>');
  }
}
```

# Running tests

Run the following command to execute unit tests for the module:

```bash
./vendor/bin/phpunit -c web/core ./web/modules/contrib/reqres_users/tests/src
```
