# Incubate
Incubate tracks Blue Acorn Incubator progress. 

### Installation
1. First, this repository has a submodule with the php google client library. So you will first need to clone with the following command. This should ensure the submodule will be cloned correctly.
``` 
$ git clone --recursive https://github.com/MikeTayC/apprentice.dev.git
```

2. To install this site, first create an empty database. 
3. Go to *Lib/Core/Config.json* set your desired base url at the ``baseUrl`` node within core. 
3. Also in *Lib/Core/Config.json*, set your host(``host``), database name(``name``), username(``user``), and password(``pass``) information the database configuration information. This will be found in the ``database`` node within ``config/modules/core``]. Type **MUST** be set to ``mysql``. Defualt informtion is set to:
```
{"config": {
    "modules": {
        "core": {
            "pool": "Lib",
            "dir": "Core",
            "baseUrl": {
                "url": "http:\/\/apprentice.dev\/"
            },
            "database": {
                "type": "mysql",
                "host": "localhost",
                "name": "incubate",
                "user": "root",
                "pass": "root"
            }
        }
    }
}
```    
5. Go to *App/Incubate/Config.json* and find the ``admin`` node found within ``config/modules/incubate`` node. Here you must set the **EMAIL** of any administators you would like to have. Email **MUST** be set a Blue Acorn email address. In this node, each email address should be numerically indexed starting at zero. Example configruation:
```
{
  "config": {
    "modules": {
      "incubate": {
        "pool": "App",
        "dir": "Incubate",
        "admin": {
            "0": "thomas@blueacorn.com",
            "1": "greg@blueacorn.com"
        }
      }
    }
  }
}
```
6. You will need to set up google 0Auth 2.0 and google calendar configuration. Go to console.developers.google.com and
create an account and set up a new project. Go to Api and Auth and create a new Client ID for a web application.
You will need to set the redirect uri in the Credentials section.This process you will generate a Client Id(``id``)
and a client secret(``secret``), you will have to insert these values in there respective places within the ``client``
node in ``core`` found in *Lib/Core/Config.json*. Here you must also insert the redirect uri you entered in the google
developers console, under the ``redirect`` node. Next ensure ``scopes`` is set to the folowing ``"email, profile,
https://www.googleapis.com/auth/calendar"``.
7. Next create a google service account for scheduling lessons on google calendar. To do this first make a google calendar. In your
calendar configuration. Under id place the id of the calendar you made. You must then make a service account with
google developer console. Under name place name of the google service account created. You must register this name under
the google calendar under the share settings. Then enable this account to make changes and manage sharing.
Finally generate a .p12 key in your google calendar service account. Place the file path to your new .p12 key
under key_location.
```
"client": {
    "id": "433657982361-lev74410eid7ejpnbu30dgi3crl0m3c1.apps.googleusercontent.com",
    "secret": "iehdSyaJgoH5uwgsjwPYO9ro",
    "redirect": "http:\/\/apprentice.dev\/incubate\/login\/index",
    "scopes": "email, profile, https:\/\/www.googleapis.com\/auth\/calendar"
},
"calendar": {
    "id": "blueacorn.com_j2d2fnauptd0u7mgnrf0u5e0ss@group.calendar.google.com",
    "name": "433657982361-40ctf1na0vahl950epgi1nffesb020kp@developer.gserviceaccount.com",
    "key_location":"\/home\/mike\/sites\/apprentice.dev\/Lib\/Core\/Model\/apprentice-9faa49d689bf.p12"
},
```

