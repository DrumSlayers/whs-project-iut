@echo off
:: Script pour d‚marrer tout le projet WHS - Pierre S. <drumslayer@drumslayer.ovh>
:: Encodage CP 850

:: ------ CONFIG ------
:: R‚pertoire de MySQL (racine) sans le /bin
set MySQl_dir=""C:\Users\dsi20\Documents SSD\OneDrive Universit‚\OneDrive - Universit‚ de Tours\IUT\Cours\$-COURS DISTANCIELS\M4207 Application d‚di‚e aux RT\mysql-5.6.47-win32""

:: User MySQL
set MySQL_user="whs_user"

:: Mot de passe MySQL
set MySQL_password="whs_password"

:: R‚pertoire de votre projet WHS (Racine)
set WHS_dir=""D:\BibliothŠques Windows\Documents\whs-basic\whs-project-iut\""

:: ------ FIN CONFIG ------

start "MySQL Server" /D "%MySQL_dir%\bin" mysqld
sleep 2
start "MySQL Command Line Interface" /D "%MySQL_dir%\bin" cmd /K "mysql -u %MySQL_user% -p%MySQL_password%"
:: Changez "whs_user" par votre user et "whs_password" par votre mdp

start "Slim Web Server 1 (8080)" /D "%WHS_dir%" php -S localhost:8080 -t public
start "Slim Web Server 2 (8081)" /D "%WHS_dir%" php -S localhost:8081 -t public

exit 0