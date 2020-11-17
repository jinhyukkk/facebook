<?php

require './pdos/DatabasePdo.php';
require './pdos/IndexPdo.php';
require './pdos/JWTPdo.php';
require './vendor/autoload.php';

use \Monolog\Logger as Logger;
use Monolog\Handler\StreamHandler;

date_default_timezone_set('Asia/Seoul');
ini_set('default_charset', 'utf8mb4');

//에러출력하게 하는 코드
error_reporting(E_ALL); ini_set("display_errors", 1);

//Main Server API
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    /* ****************   Complete   **************** */
    $r->addRoute('GET', '/', ['IndexController', 'index']);
    $r->addRoute('GET', '/users', ['IndexController', 'getUsers']);
    $r->addRoute('GET', '/users/{userIdx}', ['IndexController', 'getUserDetail']);
    $r->addRoute('POST', '/user', ['IndexController', 'createUser']);
    $r->addRoute('DELETE', '/user/{userIdx}', ['IndexController', 'deleteUser']);
    $r->addRoute('PATCH', '/user/{userIdx}', ['IndexController', 'updateUser']);
    $r->addRoute('GET', '/post', ['IndexController', 'getPost']);
    $r->addRoute('GET', '/post/{postIdx}', ['IndexController', 'getPostDetail']);
    $r->addRoute('POST', '/post', ['IndexController', 'createPost']);
    $r->addRoute('GET', '/searchPosts', ['IndexController', 'searchPosts']);
    $r->addRoute('PATCH', '/post', ['IndexController', 'updatePost']);
    $r->addRoute('DELETE', '/post/{postIdx}', ['IndexController', 'deletePost']);
    $r->addRoute('POST', '/post/{postIdx}/like', ['IndexController', 'patchLike']);
    $r->addRoute('GET', '/post/{postIdx}/like', ['IndexController', 'likeList']);
    $r->addRoute('GET', '/post/{postIdx}/image/{imageIdx}/like', ['IndexController', 'imageLikeList']);
    $r->addRoute('GET', '/post/{postIdx}/video/{videoIdx}/like', ['IndexController', 'videoLikeList']);
    $r->addRoute('GET', '/post/{postIdx}/comment/{commentIdx}/like', ['IndexController', 'commentLikeList']);
    $r->addRoute('POST', '/comment', ['IndexController', 'postComment']);
    $r->addRoute('PATCH', '/comment', ['IndexController', 'updateComment']);
    $r->addRoute('GET', '/post/{postIdx}/comment', ['IndexController', 'getComment']);
    $r->addRoute('DELETE', '/post/{postIdx}/comment/{commentIdx}', ['IndexController', 'deleteComment']);
    $r->addRoute('POST', '/friend', ['IndexController', 'applyFriend']);
    $r->addRoute('PATCH', '/friend', ['IndexController', 'acceptApply']);
    $r->addRoute('DELETE', '/friend', ['IndexController', 'deleteFriend']);
    $r->addRoute('GET', '/friend', ['IndexController', 'getFriend']);
    $r->addRoute('GET', '/waitingFriend', ['IndexController', 'waitingFriend']);
    $r->addRoute('GET', '/post/{postIdx}/image/{imageIdx}', ['IndexController', 'getImageDetail']);
    $r->addRoute('GET', '/post/{postIdx}/video/{videoIdx}', ['IndexController', 'getVideoDetail']);
    $r->addRoute('POST', '/post/{postIdx}/image/{imageIdx}/like', ['IndexController', 'patchImageLike']);
    $r->addRoute('POST', '/post/{postIdx}/video/{videoIdx}/like', ['IndexController', 'patchVideoLike']);
    $r->addRoute('POST', '/post/{postIdx}/comment/{commentIdx}/like', ['IndexController', 'patchCommentLike']);
    $r->addRoute('POST', '/page/{pageIdx}/like', ['IndexController', 'patchPageLike']);
    $r->addRoute('POST', '/page', ['IndexController', 'createPage']);
    $r->addRoute('GET', '/page/{pageIdx}/like', ['IndexController', 'pageLikeList']);
    $r->addRoute('POST', '/blacklist', ['IndexController', 'blackList']);
    $r->addRoute('DELETE', '/blacklist', ['IndexController', 'releaseBlack']);
    $r->addRoute('POST', '/favorites', ['IndexController', 'favorites']);
    $r->addRoute('POST', '/storyImage', ['IndexController', 'createStoryImage']);

    /* ******************   JWT   ****************** */
    $r->addRoute('POST', '/jwt', ['JWTController', 'createJwt']);   // JWT 생성: 로그인
    $r->addRoute('GET', '/jwt', ['JWTController', 'validateJwt']);  // JWT 유효성 검사

    /* *****************   Test   ***************** */




//    $r->addRoute('GET', '/users', 'get_all_users_handler');
//    // {id} must be a number (\d+)
//    $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
//    // The /{title} suffix is optional
//    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

// 로거 채널 생성
$accessLogs = new Logger('ACCESS_LOGS');
$errorLogs = new Logger('ERROR_LOGS');
// log/your.log 파일에 로그 생성. 로그 레벨은 Info
$accessLogs->pushHandler(new StreamHandler('logs/access.log', Logger::INFO));
$errorLogs->pushHandler(new StreamHandler('logs/errors.log', Logger::ERROR));
// add records to the log
//$log->addInfo('Info log');
// Debug 는 Info 레벨보다 낮으므로 아래 로그는 출력되지 않음
//$log->addDebug('Debug log');
//$log->addError('Error log');

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo "404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo "405 Method Not Allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        switch ($routeInfo[1][0]) {
            case 'IndexController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/IndexController.php';
                break;
            case 'JWTController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/JWTController.php';
                break;
            /*case 'EventController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/EventController.php';
                break;
            case 'ProductController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/ProductController.php';
                break;
            case 'SearchController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/SearchController.php';
                break;
            case 'ReviewController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/ReviewController.php';
                break;
            case 'ElementController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/ElementController.php';
                break;
            case 'AskFAQController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/AskFAQController.php';
                break;*/
        }

        break;
}
