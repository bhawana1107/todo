    <?php if (!empty($errors)) : ?>
        <?php foreach ($errors as $error) : ?>
            <div class="alert alert-danger" role="alert">
                <strong>Error!</strong> <?= $error ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
  