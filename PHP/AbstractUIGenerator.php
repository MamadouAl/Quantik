<?php

class AbstractUIGenerator
{
    /**
     * Methode permettant de générer le début d'un fichier HTML
     * @param string $title
     * @return string
     */
    public static function getDebutHTML(string $title): string
    {
        return '<!DOCTYPE html>
        <html lang=\"fr\">
        <head>
            <meta charset=\"UTF-8\">
            <title>'.$title.'</title>
            <link rel=\"stylesheet\" href=\"style.css\">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
        </head>
        <body>';
    }

    /**
     * Methode permettant de générer la fin d'un fichier HTML
     * @return string
     */
    public static function getFinHTML(): string
    {
        return "</body>
        </html>";
    }

    /**
     * Methode permettant générer une page d'erreur
     * @param string $message
     * @param string $urlLien
     * @return string
     */
    public static function getPageErreur(string $message, string $urlLien ) : string {
        return self::getDebutHTML("Page d'erreur") .
            "<div>
                <h1>Erreur</h1>
                <p>$message</p>
                <a href=\"$urlLien\">Retour</a>
            </div>" .
            self::getFinHTML();
    }

}