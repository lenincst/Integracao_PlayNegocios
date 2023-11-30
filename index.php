<?php

include '/xampp/htdocs/Play_Negocios/classes.php';

$emailteste = new PlayNegocios();

// Obtém o e-mail da URL usando o método PegaEmailUrl()
$email = $emailteste->PegaEmailUrl($_GET['email']);

// Chama o método buscarEmail() com o e-mail obtido
$emailteste->buscarEmail($email);
