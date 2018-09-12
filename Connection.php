<?php
error_reporting(E_ERROR | E_PARSE);
$host        = "host = 127.0.0.1";
$port        = "port = 5432";
$dbname      = "dbname = postgres";
$credentials = "user = postgres password=root";

$db = pg_connect("$host $port $dbname $credentials");

if ($db) {
    echo "connection success";
} else {
    echo "connection failed";
}

if ($_POST) {
    $data = [
        'nama' => $_POST['nama'],
        'email' => $_POST['email']
    ];
    if (!empty($_POST['id'])) {
        $condition = [
            'id' => $_POST['id']
        ];
        $state = pg_update($db,'users', $data, $condition);
    } else {
        $state = pg_insert($db,'users', $data);
    }

    if ($state) {
        echo "success";
    } else {
        echo "failed";
    }
}

if ($_GET && $_GET['action'] == 'edit') {
    $query = pg_query($db, "select * from users where id = {$_GET['id']}");
    $data_edit = pg_fetch_all($query);

} else if ($_GET && $_GET['action'] == 'delete') {
    $query = pg_delete($db, 'users', ['id' => $_GET['id']]);
}

$fetch_data = pg_query($db, 'select * from users order by nama');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
<div class="col-sm-4">

<form action="connection.php" method="post">
    <?php
    if (!empty($data_edit)):
        foreach ($data_edit as $val):
        ?>
    <input type="hidden" name="id" value="<?= $val['id']?>" >
    <div class="form-group">
        <label for="exampleFormControlInput1">Nama</label>
        <input class="form-control" type="text" name="nama" placeholder="nama" value="<?= $val['nama']?>">
    </div>
    <div class="form-group">
        <label for="exampleFormControlInput1">Nama</label>
        <input class="form-control" type="text" name="email" placeholder="email" value="<?= $val['email']?>">
    </div>
        <?php endforeach; else:?>
    <div class="form-group">
        <label for="exampleFormControlInput1">Nama</label>
        <input class="form-control form-control-sm" type="text" name="nama" placeholder="nama">
    </div>
    <div class="form-group">
        <label for="exampleFormControlInput1">Email address</label>
        <input class="form-control form-control-sm" type="text" name="email" placeholder="email">
    </div>
    <?php endif;?>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

</div>

<table>

        <tr>
            <th>No</th>
            <th>nama</th>
            <th>email</th>
            <th>action</th>
        </tr>

    <?php
        $i = 1;
        $data = pg_fetch_all($fetch_data);
        foreach($data as $row):
    ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= $row['nama']?></td>
            <td><?= $row['email']?></td>
            <td>
                <a href="connection.php?id=<?= $row['id']?>&action=edit">Edit</a>
                <a href="connection.php?id=<?= $row['id']?>&action=delete">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
