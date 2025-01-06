<?php
// Oracle 데이터베이스 연결 설정
$username = "DB502_PROJ_G4"; // 사용자명
$password = "1234"; // 비밀번호
$tns = "
(DESCRIPTION =
  (ADDRESS = (PROTOCOL = TCP)(HOST = 203.249.87.57)(PORT = 1521))
  (CONNECT_DATA =
    (SERVICE_NAME = orcl)
  )
)";


$conn = oci_connect($username, $password, $tns, 'AL32UTF8');

if (!$conn) {
  $e = oci_error();
  die("Database connection failed: " . htmlspecialchars($e['message']));
}


//실제 점검 완료 시 주석 처리
else {
    echo "";
}
?>
