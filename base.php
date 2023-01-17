<?php
// $dsn = "mysql:host=localhost;charset=utf8;dbname=survey";
$dsn = "mysql:host=localhost;charset=utf8;dbname=s1110401";
$pdo = new PDO($dsn, 's1110401', 's1110401');
date_default_timezone_set("Asia/Taipei");
session_start();

function dd($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

function all($table, ...$args)
{
    //不用global就拿不到外層的變數，因為function的變數與外界會區隔
    global $pdo;
    $sql = "select * from `$table` ";
    if (isset($args[0])) {
        if (is_array($args[0])) {
            foreach ($args[0] as $key => $value) {
                $tmp[] = "`$key`='$value'";
            }
            $sql = $sql . "WHERE" . join("&&", $tmp);
        } else {
            //表示args是一個字串時
            $sql = $sql . $args[0];
        }
    }
    //$args[1]用於有order by or limit
    if (isset($args[1])) {
        $sql = $sql . $args[1];
    }
    // dd($args[0]);
    // echo $sql;
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

//尋找指定id資料
function find($table, $id, ...$args)
{
    global $pdo;
    $sql = "select * from `$table`";
    if (is_array($id)) {
        foreach ($id as $key => $value) {
            $tmp[] = "`$key`='$value'";
        }
        $sql = $sql . "WHERE" . join("&&", $tmp);
    } else {
        $sql = "select * from $table WHERE `id`='$id'";
    }
    //我自己加的，在有order by與limit的狀況下可以透過打字控制
    if (isset($args[0])) {
        $sql = $sql . $args[0];
    }
    // echo $sql;
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function update($table, $col, ...$args)
{
    global $pdo;
    $sql = "update `$table` set ";
    if (is_array($col)) {
        foreach ($col as $key => $value) {
            $tmp[] = "`$key`='$value'";
        }
        $sql = $sql . join(",", $tmp);
    } else {
        $sql = "錯誤請輸入正確的格式(陣列)";
    }

    if (is_array($args[0])) {
        foreach ($args[0] as $key => $value) {
            $tmp2[] = "`$key`='$value'";
        }
        $sql = $sql . "WHERE" . join("&&", $tmp2);
    } else {
        $sql = $sql .  "WHERE `id`='$args[0]'";
    }

    echo $sql;
    //$t=$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    //$t=$pdo->exec($sql);
    //echo$t;
    return $pdo->exec($sql);
}


function insert($table, $cols)
{
    global $pdo;
    $sql = "INSERT INTO $table";
    $keys = array_keys($cols);
    $sql = "insert into $table (`" . join("`,`", $keys) . "`) values('" . join("','", $cols) . "')";
    echo $sql;
    //$t=$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    //$t=$pdo->exec($sql);
    return $pdo->exec($sql);
}

function del($table, $id)
{
    global $pdo;
    $sql = "DELETE FROM`$table`";
    if (is_array($id)) {
        foreach ($id as $key => $value) {
            $tmp[] = "`$key`='$value'";
        }
        $sql = $sql . "WHERE " . join("&&", $tmp);
    } else {
        $sql = $sql . "WHERE `id`='$id'";
    }



    return $pdo->exec($sql);
}

function q($sql)
{
    global $pdo;
    //echo $sql;
    return $pdo->query($sql)->fetchAll();
}

function to($place)
{
    header("location:$place");
}
