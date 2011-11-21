<?php
require('HtmlTag.class.php');

$content = HtmlTag::createElement();

$content->addElement('span')->setText('Vous vous êtes déjà demandés :');

$ul = $content->addElement('ul');
$ul->addElement('li')->setText('A quel épisode je suis arrivé(e) dans cette série ?');
$ul->addElement('li')->setText('Ils en sont à combien de saisons ?');
$ul->addElement('li')->setText('IL SORT QUAND LE PROCHAIN ???'); // Deux semaines que j\'attend >_<
$content->addElement('br');

$content->addElement('p')->setText('Si vous vous retrouvez dans ces répliques alors next-episode est fait pour vous.');
$content->addElement('p')->setText('Un moteur de recherche simple, qui va vous permettre :');
$ul = $content->addElement('ul');
$ul->addElement('li')->setText('d\'approfondir vos séries');
$ul->addElement('li')->setText('d\'en découvrir de nouvelles');
$ul->addElement('li')->setText('d\'obtenir les notes des épisodes pour revoir les meilleurs');
$ul->addElement('li')->setText('de vous informer des prochaines sorties');

$form = $content->addElement('form')->set('onvalid','javascript:return false;');
$form->addElement('input')->set('type','button')->addClass('button')->set('value','Rechercher une série')->removeClass('button');
$form->addElement('input')->set('type','button')->addClass('button')->set('value','S\'inscire');

echo($content);


$content = HtmlTag::createElement('a');
?>