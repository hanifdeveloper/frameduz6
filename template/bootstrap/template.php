<!DOCTYPE html>
<html lang="en">
    <head>
    <!-- Title -->
    <title><?= $this->web_title; ?></title>
    <base href="<?= $basePath; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta charset="UTF-8">
    <meta name="description" content="<?= $this->web_description; ?>" />
    <meta name="keywords" content="<?= $this->web_keywords; ?>" />
    <meta name="author" content="<?= $this->web_author; ?>" />
    <!-- <link rel="shortcut icon" href="assets/images/favicon.ico"> -->
    <!-- CSS -->
    <?= $cssPath; ?>
    </head>
    <?php require_once $viewPath; ?>
</html>