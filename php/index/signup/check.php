<?php
require_once('../../class/UserSignUp.php');

UserSignUp::getInstance()->checkIfUserExists();
