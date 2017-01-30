<!DOCTYPE HMTL>
<html>
<head>
    <title>Allegria - Backoffice</title>
    <!-- META -->
    <meta charset="utf-8">
    <!-- CSS -->
    <link rel="stylesheet" href="http://localhost/AllegriaV2/public/css/style.css" />
    <link rel="stylesheet" href="http://localhost/AllegriaV2/public/css/custom.css" />
    <link rel="icon" href="http://localhost/AllegriaV2/public/images/icon.png">
    <!-- JS -->
    <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
</head>
<body>
    <div class="header2">
        <div class="navigation2">
            <a href="http://localhost/AllegriaV2/public/home" class="logo2">Allegria - Personeelsvereniging Da Vinci College</a>
            <ul class="navigation2left">
            @if($user = Auth::guest())
                <li>
                    <a href="http://localhost/AllegriaV2/public/home">Home</a>
                </li> 
            @elseif($user = Auth::user()->user_account_type <= 2)
                <li>
                    <a href="http://localhost/AllegriaV2/public/account">Account</a>
                </li>
                <li>
                    <a href="http://localhost/AllegriaV2/public/activities">Activiteiten</a>
                </li>
            @elseif($user = Auth::user()->user_account_type >= 3)
                <li>
                    <a href="http://localhost/AllegriaV2/public/account">Account</a>
                </li>
                <li>
                    <a href="http://localhost/AllegriaV2/public/activities">Activiteiten</a>
                </li>
                <li>
                    <a href="http://localhost/AllegriaV2/public/members">Leden</a>
                </li>
            @endif
            </ul>
            <!-- my account -->
            <ul class="navigation2right">
            @if($user = Auth::guest())
            @else
                <li class="loggedin">
                    Ingelogd als:<strong>
                    @if(auth::user()->user_account_type == 1)
                    (Lid)
                    @elseif(auth::user()->user_account_type == 2)
                    (Bestuur)
                    @elseif(auth::user()->user_account_type == 3)
                    (Admin)
                    @elseif(auth::user()->user_account_type == 4)
                    (Super Admin)
                    @endif
                    </strong>
                </li>

                <li>
                    <a href="http://localhost/AllegriaV2/public/logout">Uitloggen</a>
                </li>
            
            @endif
            </ul>
        </div>
    </div>

<div class="wrapper">