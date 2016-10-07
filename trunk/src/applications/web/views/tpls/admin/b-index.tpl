<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>管理后台</title>
    <!--                       CSS                       -->
    <!-- Reset Stylesheet -->
    <link rel="stylesheet" href="/resource/css/reset.css" type="text/css" media="screen"/>
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="/resource/css/style.css" type="text/css" media="screen"/>
    <!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid -->
    <link rel="stylesheet" href="/resource/css/invalid.css" type="text/css" media="screen"/>

    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Oleo+Script' type='text/css'/>
    <link rel="stylesheet" href="/resource/jsoneditor/dist/jsoneditor.css" type="text/css">
    <link rel="stylesheet" href="/resource/css/frame.css" type="text/css">
    <!--                       Javascripts                       -->
    <!-- jQuery -->
    <script type="text/javascript" src="/resource/scripts/jquery-1.3.2.min.js"></script>
    <!-- jQuery Configuration -->
    <script type="text/javascript" src="/resource/scripts/simpla.jquery.configuration.js"></script>
    <!-- Facebox jQuery Plugin -->
    <script type="text/javascript" src="/resource/scripts/facebox.js"></script>
    <!-- jQuery WYSIWYG Plugin -->
    <script type="text/javascript" src="/resource/scripts/jquery.wysiwyg.js"></script>
    <!-- jQuery Datepicker Plugin -->
    <script type="text/javascript" src="/resource/scripts/jquery.datePicker.js"></script>
    <script type="text/javascript" src="/resource/scripts/jquery.date.js"></script>

    <script type="text/javascript" src="/resource/jsoneditor/dist/jsoneditor.js"></script>
</head>
<body>
<div id="body-wrapper">
    <!-- Wrapper for the radial gradient background -->
    <div id="sidebar">
        <div id="sidebar-wrapper">
            <!-- Sidebar with logo and menu -->
            <h1 id="sidebar-title"><a href="#">管理后台</a></h1>
            <!-- Logo (221px wide) -->
            <a href="#"><img id="logo" src="/resource/images/logo.png" alt="Simpla Admin logo"/></a>
            <!-- Sidebar Profile links -->
            <div id="profile-links"><a href="#" title="Sign Out">退出</a></div>
            <ul id="main-nav">
                <!-- Accordion Menu -->
                <li><a href="#/" class="nav-top-item no-submenu">
                        <!-- Add the class "no-submenu" to menu items with no sub menu -->
                        Dashboard </a></li>
                <li><a href="#" class="nav-top-item">
                        <!-- Add the class "current" to current menu item -->
                        基本功能 </a>
                    <ul>
                        <li><a href="/response/detail?type=magazine" {%if $action eq "magazine"%}class="current"{%/if%}>订阅号回复</a></li>
                        <li><a href="/response/detail?type=client" {%if $action eq "client"%}class="current"{%/if%}>服务号回复</a></li>
                        <!-- Add class "current" to sub menu items also -->
                        <li><a href="/redPacketSetting/index" {%if $action eq "redset"%}class="current"{%/if%}>红包配置</a></li>
                    </ul>
                </li>
                <li><a href="#" class="nav-top-item">一元夺宝</a>
                    <ul>
                        <li><a href="#">活动抽奖</a></li>
                        <li><a href="#">配置夺宝</a></li>
                    </ul>
                </li>

                <li><a href="#" class="nav-top-item"> Image Gallery </a>
                    <ul>
                        <li><a href="#">Upload Images</a></li>
                        <li><a href="#">Manage Galleries</a></li>
                        <li><a href="#">Manage Albums</a></li>
                        <li><a href="#">Gallery Settings</a></li>
                    </ul>
                </li>
                <li><a href="#" class="nav-top-item"> Events Calendar </a>
                    <ul>
                        <li><a href="#">Calendar Overview</a></li>
                        <li><a href="#">Add a new Event</a></li>
                        <li><a href="#">Calendar Settings</a></li>
                    </ul>
                </li>
                <li><a href="#" class="nav-top-item"> Settings </a>
                    <ul>
                        <li><a href="#">General</a></li>
                        <li><a href="#">Design</a></li>
                        <li><a href="#">Your Profile</a></li>
                        <li><a href="#">Users and Permissions</a></li>
                    </ul>
                </li>
            </ul>
            <!-- End #main-nav -->
            <div id="messages" style="display: none">
                <!-- Messages are shown when a link with these attributes are clicked: href="#messages" rel="modal"  -->
                <h3>3 Messages</h3>

                <p><strong>17th May 2009</strong> by Admin<br/>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet
                    congue.
                    <small><a href="#" class="remove-link" title="Remove message">Remove</a></small>
                </p>
                <p><strong>2nd May 2009</strong> by Jane Doe<br/>
                    Ut a est eget ligula molestie gravida. Curabitur massa. Donec eleifend, libero at sagittis mollis,
                    tellus est malesuada tellus, at luctus turpis elit sit amet quam. Vivamus pretium ornare est.
                    <small><a href="#" class="remove-link" title="Remove message">Remove</a></small>
                </p>
                <p><strong>25th April 2009</strong> by Admin<br/>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet
                    congue.
                    <small><a href="#" class="remove-link" title="Remove message">Remove</a></small>
                </p>
                <form action="#" method="post">
                    <h4>New Message</h4>
                    <fieldset>
                        <textarea class="textarea" name="textfield" cols="79" rows="5"></textarea>
                    </fieldset>
                    <fieldset>
                        <select name="dropdown" class="small-input">
                            <option value="option1">Send to...</option>
                            <option value="option2">Everyone</option>
                            <option value="option3">Admin</option>
                            <option value="option4">Jane Doe</option>
                        </select>
                        <input class="button" type="submit" value="Send"/>
                    </fieldset>
                </form>
            </div>
            <!-- End #messages -->
        </div>
    </div>
    <!-- End #sidebar -->
    <div id="main-content">
        <!-- Main Content Section with everything -->
        {%include file=$tpl%}
    </div>
    <!-- End #main-content -->
</div>
</body>
<!-- Download From www.exet.tk-->
</html>
