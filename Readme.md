Cette application gère une salle de sport

## Intégration de docker

    # Prérequis :
        docker

    On utilise un webmail avec l'image schickling/mailcatcher
        
    docker compose up -d
   
## Template et Bootstrap
    # Installation
        composer require bootstrap@next
        composer require encore -- Install webpack encore to manage js and css files

    # Version 1  
    Utilisation du template de startBootstrap : sd-admin-2 
    Disponible ici https://github.com/StartBootstrap/startbootstrap-sb-admin-2.git

    # Version 2
    Utilisation du template adminLte : 
    Disponible ici https://github.com/ColorlibHQ/AdminLTE/releases


# Démarrer le projet

    composer install -- to install all dependencies
    docker-compose up -d ou docker compose up -d
    symfony console serve -d
    npm run build  --- to build the js and css scripts
    
    
# Déploiement
    
    heroku login 
    heroku create app_name
    heroku config:set APP_ENV=prod -- change the environnement
    heroku config:set APP_SECRET=$(php -r 'echo bin2hex(random_bytes(16));')
    heroku addons:create heroku-postgresql:hobby-dev -- install postgresql addon
    heroku buildpacks:add --index 1 heroku/nodejs  
    heroku config:set USE_NPM_INSTALL=true    
    
    #composer.json 
        "compile": [
            "php bin/console doctrine:migrations:migrate"
        ]
        
    #package.json
    "heroku-postbuild": "encore production --progress"
    "engines": {
        "npm": "6.x"
    }
    
    Add a Profile file
        release: php bin/console cache:clear && php bin/console cache:warmup
        web: heroku-php-apache2 public/
        
    # install apache pack to manage internal links

# Images

## Accueil

![Accueil](https://i.postimg.cc/sX6YBsbR/accueil.png)

## Ajout d'un client

![Ajout client](https://i.postimg.cc/vZr5rWCh/reg.png)

## Administration du site

![Administration](https://i.postimg.cc/TwwmvB1N/settings.png)