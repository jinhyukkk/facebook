# API 명세서

<table>

Method | URI | Description 
  |---|:---:|---:|
  GET	|/users	|유저목록조회API
GET	|/users/{userIdx}	|유저프로필 조회 API
POST|/user	|회원가입API
DELETE	|/user/{userIdx}	|회원 탈퇴 API
PATCH	|/user/{userIdx}	|회원정보 수정 API
GET	|/post/{postIdx}	|게시물 상세조회API
GET	|/post	|게시물 전체조회API
POST	|/post	|게시물 등록 API
PATCH	|/post	|게시물 내용 글 수정 API
DELETE	|/post/{postIdx}	|게시물 삭제 API
POST	|/post/{postIdx}/like	|게시물 좋아요 등록/취소 API
GET	|/post/{postIdx}/like	|게시물 좋아요 유저 목록 조회 API
POST	|/comment	|댓글 등록 API
GET	|/post/{postIdx}/comment	|댓글 조회 API
DELETE	|/post/{postIdx}/comment/{commentIdx}	|댓글 삭제 API
PATCH	|/comment	|댓글 수정 API
POST	|/friend	|친구 신청 API
PATCH	|/waitingFriend   |친구 신청 수락/거절 API
DELETE	|/friend	|친구 삭제 API
GET	|/waitingFriend	|친구 신청 목록 API
GET	|/friend	|내 친구 목록 API
GET	|/post/{postIdx}/image/{imageIdx}	|이미지 상세조회 API
GET	|/post/{postIdx}/image/{imageIdx}/like	|이미지 좋아요 유저 목록 조회 API
POST	|/post/{postIdx}/image/{imageIdx}/like	|이미지 좋아요 등록/취소 API
POST	|/post/{postIdx}/video/{videoIdx}/like	|동영상 좋아요 등록/취소 API
GET	|/post/{postIdx}/video/{videoIdx}	|동영상 상세조회 API
GET	|/post/{postIdx}/video/{videoIdx}/like	|동영상 좋아요 유저 목록 조회 API
POST	|/post/{postIdx}/comment/{commentIdx}/like	|댓글 좋아요 등록/취소 API
POST	|/page	|페이지 만들기 API
POST	|/page/{pageIdx}/like	|페이지 좋아요 등록/취소API
POST	|/blacklist	|차단하기 API
DELETE	|/blacklist	|차단하기 해제 API
GET	|/post/{postIdx}/comment/{commentIdx}/like	|댓글 좋아요 유저 목록 조회 API
GET	|/page/{pageIdx}/like	|페이지 좋아요 유저 목록 조회 API
POST	|/favorites|	즐겨찾기 API
POST	|/story	|스토리 등록
