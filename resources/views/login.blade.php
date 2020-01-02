<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
</head>
<body>
    帳號:<input id="account" type="text">
    密碼: <input id="password" type="password">
    <button onclick="login()">按鈕</button>
</body>
<script>
function login() {
    const data = {
        account:$('#account').val(),
        password:$('#password').val(),
    }
    $.ajax({
        url: "/api/login",
        method:"POST",
        data:data,
        success: function( result ) {
            window.location="/home"
        }
    });
}
</script>
</html>