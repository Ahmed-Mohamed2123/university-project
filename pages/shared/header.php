<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin panel</title>

    <link rel="stylesheet" href="<?php echo ASSETS . 'css/style.css'?>">
    <link rel="stylesheet" href="<?php echo ASSETS . 'css/auth.css'?>">
    <link rel="stylesheet" href="<?php echo ASSETS . 'css/bootstrap.min.css'?>">
    <link rel="stylesheet" href="<?php echo ASSETS . 'css/all.css'?>">
</head>
<body>

    <!--    start header  -->
    <div class="header" id="header">
        <div class="container-fluid">
            <div class="header-content">
                <div class="header-content-left">
                    <p class="mb-0 project-name">
                        <a href="<?php echo BASEURLPAGES . 'index.php'?>" class="text-decoration-none">project_name</a>
                    </p>
                </div>

                <div class="header-content-right">
                    <!-- toggle button -->
                    <span class="toggle" onclick="openSidebar()">
                                <svg style="cursor: pointer;" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1.5625 2.63462H18.4375C18.9322 2.63462 19.25 2.16174 19.25 1.69231C19.25 1.22288 18.9322 0.75 18.4375 0.75H1.5625C1.06775 0.75 0.75 1.22288 0.75 1.69231C0.75 2.16174 1.06775 2.63462 1.5625 2.63462Z" fill="black" stroke="black" stroke-width="0.5"/>
                                    <path d="M19.25 7.23089V7.23085C19.2499 6.76146 18.9322 6.28857 18.4375 6.28857H6.625C6.13025 6.28857 5.8125 6.76146 5.8125 7.23089C5.8125 7.70031 6.13025 8.1732 6.625 8.1732H18.4375C18.9323 8.1732 19.25 7.70031 19.25 7.23089Z" fill="black" stroke="black" stroke-width="0.5"/>
                                    <path d="M18.4375 11.8269H1.5625C1.06775 11.8269 0.75 12.2998 0.75 12.7692C0.75 13.2386 1.06775 13.7115 1.5625 13.7115H18.4375C18.9322 13.7115 19.25 13.2386 19.25 12.7692C19.25 12.2998 18.9322 11.8269 18.4375 11.8269Z" fill="black" stroke="black" stroke-width="0.5"/>
                                    <path d="M18.4375 17.3655H10.8437C10.349 17.3655 10.0312 17.8384 10.0312 18.3078C10.0312 18.7772 10.349 19.2501 10.8437 19.2501H18.4375C18.9322 19.2501 19.25 18.7772 19.25 18.3078C19.25 17.8384 18.9322 17.3655 18.4375 17.3655Z" fill="black" stroke="black" stroke-width="0.5"/>
                                </svg>
                            </span>

                    <div class="options">
                        <!-- profile options -->
                        <?php if ($_SESSION['role'] !== NULL) { ?>
                        <div class="profile-info d-flex align-items-center pointer-event" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <p class="username"><?php echo $_SESSION['username']?></p>
                            <img src="<?php echo ASSETS . 'images/curve-down-arrow.png'?>" alt="">
                        </div>
                        <?php } ?>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="<?php echo BASEURLPAGES . 'profile/edit.php'; ?>">View my profile</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASEURLPAGES.'auth/logout.php';?>"><button class="btn btn-danger w-100">Logout</button></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--    end header    -->