<?php
require 'function.php';

const JWT_SECRET_KEY = "TEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEY";

$res = (Object)Array();
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));
try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {
        case "index":
            echo "API Server";
            break;
        case "ACCESS_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/access.log");
            break;
        case "ERROR_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/errors.log");
            break;
        /*
         * API No. 1
         * API Name : 유저목록전체조회 & 검색 API
         * 마지막 수정 날짜 : 20.10.19
         */
        case "getUsers":
            http_response_code(200);
            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if (!$_GET) {
                $res->result = allUsers();
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "유저목록 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $keyword = $_GET['keyword'];

            if (!isValidKeyword($keyword)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 유저입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = searchUsers($keyword);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "유저 검색 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No. 2
        * API Name : 내 프로필 조회 API
        * 마지막 수정 날짜 : 20.10.21
        */
        case "getUserDetail":
            http_response_code(200);
            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            //토큰의 유저인덱스 == 경로변수의 유저인덱스
            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY) -> userIdx;

//            if ($userIdxToken != $vars['userIdx']){
//                $res->isSuccess = false;
//                $res->code = 200;
//                $res->message = "권한이 없는 유저입니다.";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }

            $res->result = new stdClass();
            $res->result->userIdx = getUserDetail($vars["userIdx"])["userIdx"];
            $res->result->userName = getUserDetail($vars["userIdx"])["userName"];
            $res->result->profileImageUrl = getUserDetail($vars["userIdx"])["profileImageUrl"];
            $res->result->coverImageUrl = getUserDetail($vars["userIdx"])["coverImageUrl"];
            $res->result->private = getUserDetail($vars["userIdx"])["private"];
            $res->result->working = getUserDetail($vars["userIdx"])["working"];
            if (getPostAll($vars["userIdx"])==[]){
                $res->result->post = "게시물 없음";
            }
            else{
                $res->result->post = getPostAll($vars["userIdx"]);
            }
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "내 프로필 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
//            $res->result->post = new stdClass();
//            $postIdx = [];
//            $postIdx = getPostIdx($userIdxToken);
//            $res->result->post = new stdClass();
//            $res->result->post->postIdx = getPostAll($userIdxToken)["postIdx"];
//            $res->result->post->userIdx = getPostAll($userIdxToken)["userIdx"];
//            $res->result->userName = getPostDetail($postIdx, $userIdxToken)["userName"];
//            $res->result->profileImageUrl = getPostDetail($postIdx, $userIdxToken)["profileImageUrl"];
//            $res->result->target = getPostDetail($postIdx, $userIdxToken)["target"];
//            $res->result->postContent = getPostDetail($postIdx, $userIdxToken)["postContent"];
//            $res->result->whenCreated = getPostDetail($postIdx, $userIdxToken)["whenCreated"];
//            $res->result->countLike = getPostDetail($postIdx, $userIdxToken)["countLike"];
//            $res->result->countShare = getPostDetail($postIdx, $userIdxToken)["countShare"];
//            $res->result->countComment = getPostDetail($postIdx, $userIdxToken)["countComment"];
//            $res->result->likeStatus = getPostDetail($postIdx, $userIdxToken)["likeStatus"];
//            $res->result->ImageList = getPostImages($postIdx);


        /*
        * API No. 2
        * API Name : 회원가입 API
        *마지막 수정 날짜 : 20.10.16
        */
        case "createUser":
            http_response_code(200);

            if (empty($req->userEmail)){
                $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "올바른 이메일 주소를 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif (empty($req->phone)){
                $res->isSuccess = FALSE;
                $res->code = 221;
                $res->message = "올바른 휴대폰번호를 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif (empty($req->pwd)){
            $res->isSuccess = FALSE;
                $res->code = 222;
                $res->message = "올바른 비밀번호를 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
                }
            elseif (empty($req->userName)){
                $res->isSuccess = FALSE;
                $res->code = 223;
                $res->message = "올바른 이름을 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif (empty($req->birthday)){
                $res->isSuccess = FALSE;
                $res->code = 224;
                $res->message = "올바른 생년월일을 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif (empty($req->gender)) {
                $res->isSuccess = FALSE;
                $res->code = 225;
                $res->message = "올바른 성별을 선택하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif(!(($req->gender=="M") or ($req->gender=="W"))){
                $res->isSuccess = FALSE;
                $res->code = 225;
                $res->message = "올바른 성별을 선택하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif(!is_integer($req->birthday)){
                $res->isSuccess = FALSE;
                $res->code = 224;
                $res->message = "올바른 생년월일을 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif(!is_numeric($req->phone)){
                $res->isSuccess = FALSE;
                $res->code = 221;
                $res->message = "올바른 휴대폰 번호를 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif(!is_string($req->userName)){
                $res->isSuccess = FALSE;
                $res->code = 223;
                $res->message = "올바른 이름을 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif (!isValidUserEmail($req->userEmail)){
                $res->isSuccess = FALSE;
                $res->code = 232;
                $res->message = "중복된 이메일 주소입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            //Email 형식
            elseif (!preg_match('/^[a-zA-Z]{1}[a-zA-Z0-9.\-_]+@[a-z0-9]{1}[a-z0-9\-]+[a-z0-9]{1}\.(([a-z]{1}[a-z.]+[a-z]{1})|([a-z]+))$/',$req->userEmail)){
                $res->isSuccess = FALSE;
                $res->code = 226;
                $res->message = "Email 형식에 맞지 않습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            //비밀번호 형식
            elseif(!preg_match('/^.*(?=^.{6,15}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[~!@#$%^&*()\/?_+=]).*$/', $req->pwd)){
                $res->isSuccess = FALSE;
                $res->code = 227;
                $res->message = "비밀번호는 영문대소문자, 숫자, 특수문자를 포함한 6~15글자 형식이어야합니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif(!preg_match('/^([0-9]{3,11})$/', $req->phone)){
                $res->isSuccess = FALSE;
                $res->code = 221;
                $res->message = "올바른 휴대폰 번호를 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif(!preg_match('/^(19[0-9][0-9]|20\d{2})(0[0-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])$/', $req->birthday)){
                $res->isSuccess = FALSE;
                $res->code = 224;
                $res->message = "올바른 생년월일을 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $pwd_hash = password_hash($req->pwd, PASSWORD_DEFAULT);
            createUser($req->userEmail, $pwd_hash, $req->userName, $req->birthday, $req->gender, $req->phone);
            $userIdx = getUserIdxByEmail($req->userEmail);
            $jwt = getJWT($userIdx, JWT_SECRET_KEY);

            $res->jwt = $jwt;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "회원가입 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No. 3
        * API Name : 회원탈퇴 API
        * 마지막 수정 날짜 : 20.10.16
        */
        case "deleteUser":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            if ($userIdxToken != $vars['userIdx']){
                $res->isSuccess = false;
                $res->code = 201;
                $res->message = "권한이 없는 유저입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidDeleteUser($vars['userIdx'])){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 계정입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            deleteUser($vars['userIdx']);
            $res->result = $vars['userIdx'];
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "회원탈퇴 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No. 7
        * API Name : 회원정보 수정 API
        * 마지막 수정 날짜 : 20.10.16
        */

        case "updateUser":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $userIdx = $vars['userIdx'];
            if(!is_numeric(($userIdx))){
                $res->isSuccess = false;
                $res->code = 200;
                $res->message = "존재하지않은 유저입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if ($userIdxToken != $vars['userIdx']){
                $res->isSuccess = false;
                $res->code = 201;
                $res->message = "권한이 없는 유저입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (isset($req->userEmail)){
                    //Email 형식
                if (!preg_match('/^[a-zA-Z]{1}[a-zA-Z0-9.\-_]+@[a-z0-9]{1}[a-z0-9\-]+[a-z0-9]{1}\.(([a-z]{1}[a-z.]+[a-z]{1})|([a-z]+))$/',$req->userEmail)){
                    $res->isSuccess = FALSE;
                    $res->code = 226;
                    $res->message = "Email 형식에 맞지 않습니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    exit;
                }
                elseif (!isValidUserEmail($req->userEmail)){
                    $res->isSuccess = FALSE;
                    $res->code = 232;
                    $res->message = "중복된 이메일 주소입니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                updateUserEmail($userIdxToken, $req->userEmail);
            }
            if (isset($req->phone)){
                if(!is_numeric($req->phone)){
                    $res->isSuccess = FALSE;
                    $res->code = 221;
                    $res->message = "올바른 휴대폰 번호를 입력하세요.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    exit;
                }
                elseif(!preg_match('/^([0-9]{3,11})$/', $req->phone)){
                    $res->isSuccess = FALSE;
                    $res->code = 221;
                    $res->message = "올바른 휴대폰 번호를 입력하세요.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                updateUserPhone($userIdxToken, $req->phone);
            }
            if (isset($req->pwd)){
                if(!is_string($req->phone)){
                    $res->isSuccess = FALSE;
                    $res->code = 221;
                    $res->message = "올바른 휴대폰 번호를 입력하세요.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    exit;
                }
                if(!preg_match('/^.*(?=^.{6,15}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[~!@#$%^&*()\/?_+=]).*$/', $req->pwd)){
                    $res->isSuccess = FALSE;
                    $res->code = 227;
                    $res->message = "비밀번호는 영문대소문자, 숫자, 특수문자를 포함한 6~15글자 형식이어야합니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    exit;
                }
                $pwd_hash = password_hash($req->pwd, PASSWORD_DEFAULT);
                updateUserPwd($userIdxToken, $pwd_hash);
            }
            if (isset($req->userName)){
                if(!is_string($req->userName)){
                    $res->isSuccess = FALSE;
                    $res->code = 223;
                    $res->message = "올바른 이름을 입력하세요.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    exit;
                }
                updateUserName($userIdxToken, $req->userName);
            }
            if (isset($req->gender)){
                if(!(($req->gender=="M") or ($req->gender=="W"))){
                    $res->isSuccess = FALSE;
                    $res->code = 225;
                    $res->message = "올바른 성별을 선택하세요.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    exit;
                }
                updateUserGender($userIdxToken, $req->gender);
            }
            if (isset($req->birthday)){
                if(!is_integer($req->birthday)){
                    $res->isSuccess = FALSE;
                    $res->code = 224;
                    $res->message = "올바른 생년월일을 입력하세요.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    exit;
                }
                elseif(!preg_match('/^(19[0-9][0-9]|20\d{2})(0[0-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])$/', $req->birthday)){
                    $res->isSuccess = FALSE;
                    $res->code = 224;
                    $res->message = "올바른 생년월일을 입력하세요.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    exit;
                }
                updateUserBirthday($userIdxToken, $req->birthday);
            }
            if (isset($req->job)){
                if(!is_string($req->job)){
                    $res->isSuccess = FALSE;
                    $res->code = 223;
                    $res->message = "올바른 직업을 입력하세요.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    exit;
                }
                updateUserJob($userIdxToken, $req->job);
            }
            if (isset($req->school)){
                if(!is_string($req->school)){
                    $res->isSuccess = FALSE;
                    $res->code = 223;
                    $res->message = "올바른 학교를 입력하세요.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    exit;
                }
                updateUserSchool($userIdxToken, $req->school);
            }
            if (isset($req->profileImageUrl)){
                if(!is_string($req->profileImageUrl)){
                    $res->isSuccess = FALSE;
                    $res->code = 223;
                    $res->message = "올바른 사진을 선택하세요.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    exit;
                }
                updateUserProfile($userIdxToken, $req->profileImageUrl);
            }
            if (isset($req->coverImageUrl)){
                if(!is_string($req->coverImageUrl)) {
                    $res->isSuccess = FALSE;
                    $res->code = 223;
                    $res->message = "올바른 사진을 선택하세요.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    exit;
                }
                updateUserCover($userIdxToken, $req->coverImageUrl);

            }

            $res->result = $userIdxToken;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "회원정보 수정 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No. 4
        * API Name : 게시물 전체조회 API
        * 마지막 수정 날짜 : 20.10.16
        */

        case "getPost":
            http_response_code(200);
            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if(!($_GET)) {
                $res->result = getPost();
//                $res->result->imageList = getPostImages(getPost()['postIdx']);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "게시물 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $keyword = $_GET['keyword'];

            if (!isValidPostKey($keyword)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = searchPosts($keyword);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "게시물 검색 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        /*
        * API No. 5
        * API Name : 게시물 상세조회 API
        * 마지막 수정 날짜 : 20.10.16
        */

        case "getPostDetail":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $postIdx = $vars['postIdx'];

            if(!is_numeric($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 230;
                $res->message = "유효하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidPost($postIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 230;
                $res->message = "유효하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
//            $res->result = getPostDetail($postIdx);
            $res->result = new stdClass();
            $res->result->postIdx = getPostDetail($postIdx, $userIdxToken)["postIdx"];
            $res->result->userIdx = getPostDetail($postIdx, $userIdxToken)["userIdx"];
            $res->result->userName = getPostDetail($postIdx, $userIdxToken)["userName"];
            $res->result->profileImageUrl = getPostDetail($postIdx, $userIdxToken)["profileImageUrl"];
            $res->result->target = getPostDetail($postIdx, $userIdxToken)["target"];
            $res->result->postContent = getPostDetail($postIdx, $userIdxToken)["postContent"];
            $res->result->whenCreated = getPostDetail($postIdx, $userIdxToken)["whenCreated"];
            $res->result->countLike = getPostDetail($postIdx, $userIdxToken)["countLike"];
            $res->result->countShare = getPostDetail($postIdx, $userIdxToken)["countShare"];
            $res->result->countComment = getPostDetail($postIdx, $userIdxToken)["countComment"];
            $res->result->likeStatus = getPostDetail($postIdx, $userIdxToken)["likeStatus"];
            $res->result->ImageList = getPostImages($postIdx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "게시물 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
         * API No. 6
         * API Name : 게시물 등록 API
         *마지막 수정 날짜 : 20.10.16
         */
        case "createPost":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if (empty($req->postContent)){
                $res->isSuccess = FALSE;
                $res->code = 231;
                $res->message = "내용을 입력해 주세요";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif (!is_string($req->postContent)){
                $res->isSuccess = FALSE;
                $res->code = 231;
                $res->message = "내용을 입력해 주세요";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif(!(($req->target=="A") or ($req->target=="F"))){
                $res->isSuccess = FALSE;
                $res->code = 232;
                $res->message = "올바른 공개 타입이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
//            if(!is_array($req->imageList)){
//                $res->isSuccess = FALSE;
//                $res->code = 228;
//                $res->message = "올바른 이미지 리스트 형식이 아닙니다";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }
            if(isset($req->imageList)){
                foreach ($req->imageList as $list){
                    if(!isset($list->imageUrl)){
                        $res->isSuccess = FALSE;
                        $res->code = 233;
                        $res->message = "올바른 이미지 리스트 형식이 아닙니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        exit;
                    }
                    elseif(!isset($list->imageComment)) {
                        $res->isSuccess = FALSE;
                        $res->code = 233;
                        $res->message = "올바른 이미지 리스트 형식이 아닙니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        exit;
                    }
                    if(!is_string($list->imageUrl)){
                        $res->isSuccess = FALSE;
                        $res->code = 234;
                        $res->message = "올바른 이미지 주소가 아닙니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        exit;
                    }
                    elseif(!is_string($list->imageComment)) {
                        $res->isSuccess = FALSE;
                        $res->code = 235;
                        $res->message = "올바른 이미지 내용이 아닙니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        exit;
                    }
                    elseif (empty($list->imageUrl)){
                        $res->isSuccess = FALSE;
                        $res->code = 234;
                        $res->message = "올바른 이미지 주소가 아닙니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        exit;
                    }
                }
            }
            if(isset($req->videoList)){
                foreach ($req->videoList as $list){
                    if(!isset($list->videoUrl)){
                        $res->isSuccess = FALSE;
                        $res->code = 236;
                        $res->message = "올바른 동영상 리스트 형식이 아닙니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        exit;
                    }
                    elseif(!isset($list->videoComment)) {
                        $res->isSuccess = FALSE;
                        $res->code = 236;
                        $res->message = "올바른 동영상 리스트 형식이 아닙니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        exit;
                    }
                    elseif(!isset($list->length)) {
                        $res->isSuccess = FALSE;
                        $res->code = 236;
                        $res->message = "올바른 동영상 리스트 형식이 아닙니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        exit;
                    }
                    elseif(!is_string($list->videoUrl)){
                        $res->isSuccess = FALSE;
                        $res->code = 237;
                        $res->message = "올바른 동영상 주소가 아닙니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        exit;
                    }
                    elseif(!is_string($list->videoComment)) {
                        $res->isSuccess = FALSE;
                        $res->code = 237;
                        $res->message = "올바른 동영상 내용이 아닙니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        exit;
                    }
                    elseif(!preg_match('/^[0-2][0-3]:[0-5][0-9]:[0-5][0-9]$/', $list->length)) {
                        $res->isSuccess = FALSE;
                        $res->code = 239;
                        $res->message = "올바른 동영상 형식 아닙니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        exit;
                    }
                    elseif (empty($list->videoUrl)){
                        $res->isSuccess = FALSE;
                        $res->code = 237;
                        $res->message = "올바른 동영상 주소가 아닙니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        exit;
                    }
                    elseif (empty($list->length)){
                        $res->isSuccess = FALSE;
                        $res->code = 239;
                        $res->message = "올바른 동영상 형식이 아닙니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        exit;
                    }
                }
            }
            createPost($userIdxToken, $req->postContent, $req->target);
            if(isset($req->imageList)){
                foreach ($req->imageList as $list){
                    createImage(getRecentPostIdx($userIdxToken), $list->imageUrl, $list->imageComment);
                }
            }
            if(isset($req->videoList)){
                foreach ($req->videoList as $list){
                    createVideo(getRecentPostIdx($userIdxToken), $list->videoUrl, $list->videoComment, $list->length);
                }
            }
            $res->postIdx = getRecentPostIdx($userIdxToken);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "게시물 등록 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No. 7
        * API Name : 게시물 내용 글 수정 API
        * 마지막 수정 날짜 : 20.10.16
        */

        case "updatePost":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if (empty($req->postContent)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "게시물 내용이 비어있습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (empty($req->postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!is_string($req->postContent)) {
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "올바른 게시물 형식이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!is_integer($req->postIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            updatePost($req->postIdx, $req->postContent);
            $res->result = $req->postIdx;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "게시물 수정 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No. 8
        * API Name : 게시물 삭제 API
        * 마지막 수정 날짜 : 20.10.16
        */

        case "deletePost":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $postIdx = $vars["postIdx"];

            if (!is_numeric($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidExistsPost($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($userIdxToken != getUserIdxPost($postIdx)){
                $res->isSuccess = false;
                $res->code = 201;
                $res->message = "권한이 없는 유저입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            deletePost($postIdx);
            $res->result = $postIdx;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "게시물 삭제 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No. 10
        * API Name : 게시물 좋아요 등록/취소 API
        * 마지막 수정 날짜 : 20.10.22
        */

        case "patchLike":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $postIdx = $vars['postIdx'];
            if (!is_numeric($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsPost($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isExistsLikePost($postIdx, $userIdxToken)){
                $res->postIdx = registerLike($postIdx, $userIdxToken);
                $res->result = currentPostLikeStatus($postIdx, $userIdxToken);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "게시물 좋아요 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else {
                $res->postIdx = modifyPostLike($postIdx, $userIdxToken);
                $res->result = currentPostLikeStatus($postIdx, $userIdxToken);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "게시물 좋아요 수정 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        /*
        * API No. 10
        * API Name : 이미지 좋아요 등록/취소 API
        * 마지막 수정 날짜 : 20.10.22
        */

        case "patchImageLike":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $postIdx = $vars['postIdx'];
            $imageIdx = $vars['imageIdx'];
            if (!is_numeric($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_numeric($imageIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 이미지입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsPost($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsImage($postIdx, $imageIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 이미지입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isExistsLikeImage($userIdxToken, $postIdx, $imageIdx)){
                registerImageLike($userIdxToken, $postIdx, $imageIdx);
                $res->result = currentImageLikeStatus($userIdxToken, $postIdx, $imageIdx);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "이미지 좋아요 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else {
                modifyImageLike($userIdxToken, $postIdx, $imageIdx);
                $res->result = currentImageLikeStatus($userIdxToken, $postIdx, $imageIdx);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "이미지 좋아요 수정 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        /*
        * API No. 10
        * API Name : 동영상 좋아요 등록/취소 API
        * 마지막 수정 날짜 : 20.10.22
        */

        case "patchVideoLike":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $postIdx = $vars['postIdx'];
            $videoIdx = $vars['videoIdx'];

            if (!is_numeric($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_numeric($videoIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 동영상입니다.";
               echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsPost($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsVideo($postIdx, $videoIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 동영상입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isExistsLikeVideo($userIdxToken, $postIdx, $videoIdx)){
                registerVideoLike($userIdxToken, $postIdx, $videoIdx);
                $res->result = currentVideoLikeStatus($userIdxToken, $postIdx, $videoIdx);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "동영상 좋아요 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else {
                modifyVideoLike($userIdxToken, $postIdx, $videoIdx);
                $res->result = currentVideoLikeStatus($userIdxToken, $postIdx, $videoIdx);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "동영상 좋아요 수정 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        /*
        * API No. 10
        * API Name : 댓글 좋아요 등록/취소 API
        * 마지막 수정 날짜 : 20.10.22
        */

        case "patchCommentLike":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $postIdx = $vars['postIdx'];
            $commentIdx = $vars['commentIdx'];

            if (!is_numeric($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_numeric($commentIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 댓글입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsPost($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsComment($postIdx, $commentIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 댓글입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isExistsLikeComment($userIdxToken, $postIdx, $commentIdx)){
                registerCommentLike($userIdxToken, $postIdx, $commentIdx);
                $res->result = currentCommentLikeStatus($userIdxToken, $postIdx, $commentIdx);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "댓글 좋아요 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else {
                modifyCommentLike($userIdxToken, $postIdx, $commentIdx);
                $res->result = currentCommentLikeStatus($userIdxToken, $postIdx, $commentIdx);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "댓글 좋아요 수정 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        /*
        * API No. 10
        * API Name : 페이지 좋아요 등록/취소 API
        * 마지막 수정 날짜 : 20.10.22
        */

        case "patchPageLike":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $pageIdx = $vars['pageIdx'];
            if (!is_numeric($pageIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 페이지입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsPage($pageIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 페이지입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isExistsLikePage($pageIdx, $userIdxToken)){
                registerPageLike($pageIdx, $userIdxToken);
                $res->result = currentPageLikeStatus($pageIdx, $userIdxToken);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "페이지 좋아요 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else {
                modifyPageLike($pageIdx, $userIdxToken);
                $res->result = currentPageLikeStatus($pageIdx, $userIdxToken);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "페이지 좋아요 수정 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        /*
        * API No. 11
        * API Name : 좋아요 유저 목록 조회 API
        * 마지막 수정 날짜 : 20.10.18
        */

        case "likeList":
            http_response_code(200);
            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $postIdx = $vars['postIdx'];

            if (!is_numeric($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsPost($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (totalLike($postIdx)["totalLike"] == 0){
                $res->isSuccess = FALSE;
                $res->code = 210;
                $res->message = "좋아요 없는 게시물";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->result = new stdClass();
            $res->result->postIdx = totalLike($postIdx)["postIdx"];
            $res->result->totalLike = totalLike($postIdx)["totalLike"];
            $res->result->userList = likeList($postIdx, $userIdxToken);

            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "좋아요 유저 목록 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No. 11
        * API Name : 이미지 좋아요 유저 목록 조회 API
        * 마지막 수정 날짜 : 20.10.18
        */

        case "imageLikeList":
            http_response_code(200);
            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $postIdx = $vars['postIdx'];
            $imageIdx = $vars['imageIdx'];
            if (!is_numeric($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_numeric($imageIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 이미지입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsPost($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsImage($postIdx, $imageIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 이미지입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (totalImageLike($postIdx, $imageIdx)["totalLike"] == 0){
                $res->isSuccess = FALSE;
                $res->code = 210;
                $res->message = "좋아요 없는 게시물";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = new stdClass();
            $res->result->postIdx = totalImageLike($postIdx, $imageIdx)["postIdx"];
            $res->result->imageIdx = totalImageLike($postIdx, $imageIdx)["imageIdx"];
            $res->result->totalLike = totalImageLike($postIdx, $imageIdx)["totalLike"];
            $res->result->userList = imageLikeList($postIdx, $imageIdx, $userIdxToken);

            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "좋아요 유저 목록 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No. 11
        * API Name : 동영상 좋아요 유저 목록 조회 API
        * 마지막 수정 날짜 : 20.10.18
        */

        case "videoLikeList":
            http_response_code(200);
            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $postIdx = $vars['postIdx'];
            $videoIdx = $vars['videoIdx'];

            if (!is_numeric($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_numeric($videoIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 동영상입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsPost($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsVideo($postIdx, $videoIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 동영상입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (totalVideoLike($postIdx, $videoIdx)["totalLike"] == 0){
                $res->isSuccess = FALSE;
                $res->code = 210;
                $res->message = "좋아요 없는 게시물";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = new stdClass();
            $res->result->postIdx = totalVideoLike($postIdx, $videoIdx)["postIdx"];
            $res->result->videoIdx = totalVideoLike($postIdx, $videoIdx)["videoIdx"];
            $res->result->totalLike = totalVideoLike($postIdx, $videoIdx)["totalLike"];
            $res->result->userList = videoLikeList($postIdx, $videoIdx, $userIdxToken);

            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "좋아요 유저 목록 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No. 11
        * API Name : 좋아요 유저 목록 조회 API
        * 마지막 수정 날짜 : 20.10.18
        */

        case "commentLikeList":
            http_response_code(200);
            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $postIdx = $vars['postIdx'];
            $commentIdx = $vars['commentIdx'];

            if (!is_numeric($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_numeric($commentIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 댓글입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsPost($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsComment($postIdx, $commentIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 댓글입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (totalCommentLike($postIdx, $commentIdx)["totalLike"] == 0){
                $res->isSuccess = FALSE;
                $res->code = 210;
                $res->message = "좋아요 없는 댓글";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = new stdClass();
            $res->result->postIdx = totalCommentLike($postIdx, $commentIdx)["postIdx"];
            $res->result->commentIdx = totalCommentLike($postIdx, $commentIdx)["commentIdx"];
            $res->result->totalLike = totalCommentLike($postIdx, $commentIdx)["totalLike"];
            $res->result->userList = commentLikeList($postIdx, $commentIdx, $userIdxToken);

            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "좋아요 유저 목록 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No. 11
        * API Name : 좋아요 유저 목록 조회 API
        * 마지막 수정 날짜 : 20.10.18
        */

        case "pageLikeList":
            http_response_code(200);
            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $pageIdx = $vars['pageIdx'];

            if (!is_numeric($pageIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 페이지입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsPage($pageIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 페이지입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (totalPageLike($pageIdx)["totalLike"] == 0){
                $res->isSuccess = FALSE;
                $res->code = 210;
                $res->message = "좋아요 없는 페이지";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->result = new stdClass();
            $res->result->pageIdx = totalPageLike($pageIdx)["pageIdx"];
            $res->result->totalLike = totalPageLike($pageIdx)["totalLike"];
            $res->result->userList = pageLikeList($pageIdx, $userIdxToken);

            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "페이지 좋아요 유저 목록 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No. 12
        * API Name : 댓글 등록 API
        * 마지막 수정 날짜 : 20.10.18
        */

        case "postComment":
            http_response_code(200);
            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if (empty($req->postIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "올바른 게시물 선택이 선택하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (empty($req->comContent)) {
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "올바른 댓글 형식이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (empty($req->parentIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "올바른 부모인덱스를 선택하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_integer($req->postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "올바른 게시물 선택이 선택하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_string($req->comContent)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "올바른 댓글 형식이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_integer($req->parentIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "올바른 부모인덱스를 선택하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsParentIdx($req->parentIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 부모댓글입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsPost($req->postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->postIdx = postComment($req->postIdx, $userIdxToken, $req->comContent, $req->parentIdx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "댓글 등록 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No. 12
        * API Name : 댓글 수정 API
        * 마지막 수정 날짜 : 20.10.18
        */

        case "updateComment":
            http_response_code(200);
            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            if (empty($req->postIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "올바른 접근이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (empty($req->comContent)) {
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "올바른 접근이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (empty($req->commentIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "올바른 접근이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!is_integer($req->postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "올바른 게시물 선택이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_integer($req->commentIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "올바른 댓글 선택이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_string($req->comContent)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "올바른 댓글 형식이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsPost($req->postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsComment($req->postIdx, $req->commentIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 댓글입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }


            $res->postIdx = updateComment($req->postIdx, $userIdxToken, $req->commentIdx, $req->comContent);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "댓글 수정 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No. 13
        * API Name : 댓글 조회 API
        * 마지막 수정 날짜 : 20.10.18
        */

        case "getComment":
            http_response_code(200);
            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $postIdx = $vars['postIdx'];

            if(!is_numeric($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 224;
                $res->message = "올바른 게시물을 선택하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidExistsPost($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!getComment($postIdx)){
                $res->result = "No Comment";
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "댓글 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = getComment($postIdx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "댓글 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No. 14
        * API Name : 댓글 삭제 API
        * 마지막 수정 날짜 : 20.10.18
        */
        case "deleteComment":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $commentIdx = $vars['commentIdx'];
            $postIdx = $vars['postIdx'];

            if(!is_numeric($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 224;
                $res->message = "올바른 게시물을 선택하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!is_numeric($commentIdx)){
                $res->isSuccess = FALSE;
                $res->code = 224;
                $res->message = "올바른 댓글을 선택하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsPost($postIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsComment($postIdx, $commentIdx)){
                $res->isSuccess = FALSE;
                $res->code = 230;
                $res->message = "존재하지 않은 댓글입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($userIdxToken != getUserIdxComment($postIdx, $commentIdx)){
                $res->isSuccess = false;
                $res->code = 200;
                $res->message = "권한이 없는 유저입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            deleteComment($postIdx, $commentIdx, $userIdxToken);
            $res->result = "게시물 $postIdx 댓글 $commentIdx 삭제";
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "댓글 삭제 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No. 15
        * API Name : 친구 신청 API
        * 마지막 수정 날짜 : 20.10.18
        */

        case "applyFriend":
            http_response_code(200);
            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            if(!isset($req->friendUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "올바른 요청이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!is_integer($req->friendUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 계정입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidExistsUser($req->friendUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 계정입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (isValidExistsFriendList($userIdxToken, $req->friendUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "이미 친구 신청한 회원입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            applyFriend($userIdxToken, $req->friendUserIdx);
            $res->result = $req->friendUserIdx;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "친구 신청 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No. 16
        * API Name : 친구 신청 수락 API
        * 마지막 수정 날짜 : 20.10.18
        */

        case "acceptApply":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if(!isset($req->friendUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "올바른 요청이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!is_integer($req->friendUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 계정입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsUser($req->friendUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 계정입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!(($req->request=="Y") or ($req->request=="N"))){
                $res->isSuccess = FALSE;
                $res->code = 228;
                $res->message = "올바른 반응 타입이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidExistsFriendList($userIdxToken, $req->friendUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 228;
                $res->message = "존재하지 않은 친구신청입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(isValidExistsFriendYes($userIdxToken, $req->friendUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 228;
                $res->message = "이미 수락한 친구신청입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(isValidExistsFriendNo($userIdxToken, $req->friendUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 228;
                $res->message = "이미 거절한 친구신청입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if($req->request=="Y"){
                acceptApply($userIdxToken, $req->friendUserIdx);
                $res->result =$req->friendUserIdx;
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "친구 신청 수락 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if($req->request=="N"){
                rejectFriend($userIdxToken, $req->friendUserIdx);
                $res->result =$req->friendUserIdx;
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "친구 신청 거절 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        /*
        * API No. 28
        * API Name : 친구 삭제 API
        * 마지막 수정 날짜 : 20.10.18
        */
        case "deleteFriend":
            http_response_code(200);
            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            if(!isset($req->friendUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "올바른 요청이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!is_integer($req->friendUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 계정입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsUser($req->friendUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 계정입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            deleteFriend($userIdxToken, $req->friendUserIdx);
            $res->result = $req->friendUserIdx;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "친구 삭제 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No. 19
        * API Name : 내 친구 목록 조회 API
        * 마지막 수정 날짜 : 20.10.18
        */

        case "getFriend":
            http_response_code(200);
            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if(empty(getFriend($userIdxToken))){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "친구가 존재하지 않습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = getFriend($userIdxToken);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "내 친구 목록 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No. 19
        * API Name : 친구 신청 목록 조회 API
        * 마지막 수정 날짜 : 20.10.18
        */

        case "waitingFriend":
            http_response_code(200);
            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if(empty(getWaitingFriend($userIdxToken))){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "친구신청이 존재하지 않습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = getWaitingFriend($userIdxToken);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "친구 신청 목록 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No. 5
        * API Name : 이미지 상세조회 API
        * 마지막 수정 날짜 : 20.10.16
        */

        case "getImageDetail":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $postIdx = $vars['postIdx'];
            $imageIdx = $vars['imageIdx'];


            if (!isValidPost($postIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsImage($postIdx, $imageIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 이미지입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = new stdClass();
            $res->result = getImageDetail($postIdx, $imageIdx, $userIdxToken);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "이미지 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No. 5
        * API Name : 비디오 상세조회 API
        * 마지막 수정 날짜 : 20.10.16
        */

        case "getVideoDetail":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $postIdx = $vars['postIdx'];
            $videoIdx = $vars['videoIdx'];


            if (!isValidPost($postIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 게시물입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidVideo($postIdx, $videoIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 동영상입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = new stdClass();
            $res->result = getVideoDetail($postIdx, $videoIdx, $userIdxToken);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "비디오 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No. 2
        * API Name : 페이지 만들기 API
        *마지막 수정 날짜 : 20.10.16
        */
        case "createPage":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if (empty($req->pageName)){
                $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "올바른 페이지 이름을 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif (empty($req->category)){
                $res->isSuccess = FALSE;
                $res->code = 221;
                $res->message = "올바른 카테고리를 선택하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif (empty($req->subCategory)){
                $res->isSuccess = FALSE;
                $res->code = 222;
                $res->message = "올바른 하위 카테고리를 선택하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif(strlen($req->pageName)<3){
                $res->isSuccess = FALSE;
                $res->code = 225;
                $res->message = "올바른 페이지 이름을 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif(!is_integer($req->category)){
                $res->isSuccess = FALSE;
                $res->code = 224;
                $res->message = "올바른 카테고리를 선택하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif(!is_integer($req->subCategory)){
                $res->isSuccess = FALSE;
                $res->code = 221;
                $res->message = "올바른 하위 카테고리를 선택하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif(!is_string($req->webUrl)){
                $res->isSuccess = FALSE;
                $res->code = 221;
                $res->message = "올바른 웹주소를 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif(!is_string($req->profileImageUrl)){
                $res->isSuccess = FALSE;
                $res->code = 221;
                $res->message = "올바른 프로필사진을 선택하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif(!is_string($req->coverImageUrl)){
                $res->isSuccess = FALSE;
                $res->code = 221;
                $res->message = "올바른 커버사진을 선택하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif (isExistsPageName($req->pageName)){
                $res->isSuccess = FALSE;
                $res->code = 232;
                $res->message = "중복된 페이지 이름입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif (!isExistsCategory($req->category)){
                $res->isSuccess = FALSE;
                $res->code = 232;
                $res->message = "올바른 카테고리를 선택하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif (!isExistsSubCategory($req->category, $req->subCategory)){
                $res->isSuccess = FALSE;
                $res->code = 232;
                $res->message = "올바른 하위 카테고리를 선택하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            createPage($userIdxToken, $req->pageName, $req->category, $req->subCategory, $req->webUrl, $req->profileImageUrl, $req->coverImageUrl);
            $res->pageIdx = getPageIdx($userIdxToken);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "페이지 만들기 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
         * API No. 6
         * API Name : 스토리 이미지 등록 API
         *마지막 수정 날짜 : 20.10.25
         */
        case "createStoryImage":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if (empty($req->storyImageUrl)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "사진을 등록해 주세요";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif (!is_string($req->storyImageUrl)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "사진을 등록해 주세요";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif(!(($req->target=="A") or ($req->target=="F"))){
                $res->isSuccess = FALSE;
                $res->code = 228;
                $res->message = "올바른 공개 타입이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if($req->textList){
                foreach ($req->textList as $list){
                    if(!isset($list->textContent)){
                        $res->isSuccess = FALSE;
                        $res->code = 228;
                        $res->message = "올바른 텍스트 형식이 아닙니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        exit;
                    }
                    if(!is_string($list->textContent)){
                        $res->isSuccess = FALSE;
                        $res->code = 228;
                        $res->message = "올바른 텍스트 형식이 아닙니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        exit;
                    }
                    elseif (empty($list->textContent)){
                        $res->isSuccess = FALSE;
                        $res->code = 228;
                        $res->message = "올바른 텍스트 형식이 아닙니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        exit;
                    }
                }
            }
            if($req->tagList){
                foreach ($req->tagList as $list){
                    if(!isset($list->tagUserIdx)){
                        $res->isSuccess = FALSE;
                        $res->code = 228;
                        $res->message = "올바른 태그 형식이 아닙니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        exit;
                    }
                    elseif(!is_integer($list->tagUserIdx)){
                        $res->isSuccess = FALSE;
                        $res->code = 228;
                        $res->message = "올바른 태그 형식이 아닙니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        exit;
                    }
                    elseif (empty($list->tagUserIdx)){
                        $res->isSuccess = FALSE;
                        $res->code = 228;
                        $res->message = "올바른 태그 형식이 아닙니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        exit;
                    }

                }
            }
            createStoryImage($userIdxToken, $req->storyImageUrl, $req->target);
            if($req->textList){
                foreach ($req->textList as $list){
                    createStoryText(getRecentStoryIdx($userIdxToken), $list->textContent);
                }
            }
            if($req->tagList){
                foreach ($req->tagList as $list){
                    createTag($userIdxToken, $list->tagUserIdx, getRecentPostIdx($userIdxToken));
                }
            }
            $res->postIdx = getRecentPostIdx($userIdxToken);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "스토리 등록 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No. 15
        * API Name : 차단하기 API
        * 마지막 수정 날짜 : 20.10.25
        */

        case "blackList":
            http_response_code(200);
            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if(!isset($req->blackUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "올바른 요청이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!is_integer($req->blackUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 계정입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsUserIdx($req->blackUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 계정입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (isValidExistsBlackList($userIdxToken, $req->blackUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "이미 차단한 회원입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (isExistsBlackList($userIdxToken, $req->blackUserIdx)){
                modifyBlackList($userIdxToken, $req->blackUserIdx);
                $res->result = $req->blackUserIdx;
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "차단하기 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else {
                blackList($userIdxToken, $req->blackUserIdx);
                $res->result = $req->blackUserIdx;
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "차단하기 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        /*
        * API No. 15
        * API Name : 차단하기 해제 API
        * 마지막 수정 날짜 : 20.10.25
        */

        case "releaseBlack":
            http_response_code(200);
            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if(!isset($req->blackUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "올바른 요청이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!is_integer($req->blackUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 계정입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsUserIdx($req->blackUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 계정입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsBlackList($userIdxToken, $req->blackUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "차단하지 않은 회원입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            modifyBlackList($userIdxToken, $req->blackUserIdx);
            $res->result = $req->blackUserIdx;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "차단하기 해제 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No. 15
        * API Name : 즐겨찾기 API
        * 마지막 수정 날짜 : 20.10.25
        */

        case "favorites":
            http_response_code(200);
            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if(!isset($req->favoritesUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "올바른 요청이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!is_integer($req->favoritesUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 계정입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidExistsUserIdx($req->favoritesUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않은 계정입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (isValidExistsFavorites($userIdxToken, $req->favoritesUserIdx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "이미 즐겨찾기 회원입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (isExistsFavorites($userIdxToken, $req->favoritesUserIdx)){
                modifyFavorites($userIdxToken, $req->favoritesUserIdx);
                $res->result = $req->favoritesUserIdx;
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "즐겨찾기 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else {
                Favorites($userIdxToken, $req->favoritesUserIdx);
                $res->result = $req->favoritesUserIdx;
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "즐겨찾기 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }


    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
