# API Documentation

## Introduction

This document provides information about the routes available in the API.

## Base URL

```arduino
http::auth_geoquizz/api/v1
```

## Routes

### Register
- URL : /register
- Method : POST
- RequestBody :
```json
{
    "mail": "admin@admin.com",
    "username": "admin",
    "password": "admin",
    "confirm_password": "admin"
}
```
- ResponseBody
```json
{
    "access_token": "abcdefg",
    "refresh_token": "abcdefg",
    "profileId": "1"
}
```



### Login
- URL : /login
- Method : POST
- RequestBody :
```json
{
    "mail": "admin@admin.com",
    "password": "admin"
}
```
- ResponseBody
```json
{
    "access_token": "abcdefg",
    "refresh_token": "abcdefg",
    "profileId": "1"
}
```

### Token
- URL : /token
- Method : POST
- RequestBody :
```json
{
    "refresh_token": "abcdefg"
}
```
#### ResponseBody
```json
{
    "access_token": "abcdefg",
    "refresh_token": "abcdefg"
}
```

## Architecture

### Actions

Les actions ont pour unique but de recevoir les données et d'en renvoyer. On ne traite aucunement les données à l'intérieur.
Pour vérifier si les champs demandés sont présents dans la requête, on utilise un service spécial.

### Services

Nous utilisons 3 services :
- AuthenticationService | Il permet de gérer les connexions/inscriptions. Vérifier les mots de passe, si le compte existe etc...
- TokenManagementService | Il s'occupe de la génération des tokens et de leur vérification.
- RequestBodyValidatorService | Il check si les champs demandés sont présents dans la requête.

### Models

Nous avons un seul modèle : Account. Il contient toutes les informations nécessaires à la connexion et à la liaison avec l'API qui contient la gestion des profils.

### DTO

Nous avons le credentialDTO qui s'occupe de récupérer les informations de connexion/inscription afin de les traiter plus facilement.