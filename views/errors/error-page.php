<style>
    .centered {
        display: grid;
        place-items: center;
        place-content: center;
        height: 100vh;
    }

    body {
        background-color: #1e1e1e;
        color: #fff;
        font-family: 'Roboto', sans-serif;
    }

    h1 {
        font-size: 2.5rem;
        text-transform: capitalize;
        text-shadow: 0 0 25px lightskyblue;
    }

    h4.code {
        font-size: 8rem;
        margin-bottom: 25px;
        text-transform: capitalize;
    }
</style>

<?php
$code =  $error->getCode();
$code = $code != 0 ? $code : 500;
?>
<main class="centered">
    <div class="w1">
        <?php if (isset($error)) : ?>
            <h4 class="w1 code tcenter"><?= $code; ?></h4>
            <h1><?= $error->getMessage() ?></h1>
        <?php endif; ?>

        <?php if (!empty(error_get_last())) : ?>
            <pre>
                <?php print_r(error_get_last()); ?>
            </pre>
        <?php endif; ?>
    </div>
</main>