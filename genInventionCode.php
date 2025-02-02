<?php
function make_coupon_card() {
    $code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $rand = $code[rand(0,25)]
        .strtoupper(dechex(date('m')))
        .date('d').substr(time(),-5)
        .substr(microtime(),2,5)
        .sprintf('%02d',rand(0,99));
    for(
        $a = md5( $rand, true ),
        $s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',
        $d = '',
        $f = 0;
        $f < 8;
        $g = ord( $a[ $f ] ),
        $d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],
        $f++
    );
    return $d;
}
session_start();
if(isset($_SESSION["admin"])&&$_SESSION["admin"]==true){
    try{
        require 'PDOconnection.php';
        $conn=connection();
        $conn->beginTransaction();
        $code=make_coupon_card();
        $stmt=$conn->prepare("insert into invention_code(code) values(:code)");
        $stmt->bindParam(":code",$code);
        $stmt->execute();
        $conn->commit();
        echo "success";
    }
    catch (PDOException $e){
        $conn->rollBack();
        echo "fail";
    }
}else{
    Header("refresh:0;url=superAdminLogin.php");
}

?>
