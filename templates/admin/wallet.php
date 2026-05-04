<div>
    <div class="card">
        <h3>Add Funds to User Wallet</h3>
        <form action="/admin/wallet/add-funds" method="POST">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-group"><label>User ID</label><input type="number" name="user_id" class="form-control" required></div>
                <div class="form-group"><label>Amount</label><input type="number" name="amount" class="form-control" step="0.01" required></div>
                <div class="form-group"><label>Description</label><input type="text" name="description" class="form-control" value="Admin credit"></div>
            </div>
            <button class="btn btn-primary">Add Funds</button>
        </form>
    </div>
</div>
