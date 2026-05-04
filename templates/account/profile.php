<div class="account-page">
    <div class="container">
        <h2><?= __('general.profile') ?></h2>
        <div class="card">
            <h3>Update Profile</h3>
            <form action="/account/profile" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="form-row">
                    <div class="form-group"><label>Name</label><input type="text" name="name" class="form-control" value="<?= e($user['name'] ?? '') ?>" required></div>
                    <div class="form-group"><label>Email</label><input type="email" class="form-control" value="<?= e($user['email'] ?? '') ?>" disabled></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Phone</label><input type="text" name="phone" class="form-control" value="<?= e($user['phone'] ?? '') ?>"></div>
                    <div class="form-group"><label>Company</label><input type="text" name="company" class="form-control" value="<?= e($user['company'] ?? '') ?>"></div>
                </div>
                <div class="form-group"><label>Address</label><input type="text" name="address" class="form-control" value="<?= e($user['address'] ?? '') ?>"></div>
                <div class="form-row">
                    <div class="form-group"><label>City</label><input type="text" name="city" class="form-control" value="<?= e($user['city'] ?? '') ?>"></div>
                    <div class="form-group"><label>State</label><input type="text" name="state" class="form-control" value="<?= e($user['state'] ?? '') ?>"></div>
                    <div class="form-group"><label>Country</label><input type="text" name="country" class="form-control" value="<?= e($user['country'] ?? '') ?>"></div>
                    <div class="form-group"><label>ZIP</label><input type="text" name="zip_code" class="form-control" value="<?= e($user['zip_code'] ?? '') ?>"></div>
                </div>
                <div class="form-group"><label>Avatar</label><input type="file" name="avatar" class="form-control" accept="image/*"></div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
        <div class="card">
            <h3>Change Password</h3>
            <form action="/account/change-password" method="POST">
                <?= csrf_field() ?>
                <div class="form-group"><label>Current Password</label><input type="password" name="current_password" class="form-control" required></div>
                <div class="form-group"><label>New Password</label><input type="password" name="password" class="form-control" required></div>
                <div class="form-group"><label>Confirm Password</label><input type="password" name="password_confirmation" class="form-control" required></div>
                <button type="submit" class="btn btn-primary">Change Password</button>
            </form>
        </div>
    </div>
</div>
