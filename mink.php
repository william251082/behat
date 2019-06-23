<?php

//use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Session;

require __DIR__ . '/vendor/autoload.php';

//$driver = new GoutteDriver();
$driver = new Selenium2Driver();

$session = new Session($driver);
$session->start();

$session->visit('http://jurassicpark.wikia.com');

//var_dump($session->getStatusCode(), $session->getCurrentUrl());
var_dump($session->getCurrentUrl());

// DOM
$page = $session->getPage();
var_dump(substr($page->getText(), 0, 75));

// NodeElement
$header = $page->find('css', '.WikiaSiteWrapper WikiTopAds');
var_dump($page->getText());

$nav = $page->find('css', '.wds-tabs');
var_dump($nav->getHtml());
//$linkEl = $nav->find('css', 'li a');
//var_dump($linkEl->getText());
$linkEl = $page->findLink('Discuss');
var_dump($linkEl->getAttribute('href'));

$page->findField('Description');
$page->findButton('Save');

$linkEl->click();

var_dump($session->getCurrentUrl());

$session->stop();


