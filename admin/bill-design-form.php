<?php $editing = !empty($design['id']); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $editing ? 'Edit' : 'Add' ?> Bill Design</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="admin-content bill-design-page">
  <h1 class="mb-2"><?= $editing ? 'Edit' : 'Add' ?> Bill Design</h1>
  <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="post">
    <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int)$design['id'] ?>"><?php endif; ?>
    <div class="card mb-2"><div class="card-header">Design and company</div><div class="card-body">
      <div class="row g-2">
        <div class="col-md-6"><label class="form-label">Design name</label><input name="design_name" class="form-control" required value="<?= htmlspecialchars($design['design_name']) ?>"></div>
        <div class="col-md-6"><label class="form-label">Company name</label><input name="company_name" class="form-control" required value="<?= htmlspecialchars($design['company_name']) ?>"></div>
        <div class="col-md-6"><label class="form-label">Tagline</label><input name="tagline" class="form-control" value="<?= htmlspecialchars($design['tagline'] ?? '') ?>"></div>
        <div class="col-md-6"><label class="form-label">PAN / VAT number</label><input name="pan_number" class="form-control" value="<?= htmlspecialchars($design['pan_number'] ?? '') ?>"></div>
        <div class="col-12"><label class="form-label">Address</label><input name="address" class="form-control" value="<?= htmlspecialchars($design['address'] ?? '') ?>"></div>
      </div>
    </div></div>
    <div class="card mb-2"><div class="card-header">Contact and bill footer</div><div class="card-body">
      <div class="row g-2">
        <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($design['email'] ?? '') ?>"></div>
        <div class="col-md-6"><label class="form-label">Phone</label><input name="phone" class="form-control" value="<?= htmlspecialchars($design['phone'] ?? '') ?>"></div>
        <div class="col-md-6"><label class="form-label">Website</label><input name="website" class="form-control" value="<?= htmlspecialchars($design['website'] ?? '') ?>"></div>
        <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" rows="2" class="form-control"><?= htmlspecialchars($design['notes'] ?? '') ?></textarea></div>
      </div>
    </div></div>
    <div class="card mb-2"><div class="card-header">Signature</div><div class="card-body">
      <div class="row g-2">
        <div class="col-md-6"><label class="form-label">Name</label><input name="signature_name" class="form-control" value="<?= htmlspecialchars($design['signature_name'] ?? '') ?>"></div>
        <div class="col-md-6"><label class="form-label">Post</label><input name="signature_post" class="form-control" value="<?= htmlspecialchars($design['signature_post'] ?? '') ?>"></div>
      </div>
    </div></div>
    <div class="card mb-2 appearance-card"><div class="card-header">Appearance</div><div class="card-body"><div class="appearance-controls">
      <label class="appearance-color"><span>Primary color</span><input type="color" name="primary_color" class="form-control form-control-color" value="<?= htmlspecialchars($design['primary_color']) ?>"></label>
      <label class="appearance-color"><span>Accent color</span><input type="color" name="accent_color" class="form-control form-control-color" value="<?= htmlspecialchars($design['accent_color']) ?>"></label>
      <label class="form-check appearance-active"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="is-active" <?= $design['is_active'] ? 'checked' : '' ?>><span class="form-check-label">Use this design for printed bills</span></label>
    </div></div></div>
    <button class="btn btn-primary"><i class="fas fa-save"></i> Save Design</button>
    <a href="bill-designs.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>