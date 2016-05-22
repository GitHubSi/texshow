<head>
    <link rel="stylesheet" href="/resource/css/resp_index.css"/>
    <link href='http://fonts.googleapis.com/css?family=Oleo+Script' rel='stylesheet' type='text/css'>
    <script src="http://libs.baidu.com/jquery/1.7.0/jquery.min.js"></script>
</head>
<body>
<div class="lg-container">
    <h1>Admin Area</h1>

    <form action="/response/login" id="lg-form" name="lg-form" method="post">

        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" placeholder="username"/>
        </div>

        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="password"/>
        </div>

        <div>
            <button type="submit" id="login">Login</button>
        </div>

    </form>
    <div id="message"></div>
</div>
</body>
