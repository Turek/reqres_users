

Add repository to your composer.json file repositories list:
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
composer require turek/reqres_users:dev-main
```
