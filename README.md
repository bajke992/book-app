Book Management Application
===========================
--------------
Installation:
------------
To install this application make sure [**composer**] [1] is installed on your PC,
next run the following command in the Windows command prompt or Unix/OSX terminal:
`composer create-project bajke/book-app:1.0.*@dev`

The application uses composer post operation script hooks to create the database, update its schema and create the assets
and finally test the environment to see if anything is missing.
After the installation all you need to do is run the following commands:
`
chmod 777 app/cache
chmod 777 app/logs
`
Starting the server:
--------------------
To start the built-in symfony server run the following command from witin the project directory:
`php app/console server:run`
this will run the application in the development environment unless the SYMFONY_ENV variable is not set to 'prod',
to run it in the production environment either set the SYMFONY_ENV variable to prod or use the following command:
`php app/console server:run --env prod`

The API:
--------
| Description | Method | URL |
| - | - | - |
| Returns all books related to the authenticated user | GET | /api/book |
| Creates a new book | POST | /api/book |
| Returns a single book that belongs to the user | GET | /api/book/{id} |
| Updates a book | PUT/PATCH | /api/book/{id} |
| Deletes a book | DELETE | /api/book/{id} |

####**Mandatory URL Query parameters**
/api/*?access_token=[ACCESS_TOKEN]
To get an access token you must first be registered to the app and create a API Client in your profile
and follow the steps outlined there.

The API accepts JSON format body for POST requests.


[1]: https://getcomposer.org/download/
