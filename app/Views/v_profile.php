<?php $this->extend('layout') ?>
<?php $this->section('content') ?>

<style>
  .profile-left {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 28px 24px;
    border-right: 1px solid #f0f0f0;
  }
  .profile-avatar {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #4154f1;
    box-shadow: 0 4px 18px rgba(65,84,241,0.22);
    margin-bottom: 14px;
  }
  .profile-left h5 {
    font-weight: 700;
    color: #012970;
    margin-bottom: 2px;
    font-size: 1.05rem;
    text-align: center;
  }
  .profile-left small {
    color: #6c757d;
    font-size: 0.8rem;
    text-align: center;
    display: block;
    margin-bottom: 6px;
  }
  .profile-right {
    padding: 28px 28px;
  }
  .profile-right .card-title {
    font-size: 1rem;
    font-weight: 700;
    color: #012970;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 10px;
    margin-bottom: 18px;
  }
  .profile-right .row {
    padding: 9px 0;
    border-bottom: 1px solid #f4f4f4;
    align-items: center;
  }
  .profile-right .row:last-child { border-bottom: none; }
  .profile-right .label {
    font-weight: 600;
    color: #4154f1;
    font-size: 0.875rem;
  }
  .profile-right .value {
    color: #444;
    font-size: 0.875rem;
  }
  .badge-role {
    font-size: 0.72rem;
    padding: 3px 10px;
    border-radius: 20px;
    font-weight: 600;
    text-transform: capitalize;
    vertical-align: middle;
  }
  .badge-admin { background-color: #dc3545; color: #fff; }
  .badge-user  { background-color: #0d6efd; color: #fff; }
  .badge-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background-color: #198754;
    color: #fff;
    font-size: 0.78rem;
    padding: 4px 13px;
    border-radius: 20px;
    font-weight: 600;
  }
</style>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body p-0">
        <div class="row g-0">

          <!-- Foto kiri -->
          <div class="col-md-3 col-sm-12 profile-left">
            <img src="<?= base_url() ?>NiceAdmin/assets/img/profile-img.jpg"
                 alt="Profile Photo" class="profile-avatar">
            <h5><?= esc($nama) ?></h5>
            <small><?= esc($username) ?></small>
            <small>
              <span class="badge-role <?= $role === 'admin' ? 'badge-admin' : 'badge-user' ?>">
                <?= esc($role) ?>
              </span>
            </small>
          </div>

          <!-- Info kanan -->
          <div class="col-md-9 col-sm-12 profile-right">
            <h5 class="card-title">Profile Information</h5>

            <div class="row">
              <div class="col-lg-3 col-md-4 label"><i class="bi bi-person-badge-fill me-1"></i> Nama Lengkap</div>
              <div class="col-lg-9 col-md-8 value"><?= esc($nama) ?></div>
            </div>

            <div class="row">
              <div class="col-lg-3 col-md-4 label"><i class="bi bi-person-fill me-1"></i> Username</div>
              <div class="col-lg-9 col-md-8 value">
                <?= esc($username) ?> &nbsp;
                <span class="badge-role <?= $role === 'admin' ? 'badge-admin' : 'badge-user' ?>">
                  <?= esc($role) ?>
                </span>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-3 col-md-4 label"><i class="bi bi-shield-lock-fill me-1"></i> Role</div>
              <div class="col-lg-9 col-md-8 value text-capitalize"><?= esc($role) ?></div>
            </div>

            <div class="row">
              <div class="col-lg-3 col-md-4 label"><i class="bi bi-envelope-fill me-1"></i> Email</div>
              <div class="col-lg-9 col-md-8 value">
                <a href="mailto:<?= esc($email) ?>" class="text-decoration-none text-primary"><?= esc($email) ?></a>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-3 col-md-4 label"><i class="bi bi-clock-fill me-1"></i> Login Time</div>
              <div class="col-lg-9 col-md-8 value"><?= esc($loginTime) ?></div>
            </div>

            <div class="row">
              <div class="col-lg-3 col-md-4 label"><i class="bi bi-wifi me-1"></i> Status</div>
              <div class="col-lg-9 col-md-8 value">
                <?php if ($isLoggedIn): ?>
                  <span class="badge-status"><i class="bi bi-check-circle-fill"></i> Sudah Login</span>
                <?php else: ?>
                  <span style="color:#dc3545;font-weight:600;"><i class="bi bi-x-circle-fill"></i> Belum Login</span>
                <?php endif; ?>
              </div>
            </div>

          </div><!-- End info kanan -->
        </div><!-- End row g-0 -->
      </div>
    </div>
  </div>
</div>

<?php $this->endSection() ?>
