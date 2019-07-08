<nav class="navbar navbar-expand-lg navbar navbar-dark bg-dark top-fixed"  id="mainNav">
    <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">
            {# <img src="../public/img/mypic{{session.user_id}}.png" class="figure-img img-fluid rounded-circle" style="width:50px;" alt="photo"> #}
            
           
        </a>
        <a href="https://openclassrooms.facebook.com/profile.php?id=100030679643516" target="_blank"><i class="fa fa-facebook mr-4 " aria-hidden="true"></i></a>
        <a href="#t" target="_blank"><i class="fa fa-twitter mr-4 " aria-hidden="true"></i></a>
        <a href="https://github.com/samirdev2019" target="_blank"><i class="fa fa-github mr-4 " aria-hidden="true"></i></a>
        <a href="www.linkedin.com/in/samir-allab74000" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
        <div class="badge badge-primary text-wrap ml-3" style="width: 8rem;">
            Bienvenue&nbsp;{{session.username}}
        </div>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto my-2 my-lg-0">
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="../public/index.php?p=home"><i class="fa fa-home" aria-hidden="true"></i>&nbsp;Acceuil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="../public/index.php?p=posts"><i class="fa fa-clipboard" aria-hidden="true"></i>&nbsp;Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="../public/index.php?p=home#contact"><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="../public/index.php?p=backToAcount"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;Mon compte</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="../public/index.php?p=logout"><i class="fa fa-user-times" aria-hidden="true"></i>&nbsp;déconneter</a>
                </li>
                
                
            </ul>
        </div>
    </div>
</nav>