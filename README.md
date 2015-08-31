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
6. You will need to set up google 0Auth 2.0 and google calendar configuration. Go to console.developers.google.com and create an account and set up a new project. Go to Api and Auth and create a new Client ID for a web application. You will need to set the redirect uri in the Credentials section.This process you will generate a Client Id(``id``) and a client secret(``secret``), you will have to insert these values in there respective places within the ``client`` node in ``core`` found in *Lib/Core/Config.json*. Here you must also insert the redirect uri you entered in the google developers console, under the ``redirect`` node. Next ensure ``scopes`` is set to the folowing ``"email, profile, https://www.googleapis.com/auth/calendar"``. 
7. Next create a google service account for interacting with 
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




# Dillinger

Dillinger is a cloud-enabled, mobile-ready, offline-storage, AngularJS powered HTML5 Markdown editor.

  - Type some Markdown on the left
  - See HTML in the right
  - Magic

Markdown is a lightweight markup language based on the formatting conventions that people naturally use in email.  As [John Gruber] writes on the [Markdown site] [1]:

> The overriding design goal for Markdown's
> formatting syntax is to make it as readable
> as possible. The idea is that a
> Markdown-formatted document should be
> publishable as-is, as plain text, without
> looking like it's been marked up with tags
> or formatting instructions.

This text you see here is *actually* written in Markdown! To get a feel for Markdown's syntax, type some text into the left window and watch the results in the right.

### Version
3.0.2

### Tech

Dillinger uses a number of open source projects to work properly:

* [AngularJS] - HTML enhanced for web apps!
* [Ace Editor] - awesome web-based text editor
* [Marked] - a super fast port of Markdown to JavaScript
* [Twitter Bootstrap] - great UI boilerplate for modern web apps
* [node.js] - evented I/O for the backend
* [Express] - fast node.js network app framework [@tjholowaychuk]
* [Gulp] - the streaming build system
* [keymaster.js] - awesome keyboard handler lib by [@thomasfuchs]
* [jQuery] - duh

### Installation

You need Gulp installed globally:

```sh
$ npm i -g gulp
```

```sh
$ git clone [git-repo-url] dillinger
$ cd dillinger
$ npm i -d
$ mkdir -p public/files/{md,html,pdf}
$ gulp build --prod
$ NODE_ENV=production node app
```

### Plugins

Dillinger is currently extended with the following plugins

* Dropbox
* Github
* Google Drive
* OneDrive

Readmes, how to use them in your own application can be found here:

* plugins/dropbox/README.md
* plugins/github/README.md
* plugins/googledrive/README.md
* plugins/onedrive/README.md

### Development

Want to contribute? Great!

Dillinger uses Gulp + Webpack for fast developing.
Make a change in your file and instantanously see your updates!

Open your favorite Terminal and run these commands.

First Tab:
```sh
$ node app
```

Second Tab:
```sh
$ gulp watch
```

(optional) Third:
```sh
$ karma start
```

### Todo's

 - Write Tests
 - Rethink Github Save
 - Add Code Comments
 - Add Night Mode

License
----

MIT


**Free Software, Hell Yeah!**

[john gruber]:http://daringfireball.net/
[@thomasfuchs]:http://twitter.com/thomasfuchs
[1]:http://daringfireball.net/projects/markdown/
[marked]:https://github.com/chjj/marked
[Ace Editor]:http://ace.ajax.org
[node.js]:http://nodejs.org
[Twitter Bootstrap]:http://twitter.github.com/bootstrap/
[keymaster.js]:https://github.com/madrobby/keymaster
[jQuery]:http://jquery.com
[@tjholowaychuk]:http://twitter.com/tjholowaychuk
[express]:http://expressjs.com
[AngularJS]:http://angularjs.org
[Gulp]:http://gulpjs.com
