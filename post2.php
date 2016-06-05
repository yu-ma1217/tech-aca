<html>
<head>
    <title>
        計算結果
    </title>
</head>
<body>
<?php
$left = $_POST['left'];
$right = $_POST['right'];
$operator = $_POST['operator'];
$errormsg = 'エラーです';

//is_numeric で　数値かどうかを判定
//isset で　operatorに何か入っているか判定
if (is_numeric($left) && is_numeric($right) && isset($operator)) {

    switch ($_POST['operator']) {
        case '+':
            $answer = $left + $right;
            break;
        case '-':
            $answer = $left - $right;
            break;
        case '×':
            $answer = $left * $right;
            break;
        case '÷':
            if ($right == 0) {
                $answer = 'error';
                break;
            } else {
                $answer = $left / $right;
                break;
            }
    }
    if (is_numeric($answer)){
        print $_POST['left'];
        print $_POST['operator'];
        print $_POST['right'];
        print '=';
        print $answer;
    }else{
        print '0で割ってはいけません';
    }

}else{
    print $errormsg;
}
?>
</body>
</html>
