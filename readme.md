Cette application gère une salle de sport

## Intégration de docker

    # Prérequis :
        docker
        docker-compose

    On utilise un webmail avec l'image schickling/mailcatcher
        
    docker-composer up -d
   
## Template et Bootstrap
    # Installation
        npm require bootstrap@next
        composer require encore -- Install webpack encore to manage js and css files
        
    Utilisation du template de startBootstrap : sd-admin-2 
    Disponible ici https://github.com/StartBootstrap/startbootstrap-sb-admin-2.git


# Démarrer le projet

    composer install -- to install all dependencies
    docker-composer up -d
    symfony console serve -d
    npm run build  --- to build the js and css scripts
    
    
# Déploiement
    
    heroku login 
    heroku create app_name
    heroku config:set APP_ENV=prod -- change the environnement
    heroku config:set APP_SECRET=$(php -r 'echo bin2hex(random_bytes(16));')
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
