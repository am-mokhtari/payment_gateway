<?php

use Illuminate\Support\Facades\Session;

if (Session::has('success')) { ?>
    <div class="p-2 m-2 bg-lime-200 text-lime-600 border rounded">
        <h5 class="h5"><?= Session::get('success') ?></h5>
    </div>
    <?php
}
if (Session::has('warning')) { ?>
    <div class="p-2 m-2 bg-yellow-200 text-yellow-700 border rounded">
        <h5 class="h5"><?= Session::get('warning') ?></h5>
    </div>
    <?php
}
if (Session::has('danger')) { ?>
    <div class="p-2 m-2 bg-red-200 text-red-700 border rounded">
        <h5 class="h5"><?= Session::get('danger') ?></h5>
    </div>
    <?php
}
if (Session::has('message')) { ?>
    <div class="p-2 m-2 bg-teal-100 text-teal-600 border rounded">
        <h5 class="h5"><?= Session::get('message') ?></h5>
    </div>
    <?php
}
if ($errors->any()) {
    foreach ($errors->all() as $error) {
        ?>
        <div class="p-2 m-2 bg-red-200 text-red-700 border rounded">
            <h5 class="h5">
                <?php
                echo $error;
                ?>
            </h5>
        </div>
        <?php
    }
}

if (isset($exception)) {
    ?>
    <div class="p-2 m-2 bg-teal-100 text-teal-600 border rounded">
        <h5 class="h5"><?= $exception->getMessage() ?></h5>
    </div>
    <?php
}
?>
