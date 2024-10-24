<?php
function limiterTexte($texte, $limite = 100)
{
    if (strlen($texte) > $limite) {
        return substr($texte, 0, $limite) . '...';
    } else {
        return $texte;
    }
}
