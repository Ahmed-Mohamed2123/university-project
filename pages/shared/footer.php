    <!--    start sidebar    -->
    <div id="sidebar"  class="sidebar">
        <button onclick="closeSidebar()" class="btn-close"></button>

        <div class="logo">
            <img src="<?php echo BASEURL . 'assets/images/logo.png'?>" alt="">
        </div>

        <div class="sidebar-content">
            <?php if ($_SESSION['role'] !== NULL) { ?>
            <div class="item">
                <div class="item-content">
                    <a href="<?php echo BASEURLPAGES . 'index.php'?>" class="responsive main-item-text"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
                </div>
            </div>
            <?php } ?>

            <?php if ($_SESSION['role'] === '1') { ?>
            <div class="item">
                <div class="item-content" id="dropdownMenuSchools" data-bs-toggle="dropdown" aria-expanded="false">
                    <p class="responsive main-item-text"><i class="fa-solid fa-school"></i> Schools</p>
                    <img class="arrow" src="<?php echo ASSETS . 'images/arrow.png'?>" alt="">
                </div>

                <ul class="menu dropdown-menu w-100" aria-labelledby="dropdownMenuButton1">
                    <a href="<?php echo BASEURLPAGES . 'schools/add.php'?>">Add new school</a>
                    <a href="<?php echo BASEURLPAGES . 'schools/viewAll.php'?>">View school</a>
                </ul>
            </div>
            <?php } ?>

            <?php if ($_SESSION['role'] === '1') { ?>
            <div class="item">
                <div class="item-content" id="dropdownMenuStudents" data-bs-toggle="dropdown" aria-expanded="false">
                    <p class="responsive main-item-text"><i class="fa-solid fa-user-large"></i> parents</p>
                    <img class="arrow" src="<?php echo ASSETS . 'images/arrow.png'?>" alt="">
                </div>
                <ul class="menu dropdown-menu w-100" aria-labelledby="dropdownMenuStudents">
                    <a href="<?php echo BASEURLPAGES . 'parents/viewAll.php'?>">View parents</a>
                </ul>
            </div>
            <?php } ?>

            <?php if ($_SESSION['role'] === '1') { ?>
            <div class="item">
                <div class="item-content" id="dropdownMenuEmployees" data-bs-toggle="dropdown" aria-expanded="false">
                    <p class="responsive main-item-text"><i class="fa-solid fa-user-group"></i> Employees</p>
                    <img class="arrow" src="<?php echo ASSETS . 'images/arrow.png'?>" alt="">
                </div>
                <ul class="menu dropdown-menu w-100" aria-labelledby="dropdownMenuEmployees">
                    <a href="<?php echo BASEURLPAGES . 'Employees/viewAll.php'?>">View employees</a>
                </ul>
            </div>
            <?php } ?>

            <?php if ($_SESSION['role'] === '1') { ?>
            <div class="item">
                <div class="item-content" id="dropdownMenuResults" data-bs-toggle="dropdown" aria-expanded="false">
                    <p class="responsive main-item-text"><i class="fa-solid fa-graduation-cap"></i> Results</p>
                    <img class="arrow" src="<?php echo ASSETS . 'images/arrow.png'?>" alt="">
                </div>
                <ul class="menu dropdown-menu w-100" aria-labelledby="dropdownMenuResults">
                <a href="<?php echo BASEURLPAGES . 'results/add.php'?>">Add new result</a>
                <a href="<?php echo BASEURLPAGES . 'results/viewAll.php'?>">View results</a>
                </ul>
            </div>
            <?php } ?>

            <?php if ($_SESSION['role'] === '1') { ?>
            <div class="item">
                <div class="item-content" id="dropdownMenuSubjects" data-bs-toggle="dropdown" aria-expanded="false">
                    <p class="responsive main-item-text"><i class="fa-solid fa-book"></i> Subjects</p>
                    <img class="arrow" src="<?php echo ASSETS . 'images/arrow.png'?>" alt="">
                </div>
                <ul class="menu dropdown-menu w-100" aria-labelledby="dropdownMenuSubjects">
                    <a href="<?php echo BASEURLPAGES . 'subjects/add.php'?>">Add new subject</a>
                    <a href="<?php echo BASEURLPAGES . 'subjects/viewAll.php'?>">View subjects</a>
                </ul>
            </div>
            <?php } ?>

            <?php if ($_SESSION['role'] === '0') { ?>
            <div class="item">
                <div class="item-content" id="dropdownMenuPayments" data-bs-toggle="dropdown" aria-expanded="false">
                    <p class="responsive main-item-text"><i class="fa-solid fa-money-bill-1-wave"></i> Payments</p>
                    <img class="arrow" src="<?php echo ASSETS . 'images/arrow.png'?>" alt="">
                </div>
                <ul class="menu dropdown-menu w-100" aria-labelledby="dropdownMenuPayments">
                    <a href="<?php echo BASEURLPAGES . 'payments/viewAll.php'?>">View All payments</a>
                </ul>
            </div>
            <?php } ?>

            <?php if ($_SESSION['role'] !== NULL) { ?>
            <div class="item">
                <div class="item-content" id="dropdownMenuOrders" data-bs-toggle="dropdown" aria-expanded="false">
                    <p class="responsive main-item-text"><i class="fa-solid fa-list"></i> orders</p>
                    <img class="arrow" src="<?php echo ASSETS . 'images/arrow.png'?>" alt="">
                </div>
                <ul class="menu dropdown-menu w-100" aria-labelledby="dropdownMenuOrders">
                    <?php if ($_SESSION['role'] === '0') { ?>
                        <a href="<?php echo BASEURLPAGES . 'orders/add.php'?>">Add new order</a>
                    <?php } ?>
                    <a href="<?php echo BASEURLPAGES . 'orders/viewAll.php'?>">View orders</a>
                </ul>
            </div>
            <?php } ?>

            <?php if ($_SESSION['role'] !== NULL) { ?>
            <div class="item d-lg-none">
                <div class="item-content">
                    <a class="responsive main-item-text" href="<?php echo BASEURLPAGES . 'profile/edit.php'; ?>"><i class="fa-solid fa-circle-info"></i> View my profile</a>
                </div>
            </div>
            <?php } ?>

            <?php if ($_SESSION['role'] === NULL) { ?>
            <div class="item">
                <div class="item-content">
                    <a href="<?php echo BASEURLPAGES . 'auth/login.php'?>" class="responsive main-item-text"><i class="fa-solid fa-arrow-right-to-bracket"></i> login</a>
                </div>
            </div>
            <?php } ?>
            <?php if ($_SESSION['role'] === NULL) { ?>
            <div class="item">
                <div class="item-content">
                    <a href="<?php echo BASEURLPAGES . 'auth/register.php'?>" class="responsive main-item-text"><i class="fa-solid fa-registered"></i> register</a>
                </div>
            </div>
            <?php } ?>
            <?php if ($_SESSION['role'] === NULL) { ?>
                <div class="item">
                    <div class="item-content">
                        <a href="<?php echo BASEURLPAGES . 'info.php'?>" class="responsive main-item-text"><i class="fa-solid fa-circle-info"></i> Information</a>
                    </div>
                </div>
            <?php } ?>

            <?php if ($_SESSION['role'] !== NULL) { ?>
                <div class="item d-lg-none">
                    <div class="item-content">
                        <a class="responsive main-item-text w-100" href="<?php echo BASEURLPAGES . 'auth/logout.php'?>">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                        </a>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
    <!--    end sidebar    -->
    <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="<?php echo ASSETS . 'js/stripe.js'?>"></script>
    <script src="<?php echo ASSETS . 'js/popper.min.js'?>"></script>
    <script src="<?php echo ASSETS . 'js/bootstrap.min.js'?>"></script>
    <script src="<?php echo ASSETS . 'js/all.js'?>"></script>
    <script src="<?php echo ASSETS . 'js/sidenav.js'?>"></script>
    <script src="<?php echo ASSETS . 'js/main.js'?>"></script>
    </body>
</html>
