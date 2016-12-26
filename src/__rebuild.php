#!/usr/bin/env php
<?php

use hiqdev\composer\config\Builder;

require_once dirname(dirname(__DIR__)) . '/autoload.php';

Builder::rebuild(__DIR__);
