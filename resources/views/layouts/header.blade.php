<!DOCTYPE HMTL>
<html>
<head>
    <title>Allegria - Backoffice</title>
    <!-- META -->
    <meta charset="utf-8">
    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/custom.css" />
    <link rel="icon" href="images/icon.png">
    <!-- JS -->
    <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
</head>
<body>
    <div class="header2">
        <div class="navigation2">
            <a href="home" class="logo2">Allegria - Personeelsvereniging Da Vinci College</a>
            <ul class="navigation2left">
            @if($user = Auth::guest())
                <li>
                    <a href="home">Home</a>
                </li>
            @elseif($user = Auth::user()->user_account_type <= 2)
                <li>
                    <a href="account">Account</a>
                </li>
                <li>
                    <a href="activities">Activiteiten</a>
                </li>
            @elseif($user = Auth::user()->user_account_type >= 3)
                <li>
                    <a href="account">Account</a>
                </li>
                <li>
                    <a href="activities">Activiteiten</a>
                </li>
                <li>
                    <a href="members">Leden</a>
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
                    <a href="logout">Uitloggen</a>
                </li>

            @endif
            </ul>
        </div>
    </div>

<div class="wrapper">
