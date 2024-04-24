# Description
This is a test project called "Aggregator of Approves". The main idea is to provide ability for the common users to create requests of two types: "grant item" and "send message". The users with role "editor" in addition can approve or reject requests from common users.

# Brief description of the technical structure
Project has two separated parts: "Frontend" (in `/front` directory) and "Backend" (in `/app`). The frontend part has been made with Vue 3/Quasar framework and could be installed separately. The backend part implemented on Symfony 6.4.5 and has a MySQL database as a storage. The both parts communicate to each other through REST API.

# Run project
This app required Docker Compose. In oder to run project use:
`docker compose up -d`
During implementation of this command the `npm install` and all required commands for the frontend will be automatically implemented. For the backend `composer up` and all others will be automatically run, but database is better to add manually. DB dump is located in `/sql/db.sql`. 
You can connect to db with following credentials:
```
username: 'root'
password: 'qwerty'
host: 'localhost'
port: '3306'
database_name: 'db'
```
It's easier than apply all migrations, data Fixtures and commands. 

# Possible improvements.
    1. Caches. At this moment application didn't use any special caches and really could be improved with both frontend and backend caches.
    2. Data Transfer Object (DTO). During development I understand that it would be really better to create some additional layer for the data. Now we have two types of actions: "Send Message" and "ItemGrant", but what if we will have much more?
    For example, add player to the black list or remove him, invite player to the event or group, etc. 
    The main idea is to create DTO for the actions on backend and frontend views (lists and tables) will operate with the unified "actions". 
    So it will doesn't matter what type of action we show and allow to avoid unnecessary conditions in the code.
    3. Of course here is a lot of space to improve application in both frontend and backend: 
        - Show "preloader" image that will be displayed util data loading.
        - Add more filters for the all lists and tables.
        - Give ability for the users to change their data (username, password, etc.) and edit pending actions. I mean, editors should be able to edit everything.
        - Allow to give few items at once, not only one. Autocomplete form field could help with it.
        - Show all information for the specific action in popup or replace tables with "twitter" style feed. 
        It will allow to see the long text messages and improve mobile view.

# OpenApi contract
Dynamically generated list of all API endpoints:
http://138.68.109.62:8080/api/doc.json

The yaml contract located here: `/contract/api.yaml`

# Tests
Some tests could be found in `/app/tests`.

# Commands
The custom command to create new editor: 
`app:create-editor`
