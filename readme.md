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
    
    
