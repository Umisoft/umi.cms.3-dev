<!DOCTYPE html>
<html lang="en">
<head>
    {include file='partial/head.tpl'}
</head>

<body>

<div class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container">

        <div class="navbar-collapse collapse">


        </div>

    </div>
</div>

<div class="container">

    <header class="blog-header">

    </header>

    <main class="row">

        <section class="col-sm-8 blog-main">

            {$contents}

        </section>

        <aside class="col-sm-3 col-sm-offset-1 blog-sidebar">
            {include file='partial/sidebar-news.tpl'}
        </aside>

    </main>

</div>

<footer class="blog-footer">
    {widget widgetPath='structure.menu.custom' params=['menuName' => 'bottomMenu']}
    <p>
        {translate message='Back to top'}
    </p>
</footer>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="/bootstrap/js/bootstrap.min.js"></script>
<script src="/bootstrap/js/docs.min.js"></script>
</body>
</html>