<?php

use \DateTime;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

require __DIR__.'/vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__.'/templates');


$twig = new Environment($loader, [
    'debug' => true,
    'strict_variables' => true,
]);

$twig->addExtension(new DebugExtension());

$formData = [
    'email' => '',
    'subject' => '',
    'message' => '',
];

$errors = [];

if ($_POST) {
    foreach($formData as $key => $value) {
        if(isset($_POST[$key])) {
            $formData[$key]=$_POST[$key];
        }
    }


    $minLengthSubject = 3;
    $maxLengthSubject = 190;

    if (empty($_POST['email'])) {
        $errors['email'] = 'Merci de ne pas laisser ce champ vide';
    } elseif (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false)  {
        $errors['email'] = 'Merci de renseigner un email valide';
    } elseif (strlen($_POST['email']) > 190) {
        $errors['email'] = "Merci de renseigner un email dont la longueur est comprise entre {$minLengthSubject} et {$maxLengthSubject} inclus";
    }

    if (empty($_POST['subject'])) {
        $errors['subject'] = 'Merci de ne pas laisser ce champ vide';
    } elseif (strlen($_POST['subject']) < 3 || strlen($_POST['subject']) > 190) {
        $errors['subject'] = "Merci de renseigner un sujet dont la longueur est comprise entre {$minLengthSubject} et {$maxLengthSubject} inclus";
    }

    $minLengthMessage = 3;
    $maxLengthMessage = 1000;

    if (empty($_POST['message'])) {
        $errors['message'] = 'Merci de ne pas laisser ce champ vide';
    } elseif (strlen($_POST['message']) < 3 || strlen($_POST['message']) > 1000) {
        $errors['message'] = "Merci de renseigner un message dont la longueur est comprise entre {$minLengthMessage} et {$maxLengthMessage} inclus";
    }
}

echo $twig->render('contact.html.twig', [
    'errors' => $errors,
    'formData' => $formData,
]);