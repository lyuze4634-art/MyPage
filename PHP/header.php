<?php
// header.php
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>My Personal Homepage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body{
            font-family: Arial, sans-serif;
            margin:0;
            background:#f5f5f5;
        }

        header{
            background:#333;
            color:white;
            padding:15px 30px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        header a{
            color:white;
            text-decoration:none;
            margin-left:20px;
        }

        header a:hover{
            text-decoration:underline;
        }

    </style>
</head>

<body>

<header>
    <div>
        <strong>My Portfolio</strong>
    </div>

    <nav>
        <a href="index.php">Home</a>
        <a href="admin_login.php">Admin</a>
    </nav>
</header>

<div class="container"></div>