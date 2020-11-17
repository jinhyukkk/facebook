<?php

////READ
//function test()
//{
//    $pdo = pdoSqlConnect();
//    $query = "SELECT * FROM Test";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res;
//}
//
////READ
//function testDetail($testNo)
//{
//    $pdo = pdoSqlConnect();
//    $query = "SELECT * FROM Test WHERE no = ?;";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$testNo]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res[0];
//}
//
//function testPost($name)
//{
//    $pdo = pdoSqlConnect();
//    $query = "INSERT INTO Test (name) VALUES (?);";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$name]);
//
//    $st = null;
//    $pdo = null;
//
//}
//
// CREATE
//    function addMaintenance($message){
//        $pdo = pdoSqlConnect();
//        $query = "INSERT INTO MAINTENANCE (MESSAGE) VALUES (?);";
//
//        $st = $pdo->prepare($query);
//        $st->execute([$message]);
//
//        $st = null;
//        $pdo = null;
//
//    }
//
// UPDATE
//    function updateMaintenanceStatus($message, $status, $no){
//        $pdo = pdoSqlConnect();
//        $query = "UPDATE MAINTENANCE
//                        SET MESSAGE = ?,
//                            STATUS  = ?
//                        WHERE NO = ?";
//
//        $st = $pdo->prepare($query);
//        $st->execute([$message, $status, $no]);
//        $st = null;
//        $pdo = null;
//    }
//
// RETURN BOOLEAN
//    function isRedundantEmail($email){
//        $pdo = pdoSqlConnect();
//        $query = "SELECT EXISTS(SELECT * FROM USER_TB WHERE EMAIL= ?) AS exist;";
//
//
//        $st = $pdo->prepare($query);
//        //    $st->execute([$param,$param]);
//        $st->execute([$email]);
//        $st->setFetchMode(PDO::FETCH_ASSOC);
//        $res = $st->fetchAll();
//
//        $st=null;$pdo = null;
//
//        return intval($res[0]["exist"]);
//
//    }

// 이메일 수정
    function updateUserEmail($userIdx, $userEmail){
        $pdo = pdoSqlConnect();
        $query = "UPDATE User SET userEmail = '$userEmail' WHERE userIdx = $userIdx";

        $st = $pdo->prepare($query);
        $st->execute([$userIdx, $userEmail]);
        $st = null;
        $pdo = null;
    }
// 모바일 수정
function updateUserPhone($userIdx, $phone){
    $pdo = pdoSqlConnect();
    $query = "UPDATE User SET phone = $phone WHERE userIdx = $userIdx";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $phone]);
    $st = null;
    $pdo = null;
}
// 비밀번호 수정
function updateUserPwd($userIdx, $pwd){
    $pdo = pdoSqlConnect();
    $query = "UPDATE User SET pwd = '$pwd' WHERE userIdx = $userIdx";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $pwd]);
    $st = null;
    $pdo = null;
}
// 이름 수정
function updateUserName($userIdx, $userName){
    $pdo = pdoSqlConnect();
    $query = "UPDATE User SET userName = $userName WHERE userIdx = $userIdx";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $userName]);
    $st = null;
    $pdo = null;
}
// 성별 수정
function updateUserGender($userIdx, $gender){
    $pdo = pdoSqlConnect();
    $query = "UPDATE User SET gender = $gender WHERE userIdx = $userIdx";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $gender]);
    $st = null;
    $pdo = null;
}
// 생일 수정
function updateUserBirthday($userIdx, $birthday){
    $pdo = pdoSqlConnect();
    $query = "UPDATE User SET birthday = $birthday WHERE userIdx = $userIdx";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $birthday]);
    $st = null;
    $pdo = null;
}
// 직장 수정
function updateUserJob($userIdx, $job){
    $pdo = pdoSqlConnect();
    $query = "UPDATE User SET job = $job WHERE userIdx = $userIdx";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $job]);
    $st = null;
    $pdo = null;
}
// 학교 수정
function updateUserSchool($userIdx, $school){
    $pdo = pdoSqlConnect();
    $query = "UPDATE User SET school = $school WHERE userIdx = $userIdx";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $school]);
    $st = null;
    $pdo = null;
}
// 프로필 이미지 수정
function updateUserProfile($userIdx, $profileImageUrl){
    $pdo = pdoSqlConnect();
    $query = "UPDATE User SET profileImageUrl = $profileImageUrl WHERE userIdx = $userIdx";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $profileImageUrl]);
    $st = null;
    $pdo = null;
}
// 커버 이미지 수정
function updateUserCover($userIdx, $coverImageUrl){
    $pdo = pdoSqlConnect();
    $query = "UPDATE User SET coverImageUrl = $coverImageUrl WHERE userIdx = $userIdx";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $coverImageUrl]);
    $st = null;
    $pdo = null;
}
//READ
//전체유저조회
function allUsers()
{
    $pdo = pdoSqlConnect();
    $query = "select userIdx,
       userName,
       IF(isnull(profileImageUrl), 'Default', profileImageUrl) as profileImageUrl,
       private,
       IF(isnull(job), IF(isnull(school), 'Default', school), job) as working
from User where isDeleted = 'N'";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
//    echo json_encode($res);

    return $res;
}

//READ
//유저검색
function searchUsers($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "select userIdx,
       userName,
       IF(isnull(profileImageUrl), 'Default', profileImageUrl) as profileImageUrl,
       private,
       IF(isnull(job), IF(isnull(school), 'Default', school), job) as working
from User
where userName like concat('%', ?, '%') AND isDeleted = 'N';";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

//READ
//유저검색 Validation
function isValidKeyword($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select userIdx,
       userName,
       profileImageUrl,
       private,
       IF(isnull(job), IF(isnull(school), '', school), job) as working
from User
where userName like concat('%', ?, '%') AND isDeleted = 'N') as exist";

    $st = $pdo->prepare($query);
    $st->execute([$keyword]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}

//READ
function getUserDetail($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select userIdx,
       userName,
       IF(isnull(profileImageUrl), 'Default', profileImageUrl) as profileImageUrl,
       IF(isnull(profileImageUrl), 'Default', profileImageUrl) as coverImageUrl,
       private,
       IF(isnull(job), IF(isnull(school), 'Default', school), job) as working
from User where isDeleted = 'N' and userIdx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

// 회원가입
function createUser($userEmail, $pwd, $userName, $birthday, $gender, $phone)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO User (userEmail, pwd, userName, birthday, gender, phone) 
                VALUES ('".$userEmail."','".$pwd."','".$userName."','".$birthday."','".$gender."', '".$phone."');";

    $st = $pdo->prepare($query);
    $st->execute([$userEmail, $pwd, $userName, $birthday, $gender, $phone]);

    $st = null;
    $pdo = null;

}

//Validation Email 중복
function isValidUserEmail($userEmail)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select userEmail from User where userEmail=? AND isDeleted = 'N') as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userEmail]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

//    var_dump($res);

    return !intval($res[0]['exist']);
}


//회원탈퇴
function deleteUser($userIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE User SET isDeleted='Y' where userIdx = $userIdx;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    $st = null;
    $pdo = null;
}

//회원탈퇴 Validation
function isValidDeleteUser($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM User WHERE userIdx = $userIdx AND isDeleted = 'N') AS exist";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}

//READ
//게시글 조회
function getPost()
{
    $pdo = pdoSqlConnect();
    $query = "select Post.postIdx,
       User.userIdx,
       userName,
       IF(isnull(profileImageUrl), 'Default', profileImageUrl) as profileImageUrl,
       target,
       postContent,
       case
           when timestampdiff(hour, Post.createdAt, current_timestamp()) < 1
               then concat(timestampdiff(minute, Post.createdAt, current_timestamp), '분')
           when timestampdiff(hour, Post.createdAt, current_timestamp()) < 24
               then concat(timestampdiff(hour, Post.createdAt, current_timestamp), '시간')
           when timestampdiff(day, Post.createdAt, current_timestamp()) < 7
               then concat(timestampdiff(day, Post.createdAt, current_timestamp), '일')
           else date_format(Post.createdAt, '%c월 %e일 오후 %h:%i')
           end as whenCreated,
       countLike,
       countComment,
       countShare
from Post
         join User on User.userIdx = Post.userIdx
         join (select Post.postIdx, count(LikePost.userIdx) as countLike
               from Post
                        LEFT OUTER JOIN LikePost on LikePost.postIdx = Post.postIdx
               group by Post.postIdx) LP on LP.postIdx = Post.postIdx
         join (select Post.postIdx, count(commentIdx) as countComment from Post
                        LEFT OUTER JOIN Comment on Comment.postIdx = Post.postIdx
               group by Post.postIdx) Com on Com.postIdx = Post.postIdx
         join (select Post.postIdx, count(shareIdx) as countShare from Post
                        LEFT OUTER JOIN Share on Share.postIdx = Post.postIdx
               group by Post.postIdx) Share on Share.postIdx = Post.postIdx
               where Post.isDeleted = 'N'";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

//READ
//게시글 상세조회
function getPostDetail($postIdx, $userIdxToken)
{
    $pdo = pdoSqlConnect();
    $query = "select Post.postIdx,
       User.userIdx,
       userName,
       IF(isnull(profileImageUrl), 'Default', profileImageUrl) as profileImageUrl,
       target,
       postContent,
       case
           when timestampdiff(hour, Post.createdAt, current_timestamp()) < 1
               then concat(timestampdiff(minute, Post.createdAt, current_timestamp), '분')
           when timestampdiff(hour, Post.createdAt, current_timestamp()) < 24
               then concat(timestampdiff(hour, Post.createdAt, current_timestamp), '시간')
           when timestampdiff(day, Post.createdAt, current_timestamp()) < 7
               then concat(timestampdiff(day, Post.createdAt, current_timestamp), '일')
           else date_format(Post.createdAt, '%c월 %e일 오후 %h:%i')
           end as whenCreated,
       countLike,
       countShare,
       countComment,
       IF(exists(select * from LikePost where postIdx = $postIdx and userIdx = $userIdxToken and isDeleted = 'N'), 'Y', 'N') as likeStatus
from Post
         join User on User.userIdx = Post.userIdx
         join (select Post.postIdx, count(LikePost.userIdx) as countLike
               from Post
                        LEFT OUTER JOIN LikePost on LikePost.postIdx = Post.postIdx
               group by Post.postIdx) LP on LP.postIdx = Post.postIdx
         join (select Post.postIdx, count(commentIdx) as countComment from Post
                        LEFT OUTER JOIN Comment on Comment.postIdx = Post.postIdx
               group by Post.postIdx) Com on Com.postIdx = Post.postIdx
         join (select Post.postIdx, count(shareIdx) as countShare from Post
                        LEFT OUTER JOIN Share on Share.postIdx = Post.postIdx
               group by Post.postIdx) Share on Share.postIdx = Post.postIdx
where Post.postIdx = $postIdx AND Post.isDeleted = 'N';";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $userIdxToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

//게시글 상세조회
function getPostAll($userIdxToken)
{
    $pdo = pdoSqlConnect();
    $query = "select Post.postIdx,
       User.userIdx,
       userName,
       IF(isnull(profileImageUrl), 'Default', profileImageUrl) as profileImageUrl,
       target,
       postContent,
       case
           when timestampdiff(hour, Post.createdAt, current_timestamp()) < 1
               then concat(timestampdiff(minute, Post.createdAt, current_timestamp), '분')
           when timestampdiff(hour, Post.createdAt, current_timestamp()) < 24
               then concat(timestampdiff(hour, Post.createdAt, current_timestamp), '시간')
           when timestampdiff(day, Post.createdAt, current_timestamp()) < 7
               then concat(timestampdiff(day, Post.createdAt, current_timestamp), '일')
           else date_format(Post.createdAt, '%c월 %e일 오후 %h:%i')
           end as whenCreated,
       countLike,
       countShare,
       countComment,
       IF(exists(select * from LikePost where postIdx = Post.postIdx and userIdx = $userIdxToken and isDeleted = 'N'), 'Y', 'N') as likeStatus
from Post
         join User on User.userIdx = Post.userIdx
         join (select Post.postIdx, count(LikePost.userIdx) as countLike
               from Post
                        LEFT OUTER JOIN LikePost on LikePost.postIdx = Post.postIdx
               group by Post.postIdx) LP on LP.postIdx = Post.postIdx
         join (select Post.postIdx, count(commentIdx) as countComment from Post
                        LEFT OUTER JOIN Comment on Comment.postIdx = Post.postIdx
               group by Post.postIdx) Com on Com.postIdx = Post.postIdx
         join (select Post.postIdx, count(shareIdx) as countShare from Post
                        LEFT OUTER JOIN Share on Share.postIdx = Post.postIdx
               group by Post.postIdx) Share on Share.postIdx = Post.postIdx
where Post.userIdx = $userIdxToken AND Post.isDeleted = 'N';";

    $st = $pdo->prepare($query);
    $st->execute([$userIdxToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//이미지
function getPostImages2()
{
    $pdo = pdoSqlConnect();
    $query = "select imageIdx, imageUrl
from PostImage
where isDeleted = 'N'
order by imageIdx;";

    $st = $pdo->prepare($query);
    $st->execute([]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;

}
//이미지
function getPostImages($postIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select imageIdx, imageUrl
from PostImage
where postIdx = $postIdx AND isDeleted = 'N'
order by imageIdx;";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;

}

// 게시물 인덱스
function getPostIdx($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT postIdx FROM Post where userIdx = ? ORDER BY createdAt ASC";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
//게시글상세조회 Validation
function isValidPost($postIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS (select Post.postIdx,
       User.userIdx,
       userName,
       profileImageUrl,
       target,
       postContent,
       case
           when timestampdiff(hour, Post.createdAt, current_timestamp()) < 1
               then concat(timestampdiff(minute, Post.createdAt, current_timestamp), '분')
           when timestampdiff(hour, Post.createdAt, current_timestamp()) < 24
               then concat(timestampdiff(hour, Post.createdAt, current_timestamp), '시간')
           when timestampdiff(day, Post.createdAt, current_timestamp()) < 7
               then concat(timestampdiff(day, Post.createdAt, current_timestamp), '일')
           else date_format(Post.createdAt, '%c월 %e일 오후 %h:%i')
           end as whenCreated,
       countLike,
       countShare,
       countComment
from Post
         join User on User.userIdx = Post.userIdx
         join (select Post.postIdx, count(LikePost.userIdx) as countLike
               from Post
                        LEFT OUTER JOIN LikePost on LikePost.postIdx = Post.postIdx
               group by Post.postIdx) LP on LP.postIdx = Post.postIdx
         join (select Post.postIdx, count(commentIdx) as countComment from Post
                        LEFT OUTER JOIN Comment on Comment.postIdx = Post.postIdx
               group by Post.postIdx) Com on Com.postIdx = Post.postIdx
         join (select Post.postIdx, count(shareIdx) as countShare from Post
                        LEFT OUTER JOIN Share on Share.postIdx = Post.postIdx
               group by Post.postIdx) Share on Share.postIdx = Post.postIdx
where Post.postIdx = $postIdx AND Post.isDeleted = 'N') as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}
// 게시물 등록
function createPost($userIdx, $postContent, $target)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO Post (userIdx, postContent, target)
                VALUES ('".$userIdx."','".$postContent."','".$target."');";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $postContent, $target]);

    $st = null;
    $pdo = null;

}
// CREATE
//    function createPost($userIdx, $postContent, $target, $imageList){
//        $pdo = pdoSqlConnect();
//        //
//
//// 작업 성공여부를 나타내는 플래그
//        $success = true;
//
//
//// 트랙잭션을 시작한다.
//        $result = @mysqli_query("SET AUTOCOMMIT=0", $conn);
//        $result = @mysqli_query("BEGIN", $conn);
//
//
//// 첫번째 작업 수행
//        $insertquery  = "INSERT INTO tbl1 (f1, f2) values (\'aa\', \'bb\')";
//        $result = @mysql_query($insertquery, $conn);
//        if(!$result || @mysql_affected_row($result) == 0) $success = false;
//// 두번째 작업 수행
//        $insertquery  = "INSERT INTO tbl2 (f1, f2) values (\'aa\', \'bb\')";
//        $result = @mysql_query($insertquery, $conn);
//        if(!$result || @mysql_affected_row($result) == 0) $success = false;
//
//
//// 작업 성공/실패 여부에 따라 COMMIT/ROLLBACK 처리한다.
//        if(!$success) {
//            $result = @mysqli_query("ROLLBACK", $conn);
//            echo ("롤백되었습니다.");
//        } else {
//            $result = @mysql_query("COMMIT", $conn);
//            echo("입력되었습니다.");
//        }
////
//        $st = $pdo->prepare($query);
//        $st->execute([$userIdx, $postContent, $target, $imageList]);
//
//        $st = null;
//        $pdo = null;
//
//
//    }
// 게시물 등록
//function createPostTransaction($userIdx, $postContent, $target){
//    $pdo = pdoSqlConnect();
//    try {
//        $pdo->startTransaction();
//        $query = "START TRANSACTION" ;
//
//        $st = $pdo->prepare($query);
//
//        $st->execute([$userIdx, $postContent, $target]);
//
//        $pdo->commit();
//    }
//    catch (\Exception $e) {
//        if ($pdo->inTransaction()) {
//            $pdo->rollback();
//            // If we got here our two data updates are not in the database
//        }
//        throw $e;
//    }
//}
// 게시물 사진 등록
function createImage($postIdx, $imageUrl, $imageComment)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO PostImage (postIdx, imageUrl, imageComment)
                VALUES ('".$postIdx."','".$imageUrl."','".$imageComment."');";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $imageUrl, $imageComment]);

    $st = null;
    $pdo = null;

}
// 게시물 비디오 등록
function createVideo($postIdx, $videoUrl, $videoComment, $length)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO PostVideo (postIdx, videoUrl, videoComment, videoLength)
                VALUES ('".$postIdx."','".$videoUrl."','".$videoComment."', '".$length."');";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $videoUrl, $videoComment, $length]);

    $st = null;
    $pdo = null;

}
// 게시물 인덱스
function getRecentPostIdx($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT postIdx FROM Post where userIdx = ? ORDER BY createdAt DESC limit 1";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['postIdx'];
}
// 스토리 인덱스
function getRecentStoryIdx($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT storyIdx FROM StoryImage where userIdx = ? ORDER BY createdAt DESC limit 1";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['postIdx'];
}

//게시물 검색
function searchPosts($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "select Post.postIdx,
       User.userIdx,
       userName,
       IF(isnull(profileImageUrl), 'Default', profileImageUrl) as profileImageUrl,
       target,
       postContent,
       case
           when timestampdiff(hour, Post.createdAt, current_timestamp()) < 1
               then concat(timestampdiff(minute, Post.createdAt, current_timestamp), '분')
           when timestampdiff(hour, Post.createdAt, current_timestamp()) < 24
               then concat(timestampdiff(hour, Post.createdAt, current_timestamp), '시간')
           when timestampdiff(day, Post.createdAt, current_timestamp()) < 7
               then concat(timestampdiff(day, Post.createdAt, current_timestamp), '일')
           else date_format(Post.createdAt, '%c월 %e일 오후 %h:%i')
           end as whenCreated,
       countLike,
       countComment,
       countShare
from Post
         join User on User.userIdx = Post.userIdx
         join (select Post.postIdx, count(LikePost.userIdx) as countLike
               from Post
                        LEFT OUTER JOIN LikePost on LikePost.postIdx = Post.postIdx
               group by Post.postIdx) LP on LP.postIdx = Post.postIdx
         join (select Post.postIdx, count(commentIdx) as countComment from Post
                        LEFT OUTER JOIN Comment on Comment.postIdx = Post.postIdx
               group by Post.postIdx) Com on Com.postIdx = Post.postIdx
         join (select Post.postIdx, count(shareIdx) as countShare from Post
                        LEFT OUTER JOIN Share on Share.postIdx = Post.postIdx
               group by Post.postIdx) Share on Share.postIdx = Post.postIdx
where postContent like concat('%', ?, '%') AND Post.isDeleted = 'N';";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

//READ
//게시물검색 Validation
function isValidPostKey($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select Post.postIdx,
       User.userIdx,
       userName,
       profileImageUrl,
       target,
       postContent,
       case
           when timestampdiff(hour, Post.createdAt, current_timestamp()) < 1
               then concat(timestampdiff(minute, Post.createdAt, current_timestamp), '분')
           when timestampdiff(hour, Post.createdAt, current_timestamp()) < 24
               then concat(timestampdiff(hour, Post.createdAt, current_timestamp), '시간')
           when timestampdiff(day, Post.createdAt, current_timestamp()) < 7
               then concat(timestampdiff(day, Post.createdAt, current_timestamp), '일')
           else date_format(Post.createdAt, '%c월 %e일 오후 %h:%i')
           end as whenCreated,
       countLike,
       countComment,
       countShare
from Post
         join User on User.userIdx = Post.userIdx
         join (select Post.postIdx, count(LikePost.userIdx) as countLike
               from Post
                        LEFT OUTER JOIN LikePost on LikePost.postIdx = Post.postIdx
               group by Post.postIdx) LP on LP.postIdx = Post.postIdx
         join (select Post.postIdx, count(commentIdx) as countComment from Post
                        LEFT OUTER JOIN Comment on Comment.postIdx = Post.postIdx
               group by Post.postIdx) Com on Com.postIdx = Post.postIdx
         join (select Post.postIdx, count(shareIdx) as countShare from Post
                        LEFT OUTER JOIN Share on Share.postIdx = Post.postIdx
               group by Post.postIdx) Share on Share.postIdx = Post.postIdx
where postContent like concat('%', ?, '%') AND Post.isDeleted = 'N') as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$keyword]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}

 //게시물 내용 글 수정
    function updatePost($postIdx, $postContent){
        $pdo = pdoSqlConnect();
        $query = "UPDATE Post SET postContent = '$postContent' where postIdx = $postIdx AND isDeleted = 'N';";
        $st = $pdo->prepare($query);
        $st->execute([$postIdx, $postContent]);
        $st = null;
        $pdo = null;
    }

//게시글 삭제
function deletePost($postIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE Post SET isDeleted='Y' where Post.postIdx = $postIdx;";
    $st = $pdo->prepare($query);
    $st->execute([$postIdx]);
    $st = null;
    $pdo = null;
}
//존재하지 않은 게시물 Validation
function isValidExistsPost($postIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Post WHERE postIdx = $postIdx AND isDeleted = 'N') AS exist";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}
//존재하는 이미지 Validation
function isValidExistsImage($postIdx, $imageIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM PostImage WHERE postIdx = $postIdx AND imageIdx = $imageIdx AND isDeleted = 'N') AS exist";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $imageIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}
//존재하지 않은 동영상 Validation
function isValidExistsVideo($postIdx, $videoIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM PostVideo WHERE postIdx = $postIdx AND videoIdx = $videoIdx AND isDeleted = 'N') AS exist";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $videoIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}
//존재하지 않은 댓글 Validation
function isValidExistsComment($postIdx, $commentIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Comment WHERE postIdx = $postIdx AND commentIdx = $commentIdx AND isDeleted = 'N') AS exist";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $commentIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}
//존재하는 부모댓글 Validation
function isValidExistsParentIdx($parentIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Comment WHERE CommentIdx = $parentIdx AND isDeleted = 'N') AS exist";

    $st = $pdo->prepare($query);
    $st->execute([$parentIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}
//존재하는 페이지 Validation
function isValidExistsPage($pageIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Page WHERE pageIdx = $pageIdx AND isDeleted = 'N') AS exist";

    $st = $pdo->prepare($query);
    $st->execute([$pageIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}
//게시글 좋아요 수정
function modifyPostLike($postIdx, $userIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE LikePost SET isDeleted = if(isDeleted = 'Y', 'N','Y') where postIdx = $postIdx and userIdx = $userIdx;";
    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $userIdx]);
    $st = null;
    $pdo = null;

    return $postIdx;
}
//이미지 좋아요 수정
function modifyImageLike($userIdx, $postIdx, $imageIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE LikeImage SET isDeleted = if(isDeleted = 'Y', 'N','Y') where postIdx = $postIdx and userIdx = $userIdx and imageIdx=$imageIdx;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $postIdx, $imageIdx]);
    $st = null;
    $pdo = null;

}
//동영상 좋아요 수정
function modifyVideoLike($userIdx, $postIdx, $videoIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE LikeVideo SET isDeleted = if(isDeleted = 'Y', 'N','Y') where postIdx = $postIdx and userIdx = $userIdx and videoIdx=$videoIdx;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $postIdx, $videoIdx]);
    $st = null;
    $pdo = null;

}
//댓글 좋아요 수정
function modifyCommentLike($userIdx, $postIdx, $commentIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE LikeComment SET isDeleted = if(isDeleted = 'Y', 'N','Y') where postIdx = $postIdx and userIdx = $userIdx and commentIdx=$commentIdx;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $postIdx, $commentIdx]);
    $st = null;
    $pdo = null;

}
//페이지 좋아요 수정
function modifyPageLike($pageIdx, $userIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE LikePage SET isDeleted = if(isDeleted = 'Y', 'N','Y') where pageIdx = $pageIdx and userIdx = $userIdx;";
    $st = $pdo->prepare($query);
    $st->execute([$pageIdx, $userIdx]);
    $st = null;
    $pdo = null;

}
//존재하는 게시물 좋아요 Validation
function isExistsLikePost($postIdx, $userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM LikePost WHERE postIdx = $postIdx AND userIdx=$userIdx) AS exist";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}
//존재하는 이미지 좋아요 Validation
function isExistsLikeImage($userIdx, $postIdx, $imageIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM LikeImage WHERE postIdx = $postIdx AND userIdx=$userIdx AND imageIdx = $imageIdx) AS exist";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $postIdx, $imageIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}
//존재하는 동영상 좋아요 Validation
function isExistsLikeVideo($userIdx, $postIdx, $videoIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM LikeVideo WHERE postIdx = $postIdx AND userIdx=$userIdx AND videoIdx = $videoIdx) AS exist";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $postIdx, $videoIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}
//존재하는 댓글 좋아요 Validation
function isExistsLikeComment($userIdx, $postIdx, $commentIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM LikeComment WHERE postIdx = $postIdx AND userIdx=$userIdx AND commentIdx = $commentIdx) AS exist";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $postIdx, $commentIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}
//존재하는 페이지 좋아요 Validation
function isExistsLikePage($pageIdx, $userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM LikePage WHERE pageIdx = $pageIdx AND userIdx=$userIdx) AS exist";

    $st = $pdo->prepare($query);
    $st->execute([$pageIdx, $userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}
//게시글 좋아요 상태
function currentPostLikeStatus($postIdx, $userIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT IF(isDeleted='Y', '좋아요 취소', '좋아요') AS status FROM LikePost where postIdx = $postIdx and userIdx = $userIdx;";
    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res[0]['status'];
}
//이미지 좋아요 상태
function currentImageLikeStatus($userIdx, $postIdx, $imageIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT IF(isDeleted='Y', '좋아요 취소', '좋아요') AS status FROM LikeImage where postIdx = $postIdx and userIdx = $userIdx and imageIdx=$imageIdx;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $postIdx, $imageIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res[0]['status'];
}
//동영상 좋아요 상태
function currentVideoLikeStatus($userIdx, $postIdx, $videoIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT IF(isDeleted='Y', '좋아요 취소', '좋아요') AS status FROM LikeVideo where postIdx = $postIdx and userIdx = $userIdx and videoIdx=$videoIdx;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $postIdx, $videoIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res[0]['status'];
}
//댓글 좋아요 상태
function currentCommentLikeStatus($userIdx, $postIdx, $commentIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT IF(isDeleted='Y', '좋아요 취소', '좋아요') AS status FROM LikeComment where postIdx = $postIdx and userIdx = $userIdx and commentIdx=$commentIdx;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $postIdx, $commentIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res[0]['status'];
}
//페이지 좋아요 상태
function currentPageLikeStatus($pageIdx, $userIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT IF(isDeleted='Y', '좋아요 취소', '좋아요') AS status FROM LikePage where pageIdx = $pageIdx and userIdx = $userIdx;";
    $st = $pdo->prepare($query);
    $st->execute([$pageIdx, $userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res[0]['status'];
}
//게시글 좋아요 등록
function registerLike($postIdx, $userIdx){
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO LikePost(postIdx, userIdx) VALUES ($postIdx, $userIdx);";
    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $userIdx]);
    $st = null;
    $pdo = null;

    return $postIdx;
}
//이미지 좋아요 등록
function registerImageLike($userIdx, $postIdx, $imageIdx){
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO LikeImage(postIdx, imageIdx, userIdx) VALUES ($postIdx, $imageIdx, $userIdx);";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $postIdx, $imageIdx]);
    $st = null;
    $pdo = null;
}
//비디오 좋아요 등록
function registerVideoLike($userIdx, $postIdx, $videoIdx){
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO LikeVideo(userIdx, postIdx, videoIdx) VALUES ($userIdx, $postIdx, $videoIdx);";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $postIdx, $videoIdx]);
    $st = null;
    $pdo = null;
}
//댓글 좋아요 등록
function registerCommentLike($userIdx, $postIdx, $commentIdx){
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO LikeComment(userIdx, postIdx, commentIdx) 
                VALUES ($userIdx, $postIdx, $commentIdx);";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $postIdx, $commentIdx]);
    $st = null;
    $pdo = null;
}
//채널 좋아요 등록
function registerPageLike($pageIdx, $userIdx){
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO LikePage(pageIdx, userIdx) VALUES ($pageIdx, $userIdx);";
    $st = $pdo->prepare($query);
    $st->execute([$pageIdx, $userIdx]);
    $st = null;
    $pdo = null;
}

//좋아요 유저 목록 조회
function likeList($postIdx, $userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select LikePost.userIdx                                        as userIdx,
       IF(isnull(profileImageUrl), 'Default', profileImageUrl) as profileImageUrl,
       userName
from LikePost
         join User on User.userIdx = LikePost.userIdx
where postIdx = $postIdx
  and LikePost.isDeleted = 'N'";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//좋아요 유저 목록 조회
function imageLikeList($postIdx, $imageIdx, $userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select LikeImage.userIdx                                        as userIdx,
       IF(isnull(profileImageUrl), 'Default', profileImageUrl) as profileImageUrl,
       userName
from LikeImage
         join User on User.userIdx = LikeImage.userIdx
where postIdx = $postIdx and imageIdx=$imageIdx
  and LikeImage.isDeleted = 'N'";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $imageIdx, $userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//동영상 좋아요 유저 목록 조회
function videoLikeList($postIdx, $videoIdx, $userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select LikeVideo.userIdx                                        as userIdx,
       IF(isnull(profileImageUrl), 'Default', profileImageUrl) as profileImageUrl,
       userName
from LikeVideo
         join User on User.userIdx = LikeVideo.userIdx
where postIdx = $postIdx and videoIdx=$videoIdx
  and LikeVideo.isDeleted = 'N'";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $videoIdx, $userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//댓글 좋아요 유저 목록 조회
function commentLikeList($postIdx, $commentIdx, $userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select LikeComment.userIdx                                        as userIdx,
       IF(isnull(profileImageUrl), 'Default', profileImageUrl) as profileImageUrl,
       userName
from LikeComment
         join User on User.userIdx = LikeComment.userIdx
where postIdx = $postIdx and commentIdx=$commentIdx
  and LikeComment.isDeleted = 'N'";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $commentIdx, $userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//채널 좋아요 유저 목록 조회
function pageLikeList($pageIdx, $userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select LikePage.userIdx                                        as userIdx,
       IF(isnull(profileImageUrl), 'Default', profileImageUrl) as profileImageUrl,
       userName
from LikePage
         join User on User.userIdx = LikePage.userIdx
where pageIdx = $pageIdx
  and LikePage.isDeleted = 'N'";

    $st = $pdo->prepare($query);
    $st->execute([$pageIdx, $userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//좋아요 개수 조회
function totalLike($postIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select postIdx, count(LikePost.userIdx) as totalLike
from LikePost
where postIdx = $postIdx and isDeleted = 'N'";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}
//이미지 좋아요 개수 조회
function totalImageLike($postIdx, $imageIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select postIdx, imageIdx, count(LikeImage.userIdx) as totalLike
from LikeImage
where postIdx = $postIdx and imageIdx=$imageIdx and isDeleted = 'N'";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $imageIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}
//동영상 좋아요 개수 조회
function totalVideoLike($postIdx, $videoIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select postIdx, videoIdx, count(LikeVideo.userIdx) as totalLike
from LikeVideo
where postIdx = $postIdx and videoIdx=$videoIdx and isDeleted = 'N'";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $videoIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}
//댓글 좋아요 개수 조회
function totalCommentLike($postIdx, $commentIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select postIdx, commentIdx, count(LikeComment.userIdx) as totalLike
from LikeComment
where postIdx = $postIdx and commentIdx=$commentIdx and isDeleted = 'N'";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $commentIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}
//페이지 좋아요 개수 조회
function totalPageLike($pageIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select pageIdx, count(LikePage.userIdx) as totalLike
from LikePage
where pageIdx = $pageIdx and isDeleted = 'N'";

    $st = $pdo->prepare($query);
    $st->execute([$pageIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}
// 댓글 등록
//function postComment($postIdx, $userIdx, $comContent, $parentIdx)
//{
//    $pdo = pdoSqlConnect();
//    $query = "INSERT INTO Comment(postIdx, userIdx, comContent, parentIdx)
//                VALUES ($postIdx, $userIdx, $comContent, $parentIdx)";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$postIdx, $userIdx, $comContent, $parentIdx]);
//
//    $st = null;
//    $pdo = null;
//
//}
//댓글 등록
function postComment($postIdx, $userIdx, $comContent, $parentIdx)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO Comment (postIdx, userIdx, comContent, parentIdx) VALUES ('".$postIdx."', '".$userIdx."', '".$comContent."', '".$parentIdx."');";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $userIdx, $comContent, $parentIdx]);

    $st = null;
    $pdo = null;

    return $postIdx;
}
//댓글 수정
function updateComment($postIdx, $userIdx, $commentIdx, $comContent)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE Comment SET comContent='".$comContent."' where userIdx = $userIdx and postIdx=$postIdx and commentIdx=$commentIdx and isDeleted='N';";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $userIdx, $commentIdx, $comContent]);

    $st = null;
    $pdo = null;

    return $postIdx;
}
//댓글 조회
function getComment($postIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select postIdx,
       Comment.userIdx,
       userName,
       commentIdx,
       IF(isnull(profileImageUrl), 'Default', profileImageUrl) as profileImageUrl,
       comContent,
       case
           when timestampdiff(hour, Comment.createdAt, current_timestamp()) < 1
               then concat(timestampdiff(minute, Comment.createdAt, current_timestamp), '분')
           when timestampdiff(hour, Comment.createdAt, current_timestamp()) < 24
               then concat(timestampdiff(hour, Comment.createdAt, current_timestamp), '시간')
           when timestampdiff(day, Comment.createdAt, current_timestamp()) < 7
               then concat(timestampdiff(day, Comment.createdAt, current_timestamp), '일')
           else date_format(Comment.createdAt, '%c월 %e일 오후 %h:%i')
           end as whenCreated
from Comment
         inner join (select userIdx, profileImageUrl, userName from User) user on user.userIdx = Comment.userIdx
where postIdx = $postIdx
  and isDeleted = 'N'
order by if((parentIdx = -1), commentIdx, parentIdx), createdAt
limit 10;";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//댓글 삭제
function deleteComment($postIdx, $commentIdx, $userIdxToken){
    $pdo = pdoSqlConnect();
    $query = "UPDATE Comment SET isDeleted='Y' where postIdx = $postIdx and commentIdx=$commentIdx and userIdx =$userIdxToken and isDeleted='N';";
    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $commentIdx, $userIdxToken]);
    $st = null;
    $pdo = null;
}
//댓글 유저인덱스 조회
function getUserIdxComment($postIdx, $commentIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT userIdx FROM Comment WHERE postIdx = $postIdx and commentIdx=$commentIdx;";
    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $commentIdx]);
        //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res[0]["userIdx"];
}
//게시물 유저인덱스 조회
function getUserIdxPost($postIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT userIdx FROM Post WHERE postIdx = $postIdx;";
    $st = $pdo->prepare($query);
    $st->execute([$postIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res[0]["userIdx"];
}

// 친구 신청
function applyFriend($userIdx, $friendUserIdx)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO FriendList (userIdx, friendUserIdx) VALUES ($userIdx, $friendUserIdx);";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $friendUserIdx]);

    $st = null;
    $pdo = null;
}
//존재하지 않은 친구신청 수신회원 Validation
function isValidExistsUser($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM User WHERE userIdx = $userIdx AND isDeleted = 'N') AS exist";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}
// 친구 신청 수락
function acceptApply($userIdx, $friendUserIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE FriendList SET request='Y' where userIdx = $userIdx and friendUserIdx=$friendUserIdx and isDeleted='N';";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $friendUserIdx]);
    $st = null;
    $pdo = null;
}
//존재하는 친구신청 리스트
function isValidExistsFriendList($userIdx, $friendUserIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM FriendList WHERE ((userIdx = $userIdx and friendUserIdx=$friendUserIdx) or (userIdx = $friendUserIdx and friendUserIdx=$userIdx)) AND isDeleted = 'N') AS exist";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $friendUserIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}
//수락한 친구신청 리스트
function isValidExistsFriendYes($userIdx, $friendUserIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM FriendList WHERE ((userIdx = $userIdx and friendUserIdx=$friendUserIdx) or (userIdx = $friendUserIdx and friendUserIdx=$userIdx)) and request='Y' AND isDeleted = 'N') AS exist";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $friendUserIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}

//거절한 친구신청 리스트
function isValidExistsFriendNo($userIdx, $friendUserIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM FriendList WHERE ((userIdx = $userIdx and friendUserIdx=$friendUserIdx) or (userIdx = $friendUserIdx and friendUserIdx=$userIdx)) and request='N' AND isDeleted = 'N') AS exist";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $friendUserIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}
// 친구 신청 거절
function rejectFriend($userIdx, $friendUserIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE FriendList SET request='N' where userIdx = $userIdx and friendUserIdx=$friendUserIdx and isDeleted='N';";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $friendUserIdx]);
    $st = null;
    $pdo = null;
}
// 친구 삭제
function deleteFriend($userIdx, $friendUserIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE FriendList SET isDeleted='Y' where userIdx = $userIdx and friendUserIdx=$friendUserIdx and isDeleted='N';";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $friendUserIdx]);
    $st = null;
    $pdo = null;
}
//친구 목록 조회
function getFriend($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select FL.userIdx, userName, IF(isnull(profileImageUrl), 'Default', profileImageUrl) as profileImageUrl
from User
         join (select case when userIdx = $userIdx then friendUserIdx when friendUserIdx = $userIdx then userIdx end as userIdx
               from FriendList
               where request = 'Y'
                 and (userIdx = $userIdx or friendUserIdx = $userIdx)) FL on FL.userIdx = User.userIdx;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//받은 친구 신청 목록 조회
function getWaitingFriend($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select FL.userIdx, userName, IF(isnull(profileImageUrl), 'Default', profileImageUrl) as profileImageUrl, whenCreated
from User
         join (select case when userIdx = 15 then friendUserIdx when friendUserIdx = 15 then userIdx end as userIdx,
                      case
                          when timestampdiff(hour, createdAt, current_timestamp()) < 1
                              then concat(timestampdiff(minute, createdAt, current_timestamp), '분')
                          when timestampdiff(hour, createdAt, current_timestamp()) < 24
                              then concat(timestampdiff(hour, createdAt, current_timestamp), '시간')
                          when timestampdiff(day, createdAt, current_timestamp()) < 7
                              then concat(timestampdiff(day, createdAt, current_timestamp), '일')
                          else date_format(createdAt, '%c월 %e일 오후 %h:%i')
                          end                                                                            as whenCreated
               from FriendList
               where request = 'W'
                 and (userIdx = $userIdx or friendUserIdx = $userIdx)) FL on FL.userIdx = User.userIdx;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

//이미지 상세조회
function getImageDetail($postIdx, $imageIdx, $userIdxToken)
{
    $pdo = pdoSqlConnect();
    $query = "select PostImage.postIdx, UserPost.userIdx, userName, imageIdx, imageUrl, IF(isnull(imageComment), 'Default', imageComment) as imageComment, 
case
           when timestampdiff(hour, createdAt, current_timestamp()) < 1
               then concat(timestampdiff(minute, createdAt, current_timestamp), '분')
           when timestampdiff(hour, createdAt, current_timestamp()) < 24
               then concat(timestampdiff(hour, createdAt, current_timestamp), '시간')
           else date_format(createdAt, '%c월 %e일 오후 %h:%i')
           end as whenCreated,
IF(exists(select * from LikeImage where postIdx = $postIdx and userIdx = $userIdxToken and imageIdx=$imageIdx and isDeleted = 'N'), 'Y', 'N') as likeStatus
from PostImage
join (select postIdx, User.userIdx, userName from User join Post P on User.userIdx = P.userIdx) UserPost on UserPost.postIdx=PostImage.postIdx
where PostImage.postIdx = $postIdx and imageIdx=$imageIdx AND isDeleted = 'N'
order by imageIdx;";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $imageIdx, $userIdxToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;

}
//동영상 상세조회 Validation
function isValidVideo($postIdx, $videoIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS (select * from PostVideo where postIdx=$postIdx and videoIdx = $videoIdx AND isDeleted = 'N') as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $videoIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}

//동영상 상세조회
function getVideoDetail($postIdx, $videoIdx, $userIdxToken)
{
    $pdo = pdoSqlConnect();
    $query = "select PostVideo.postIdx, UserPost.userIdx, userName, videoIdx, videoUrl, IF(isnull(videoComment), 'Default', videoComment) as videoComment, length,
case
           when timestampdiff(hour, createdAt, current_timestamp()) < 1
               then concat(timestampdiff(minute, createdAt, current_timestamp), '분')
           when timestampdiff(hour, createdAt, current_timestamp()) < 24
               then concat(timestampdiff(hour, createdAt, current_timestamp), '시간')
           else date_format(createdAt, '%c월 %e일 오후 %h:%i')
           end as whenCreated,
IF(exists(select * from LikeVideo where postIdx = $postIdx and userIdx = $userIdxToken and videoIdx=$videoIdx and isDeleted = 'N'), 'Y', 'N') as likeStatus
from PostVideo
join (select postIdx, User.userIdx, userName from User join Post P on User.userIdx = P.userIdx) UserPost on UserPost.postIdx=PostVideo.postIdx
where PostVideo.postIdx = $postIdx and videoIdx=$videoIdx AND isDeleted = 'N'
order by videoIdx;";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $videoIdx, $userIdxToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;

}
// 페이지 만들기
    function createPage($userIdx, $pageName, $category, $subCategory, $webUrl, $profileImageUrl, $coverImageUrl){
        $pdo = pdoSqlConnect();
        $query = "INSERT INTO Page (userIdx, pageName, categoryIdx, subCategoryIdx, web, profileImageUrl, coverImageUrl) 
                    VALUES ($userIdx, '".$pageName."', $category, $subCategory, '".$webUrl."', '".$profileImageUrl."', '".$coverImageUrl."');";

        $st = $pdo->prepare($query);
        $st->execute([$userIdx, $pageName, $category, $subCategory, $webUrl, $profileImageUrl, $coverImageUrl]);

        $st = null;
        $pdo = null;

    }

// 존재하는 페이지이름
    function isExistsPageName($pageName){
        $pdo = pdoSqlConnect();
        $query = "SELECT EXISTS(SELECT * FROM Page WHERE pageName= ? and isDeleted='N') AS exist;";


        $st = $pdo->prepare($query);
        //    $st->execute([$param,$param]);
        $st->execute([$pageName]);
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $res = $st->fetchAll();

        $st=null;$pdo = null;

        return intval($res[0]["exist"]);

    }
// 게시물 인덱스
function getPageIdx($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT pageIdx FROM Page where userIdx = ? ORDER BY createdAt DESC limit 1";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['pageIdx'];
}
// 존재하지 않은 카테고리
function isExistsCategory($category){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM PageCategory WHERE categoryIdx= ? and isDeleted='N') AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$category]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}
// 존재하지 않은 하위카테고리
function isExistsSubCategory($category, $subCategory){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM PageSubCategory WHERE categoryIdx=$category and subCategoryIdx= $subCategory and isDeleted='N') AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$category, $subCategory]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}
// 스토리 이미지 등록
    function createStoryImage($userIdxToken, $storyImageUrl, $target){
        $pdo = pdoSqlConnect();
        $query = "INSERT INTO StoryImage (userIdx, storyImageUrl, target) VALUES ($userIdxToken, $storyImageUrl, $target);";

        $st = $pdo->prepare($query);
        $st->execute([$userIdxToken, $storyImageUrl, $target]);

        $st = null;
        $pdo = null;

    }
// 스토리 이미지 텍스트 등록
function createStoryText($storyIdx, $textContent){
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO StoryText (storyIdx, storyKinds, textContent) VALUES ($storyIdx, 1, $textContent);";

    $st = $pdo->prepare($query);
    $st->execute([$storyIdx, $textContent]);

    $st = null;
    $pdo = null;

}
// 스토리 이미지 태그 등록
function createTag($userIdx, $tagUserIdx, $locationIdx){
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO tagUser (userIdx, targetUserIdx, tagLocation, locationIdx) VALUES ($userIdx, $tagUserIdx, 'storyImage', $locationIdx);";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $tagUserIdx, $locationIdx]);

    $st = null;
    $pdo = null;

}
// 존재하는 회원
    function isValidExistsUserIdx($userIdx){
        $pdo = pdoSqlConnect();
        $query = "SELECT EXISTS(SELECT * FROM User WHERE userIdx= ? and isDeleted='N') AS exist;";


        $st = $pdo->prepare($query);
        //    $st->execute([$param,$param]);
        $st->execute([$userIdx]);
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $res = $st->fetchAll();

        $st=null;$pdo = null;

        return intval($res[0]["exist"]);

    }
// 존재하는 차단 회원
function isValidExistsBlackList($userIdxToken, $blackUserIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM BlackList WHERE userIdx= $userIdxToken and blackUserIdx=$blackUserIdx and isDeleted='N') AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdxToken, $blackUserIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);
}
// 존재하는 차단 해제 회원
function isExistsBlackList($userIdxToken, $blackUserIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM BlackList WHERE userIdx= $userIdxToken and blackUserIdx=$blackUserIdx and isDeleted='Y') AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdxToken, $blackUserIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);
}
// 차단하기
function blacklist($userIdx, $blackUserIdx)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO BlackList (userIdx, blackUserIdx) VALUES ($userIdx, $blackUserIdx);";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $blackUserIdx]);

    $st = null;
    $pdo = null;
}
//차단하기 수정
function modifyBlacklist($userIdx, $blackUserIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE BlackList SET isDeleted = if(isDeleted = 'Y', 'N','Y') where blackUserIdx = $blackUserIdx and userIdx = $userIdx;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $blackUserIdx]);
    $st = null;
    $pdo = null;
}
// 존재하는 즐겨찾기 회원
function isValidExistsFavorites($userIdxToken, $favoritesUserIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Favorites WHERE userIdx= $userIdxToken and favoritesUserIdx=$favoritesUserIdx and isDeleted='N') AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdxToken, $favoritesUserIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);
}
// 존재하는 즐겨찾기 해제 회원
function isExistsFavorites($userIdxToken, $favoritesUserIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Favorites WHERE userIdx= $userIdxToken and favoritesUserIdx=$favoritesUserIdx and isDeleted='Y') AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdxToken, $favoritesUserIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);
}
//즐겨찾기 수정
function modifyFavorites($userIdx, $favoritesUserIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE Favorites SET isDeleted = if(isDeleted = 'Y', 'N','Y') where favoritesUserIdx = $favoritesUserIdx and userIdx = $userIdx;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $favoritesUserIdx]);
    $st = null;
    $pdo = null;
}
// 즐겨찾기
function Favorites($userIdx, $favoritesUserIdx)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO Favorites (userIdx, favoritesUserIdx) VALUES ($userIdx, $favoritesUserIdx);";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $favoritesUserIdx]);

    $st = null;
    $pdo = null;
}