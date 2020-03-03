<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 06/06/2017
 * Time: 21:53
 */

use App\Controller\DemoController;
use App\Middleware\MediaTypeJsonMiddleware;
use PocFramework\Middleware\LogFilterTruncateMiddleware;

$app->get('/gank/list_benefits', DemoController::class . ':listBenefits')->add(LogFilterTruncateMiddleware::class);

$app->add(MediaTypeJsonMiddleware::class);