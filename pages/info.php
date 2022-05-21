<?php
    require '../config.php';
    require BLP . 'shared/header.php';
?>

<!--  start main    -->
<div class="main" id="main">
    <div class="container-fluid">
        <div class="content-info text-end">


<!--            <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">-->
<!--                <div class="carousel-inner">-->
<!--                    <div class="carousel-item active" data-bs-interval="10000">-->
<!--                        <img src="--><?php //echo ASSETS . 'images/info.jpg';?><!--" class="d-block w-100" alt="...">-->
<!--                        <div class="carousel-caption d-none d-md-block text-black">-->
<!--                            <h5>First slide label</h5>-->
<!--                            <p>Some representative placeholder content for the first slide.</p>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="carousel-item" data-bs-interval="2000">-->
<!--                        <img src="--><?php //echo ASSETS . 'images/info.jpg';?><!--" class="d-block w-100" alt="...">-->
<!--                        <div class="carousel-caption d-none d-md-block text-black">-->
<!--                            <h5>Second slide label</h5>-->
<!--                            <p>Some representative placeholder content for the second slide.</p>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="carousel-item">-->
<!--                        <img src="--><?php //echo ASSETS . 'images/info.jpg';?><!--" class="d-block w-100" alt="...">-->
<!--                        <div class="carousel-caption d-none d-md-block text-black">-->
<!--                            <h5>Third slide label</h5>-->
<!--                            <p>Some representative placeholder content for the third slide.</p>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">-->
<!--                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>-->
<!--                    <span class="visually-hidden">Previous</span>-->
<!--                </button>-->
<!--                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">-->
<!--                    <span class="carousel-control-next-icon" aria-hidden="true"></span>-->
<!--                    <span class="visually-hidden">Next</span>-->
<!--                </button>-->
<!--            </div>-->

            <div class="content-text">
                <p class="">
                    بسبب الظروف التي يمر بها العالم في هذه الآونة الأخيرة وما تعانيه كل القطاعات متأثرة بتداعيات فيروس كورونا المستجد COVID-19، وهنا نخص بالذكر المجال الادارى التعليمى على وجه الخصوص، بات جليا لدى المؤسسات التعليمية أن توجد بدائل الطلبات التقليديه؛
                    هذا ما دفع العديد من المؤسسات في جميع أنحاء العالم بشكل عام وفي منطقة الشرق الأوسط بشكل خاص أن تتجه لاعتماد منصات حتى تستطيع أن تؤدي خدمة انشاء الطلبات بمنظومة الاداره التعليميه
                </p>
<!---->
                <p>اذا كنت تريد الدخول للنظام برجاه الضغط على <a href="<?php echo BASEURLPAGES . 'auth/login.php'?>">تسجيل الدخول</a></p>
            </div>
            <div class="content-img">
                <img src="<?php echo ASSETS . 'images/0545a5aa-35e7-4b15-9812-3cf273165d7d.jpeg';?>" alt="">
            </div>
            <hr>
            <div class="feature">
                <h3 class="text-end fw-bold mb-3">مميزات النظام</h3>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="content one d-flex align-items-center justify-content-center">
                            يعمل النظام طبقا للمواصفات القياسية للعمل الإلكتروني
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="content two d-flex align-items-center justify-content-center">
                            استخراج تقرير لولي الأمر لنتيجه الطالب
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="content three d-flex align-items-center justify-content-center">
                            العمل على بيئة الإنترنت مما ييسر العمل من أى مكان
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="content four d-flex align-items-center justify-content-center">
                            معرفه المدفوعات اللذين قامو بها كافه أولياء الأمور
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

</div>
<!--  End main    -->
<?php
    require BLP . 'shared/footer.php';
?>