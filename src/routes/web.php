<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 06/06/2017
 * Time: 21:52
 */

use App\Controller\UserController;
use App\Middleware\CheckCodeMiddleware;
use App\Middleware\ExceptionHandler;
use App\Middleware\MediaTypeJsonMiddleware;
use App\Controller\RegionalController;
use App\Controller\UserContributeController;
use App\Controller\ArticleController;
use App\Controller\FoodController;

$app->group('/user', function (){
    $this->post('/login', UserController::class . ':login')->add(CheckCodeMiddleware::class);           //登录
    $this->post('/register', UserController::class . ':register')->add(CheckCodeMiddleware::class);     //注册
    $this->post('/logout', UserController::class . ':logout');                                          //退出
    $this->get('/send_code', UserController::class . ':sendVerificationCode');                          //发送验证码
});

$app->group('/article', function () {
    $this->get('/list_contributes', ArticleController::class . ':listUserContributes');                   //获取需要审核的文章列表
    $this->get('/get_article', ArticleController::class . ':getUserArticle');                   //获取用户的文章列表
    $this->get('/list_user_articles', ArticleController::class . ':listUserArticles');                   //获取所有通过审核的文章列表
    $this->post('/pass_article', ArticleController::class . ':passArticle');                   //审核通过
    $this->post('/refuse_article', ArticleController::class . ':refuseArticle');                   //审核不通过
});

$app->get('get_nav', UserController::class . ':getNav');                    //Nav列表

$app->group('/region', function () {
//    $this->post('/add_region', RegionalController::class . ':addRegion');
    $this->get('/init_info', RegionalController::class . ':initInfo');
    $this->get('/list_simples', RegionalController::class . ':listSimple');
    $this->get('/get_detail', RegionalController::class . ':getRegionSimple');
    $this->get('/list_hot', RegionalController::class . ':listHot');
    $this->get('/get_search', RegionalController::class . ':searchRegion');
    $this->get('/list_region_names', RegionalController::class . ':listRegionNames');
});

$app->group('/food', function () {
    $this->get('/list_simples', FoodController::class . ':listFoods');
    $this->post('/add_food', FoodController::class . ':addFood');
});

$app->post('/add_article', UserContributeController::class . ':addArticle');                      //我添加文章
$app->post('/my_article', UserContributeController::class . ':myArticle');                       //我的文章

$app->add(ExceptionHandler::class)->add(MediaTypeJsonMiddleware::class);